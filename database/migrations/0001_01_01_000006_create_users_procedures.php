<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('
            CREATE PROCEDURE IF NOT EXISTS insert_user(
                IN u_name VARCHAR(50),
                IN u_email VARCHAR(255),
                IN u_password VARCHAR(255),
                IN u_created_at DATETIME
            ) BEGIN
                INSERT INTO users (name, email, password, created_at)
                VALUES (u_name, u_email, u_password, u_created_at);

                SELECT LAST_INSERT_ID() AS ID;
            END;
        ');

        DB::statement('
            CREATE PROCEDURE IF NOT EXISTS get_user_by_id(
                IN u_id BIGINT
            ) BEGIN
                SELECT * FROM users
                WHERE id = u_id
                LIMIT 1;
            END;
        ');

        DB::statement('
            CREATE PROCEDURE IF NOT EXISTS get_user_by_email(
                IN u_email VARCHAR(255)
            ) BEGIN
                SELECT * FROM users
                WHERE email COLLATE "utf8mb4_unicode_ci" = u_email COLLATE "utf8mb4_unicode_ci"
                LIMIT 1;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP PROCEDURE IF EXISTS insert_user');
        DB::statement('DROP PROCEDURE IF EXISTS get_user_by_id');
        DB::statement('DROP PROCEDURE IF EXISTS get_user_by_email');
    }
};
