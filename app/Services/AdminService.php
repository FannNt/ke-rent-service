<?php

namespace App\Services;

use App\Interface\User\UserRepositoryInterface;

class AdminService
{
    protected $userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

}
