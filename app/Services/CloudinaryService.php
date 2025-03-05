<?php

namespace App\Services;


use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    public function uploadProduct($data)
    {
        $uploadedFile = cloudinary()->upload($data->getRealPath(), [
            'folder' => 'kerent'
        ]);

        return [
            'public_id' => $uploadedFile->getPublicId(),
            'url' => $uploadedFile->getSecurePath(),
        ];
    }

    public function deleteProduct($data)
    {
        try {
            cloudinary()->destroy($data);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed Delete image', [
                'error' => $e->getMessage()
            ]);
        }
        return true;
    }
}
