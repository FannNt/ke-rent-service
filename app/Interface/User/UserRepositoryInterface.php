<?php

namespace App\Interface\User;

interface UserRepositoryInterface
{
    public function all();
    public function create(array $data);

    public function update($id,array $data);

    public function delete($id);
    public function findById($id);
    public function findByEmail($email);

    public function findByNumber($number);

    public function createStatus();

    public function createKtp($data,$imageKtp);

    public function findNik($nik);
}
