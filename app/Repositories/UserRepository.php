<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends Repository
{
    public function create(User $user): User {
        $data = $this->databaseManager->select(
            "CALL insert_user(:name, :email, :password, :created_at)",
            [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->getAuthPassword(),
                'created_at' => $user->created_at,
            ]
        );

        $user->id = $data[0]?->ID;
        return $user;
    }

    public function findById(int $id): ?User {
        $data = $this->databaseManager->select(
            "CALL get_user_by_id(:id)",
             ['id' => $id]
        );

        return User::hydrate($data)->first();
    }

    public function findByEmail(string $email): ?User {
        $data = $this->databaseManager->select(
            "CALL get_user_by_email(:email)",
             ['email' => $email]
        );

        return User::hydrate($data)->first();
    }
}