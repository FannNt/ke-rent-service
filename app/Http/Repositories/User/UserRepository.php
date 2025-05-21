<?php

namespace App\Http\Repositories\User;

use App\Models\User;
use App\Models\UserKtp;
use App\Models\UsersStatus;
use App\Interface\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;

class UserRepository implements UserRepositoryInterface {


    public function all()
    {
        return User::all();
    }

    public function create(array $data)
    {

        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        return User::delete($id);
    }

    public function findById($id)
    {
        return User::findOrFail($id);
    }

    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function createStatus()
    {

        return UsersStatus::create([
            'role' => 'user',
            'is_banned' => false,
            'last_seen' => now()
        ]);
    }

    public function bannedUser($id)
    {
        $user = User::findOrFail($id);
        if (!$user){
            return null;
        }
        UsersStatus::query()->where($id)->update([
            'is_banned' => true,
        ]);

        return true;
    }

    public function createKtp($data, $imageKtp)
    {
        Log::info('Creating KTP record with data:', $data);

        try {
            $user = auth()->user();

            return $user->ktp()->create([
                'user_id' => auth()->id(),
                'nik' => $data['nik'],
                'nama' => $data['nama'],
                'ttl' => $data['ttl'],
                'alamat' => $data['alamat'],
                'rt_rw' => $data['rt_rw'],
                'kel_desa' => $data['kel_desa'],
                'kecamatan' => $data['kecamatan'],
                'agama' => $data['agama'],
                'pekerjaan' => $data['pekerjaan'],
                'warga_negara' => $data['warga_negara'],
                'ktp_url' => $imageKtp
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create KTP record:', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            return false;
        }
    }

    public function findNik($nik)
    {
        return UserKtp::where('nik',$nik)->exists();
    }

    public function findByNumber($number)
    {
        return User::where('phone_number',$number)->first();
    }
}
