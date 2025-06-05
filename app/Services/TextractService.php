<?php

namespace App\Services;

use App\Classes\ApiResponse;
use Aws\S3\S3Client;
use Aws\Textract\Exception\TextractException;
use Aws\Textract\TextractClient;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use InvalidArgumentException;

class TextractService
{
    protected $textract;
    protected $s3;

    public function __construct()
    {
        $config = [
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION', 'ap-southeast-1'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ]
        ];

        $this->textract = new TextractClient($config);
        $this->s3 = new S3Client($config);
    }



    protected function scanId($key)
    {
        $result = $this->textract->detectDocumentText([
            'Document' => [
                'S3Object' => [
                    'Bucket' => env("AWS_BUCKET"),
                    'Name'   => $key
                ]
            ]
        ]);
        return $this->parseKtpResponse($result);
    }

    protected function parseKtpResponse($response)
    {
        $rawLines = [];
        $lineConfidences = [];

        foreach ($response['Blocks'] as $block) {
            if ($block['BlockType'] == 'LINE') {
                $rawLines[] = $block['Text'];
                $lineConfidences[] = $block['Confidence'];
            }
        }

        $ktpData = [
            'nik' => null,
            'nama' => null,
            'tempat_tgl_lahir' => null,
            'alamat' => null,
            'rt_rw' => null,
            'kel_desa' => null,
            'kecamatan' => null,
            'agama' => null,
            'status_perkawinan' => null,
            'pekerjaan' => null,
            'kewarganegaraan' => null,
            'berlaku_hingga' => null,
            'raw_text' => implode("\n", $rawLines),
        ];

        $currentField = null;

        foreach ($rawLines as $index => $line) {
            $line = trim($line);

            // NIK Pattern
            if (preg_match('/(?:NIK|IK)?[:\s]*(\d{16})/i', $line, $matches)) {
                $ktpData['nik'] = $matches[1];
            }

            // Nama Pattern - Look for the line after "ama"
            elseif (strtolower(trim($line)) === 'ama' && isset($rawLines[$index + 1])) {
                $nextLine = trim($rawLines[$index + 1]);
                if (strpos($nextLine, ':') === 0) {
                    $ktpData['nama'] = trim(substr($nextLine, 1));
                }
            }

            // Tempat/Tgl Lahir Pattern
            elseif (preg_match('/(?:empat|Tempat).*(?:Lahir)[:\s]*([^,]+),\s*(\d{2}-\d{2}-\d{4})/i', $line, $matches)) {
                $tempat = trim($matches[1]);
                $tanggal = $matches[2];
                $ktpData['tempat_tgl_lahir'] = $tempat . ', ' . $this->formatIndonesianDate($tanggal);
            }

            // Alamat Pattern - Look for the line after "lamat"
            elseif (strtolower(trim($line)) === 'lamat' && isset($rawLines[$index + 1])) {
                $nextLine = trim($rawLines[$index + 1]);
                if (strpos($nextLine, ':') === 0) {
                    $ktpData['alamat'] = trim(substr($nextLine, 1));
                }
            }

            // RT/RW Pattern
            elseif (preg_match('/RT\/RW[:\s]*(\d{3}\/\d{3})/i', $line, $matches)) {
                $ktpData['rt_rw'] = $matches[1];
            }

            // Kel/Desa Pattern
            elseif (strpos($line, 'Kel/Desa') !== false && isset($rawLines[$index + 1])) {
                $nextLine = trim($rawLines[$index + 1]);
                if (strpos($nextLine, ':') === 0) {
                    $ktpData['kel_desa'] = trim(substr($nextLine, 1));
                }
            }

            // Kecamatan Pattern
            elseif (preg_match('/Kecamatan[:\s]*([^:]+)/i', $line, $matches)) {
                $ktpData['kecamatan'] = trim($matches[1]);
            }

            // Agama Pattern - Look for the line after "gama"
            elseif (strtolower(trim($line)) === 'gama' && isset($rawLines[$index + 1])) {
                $nextLine = trim($rawLines[$index + 1]);
                if (strpos($nextLine, ':') === 0) {
                    $ktpData['agama'] = trim(substr($nextLine, 1));
                }
            }

            // Status Perkawinan Pattern
            elseif (preg_match('/(?:Status|atus)\s*Perkawinan[:\s]*([^:]+)/i', $line, $matches)) {
                $ktpData['status_perkawinan'] = trim($matches[1]);
            }

            // Pekerjaan Pattern - Look for the line after "kerjaan"
            elseif (strtolower(trim($line)) === 'kerjaan' && isset($rawLines[$index + 1])) {
                $nextLine = trim($rawLines[$index + 1]);
                if (strpos($nextLine, ':') === 0) {
                    $ktpData['pekerjaan'] = trim(substr($nextLine, 1));
                }
            }

            // Kewarganegaraan Pattern
            elseif (preg_match('/warganegaraan[:\s]*/i', $line) && isset($rawLines[$index + 1])) {
                $nextLine = trim($rawLines[$index + 1]);
                if ($nextLine === 'WNI') {
                    $ktpData['kewarganegaraan'] = 'WNI';
                }
            }

            // Berlaku Hingga Pattern
            elseif (preg_match('/(?:Berlaku|riaku)\s*Hingga[:\s]*([^:]+)/i', $line, $matches)) {
                if (trim($matches[1]) === 'SEUMUR HIDUP') {
                    $ktpData['berlaku_hingga'] = 'SEUMUR HIDUP';
                } elseif (isset($rawLines[$index + 1]) && preg_match('/(\d{2}-\d{2}-\d{4})/', $rawLines[$index + 1], $dateMatches)) {
                    $ktpData['berlaku_hingga'] = $this->formatIndonesianDate($dateMatches[1]);
                }
            }
        }

        // Clean up any remaining colons in the values
        foreach ($ktpData as $key => $value) {
            if (is_string($value)) {
                $ktpData[$key] = trim(str_replace(':', '', $value));
            }
        }

        // Log the final extracted data
        Log::info('Final KTP Data:', $ktpData);

        return $ktpData;
    }

