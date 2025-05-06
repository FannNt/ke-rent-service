<?php

namespace App\Services;

use App\Models\User;

interface ServiceInterface
{
    public function index();

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function findById($id);

    public function getByUserId($userId);
}