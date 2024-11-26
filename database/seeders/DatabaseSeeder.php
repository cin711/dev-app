<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    public function __construct(private DatabaseManager $databaseManager) {}

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->databaseManager->statement("
            CALL insert_user(
                :name,
                :email,
                :password,
                :created_at
            )
        ", [
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'created_at' => Carbon::now()
        ]);
    }
}