    protected function formatIndonesianDate($dateString)
    {
        // Convert DD-MM-YYYY to YYYY-MM-DD
        if (preg_match('/^([0-9]{2})-([0-9]{2})-([0-9]{4})$/', $dateString, $matches)) {
            return $matches[3] . '-' . $matches[2] . '-' . $matches[1];
        }
        return $dateString;
    }

    public function uploadKtp($image)
    {
        try {
            $bucket = env('AWS_BUCKET');
            $key = 'ktp/' . time() . '_' . $image->getClientOriginalName();

            $this->s3->putObject([
                'Bucket' => $bucket,
                'Key'    => $key,
                'Body'   => fopen($image->getRealPath(), 'r'),
                'ACL'    => 'private'
            ]);

            // Generate presigned URL (valid for 1 hour) - for viewing the image
            $presignedUrl = $this->s3->createPresignedRequest(
                $this->s3->getCommand('GetObject', [
                    'Bucket' => $bucket,
                    'Key'    => $key
                ]),
                '+1 hour'
            )->getUri()->__toString();

            // Create a simple S3 URL for storage
            $s3Url = "s3://{$bucket}/{$key}";

            $ktpData = $this->scanId($key);

            $formattedData = [
                'nik' => $ktpData['nik'],
                'nama' => $ktpData['nama'],
                'ttl' => $ktpData['tempat_tgl_lahir'],
                'alamat' => $ktpData['alamat'],
                'rt_rw' => $ktpData['rt_rw'],
                'kel_desa' => $ktpData['kel_desa'],
                'kecamatan' => $ktpData['kecamatan'],
                'agama' => $ktpData['agama'],
                'pekerjaan' => $ktpData['pekerjaan'],
                'warga_negara' => $ktpData['kewarganegaraan'],
                'ktp_url' => $s3Url,
            ];

            // Validate required fields
            $requiredFields = [];
            $missingFields = [];
            foreach ($requiredFields as $field) {
                if (empty($formattedData[$field])) {
                    $missingFields[] = $field;
                }
            }

            if (!empty($missingFields)) {
                Log::error('Missing required fields:', $missingFields);
                throw new HttpResponseException(
                    ApiResponse::sendErrorResponse("Missing required fields: " . implode(', ', $missingFields), 400)
                );
            }

            return [
                'scan_data' => $formattedData,
                's3_path' => $s3Url
            ];

        } catch (HttpResponseException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::error('KTP Processing Failed:', [
                'error' => $e->getMessage(),
                'file_info' => [
                    'name' => $image->getClientOriginalName(),
                    'size' => $image->getSize(),
                    'mime' => $image->getMimeType()
                ]
            ]);
            throw new HttpResponseException(
                ApiResponse::sendErrorResponse('Failed to process KTP: ' . $e->getMessage())
            );
        }
    }

    public function removeKtp($s3Url)
    {
        try {
            // Parse the s3:// URL format
            if (!preg_match('#^s3://([^/]+)/(.+)$#', $s3Url, $matches)) {
                throw new InvalidArgumentException('Invalid S3 URL format. Expected: s3://bucket/key');
            }

            $bucket = $matches[1];
            $key = $matches[2];

            // Verify bucket matches our configuration
            $configuredBucket = env('AWS_BUCKET');
            if ($bucket !== $configuredBucket) {
                throw new InvalidArgumentException('Invalid bucket in S3 URL');
            }

            // Add debug logging
            Log::debug('Attempting to delete S3 object', [
                'bucket' => $bucket,
                'key' => $key
            ]);

            if ($this->s3->doesObjectExist($bucket, $key)) {
                $this->s3->deleteObject([
                    'Bucket' => $bucket,
                    'Key'    => $key
                ]);
                Log::info('Successfully deleted KTP image', [
                    'bucket' => $bucket,
                    'key' => $key
                ]);
                return true;
            }

            Log::warning('KTP image not found for deletion', [
                'bucket' => $bucket,
                'key' => $key,
                'original_url' => $s3Url
            ]);
            return false;

        } catch (Exception $e) {
            Log::error('Failed to delete KTP image:', [
                'error' => $e->getMessage(),
                'url' => $s3Url,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
