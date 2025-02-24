<?php

namespace App\Http\Repositories;

use App\Models\User;
use App\Models\UsersStatus;
use App\UserRepositoryInterface;

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
}
