<?php

namespace App\Services;

use App\Models\User;

interface ServiceInterface
{
    public function create(array $data);
    public function update(User $user, array $data);
    public function delete($id);
    public function findById($id);

    public function login(array $data);
}
