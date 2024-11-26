<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    public function store(User $user) {
        $user->created_at = Carbon::now();
        return $this->userRepository->create($user);
    }    

    public function getOrFail(int $id): User|null {
        $user = $this->userRepository->findById($id);
        if (! $user) {
            $exception = new ModelNotFoundException();
            $exception->setModel(User::class, [$id]);
            throw $exception;
        }

        return $user;
    }

    public function getByEmail(string $email): ?User {
        return $this->userRepository->findByEmail($email);
    }
}