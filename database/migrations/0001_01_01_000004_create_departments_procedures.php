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
            CREATE PROCEDURE IF NOT EXISTS insert_department(
                IN d_name VARCHAR(50),
                IN d_parent_id BIGINT,
                IN d_flags TINYINT,
                IN d_created_at DATETIME
            ) BEGIN
                INSERT INTO departments (name, parent_id, flags, created_at)
                VALUES (d_name, d_parent_id, d_flags, d_created_at);

                SELECT LAST_INSERT_ID() AS ID;
            END;
        ');

        DB::statement('
            CREATE PROCEDURE IF NOT EXISTS update_department(
                IN d_id BIGINT,
                IN d_name VARCHAR(50),
                IN d_parent_id BIGINT,
                IN d_flags TINYINT,
                IN d_updated_at DATETIME
            ) BEGIN
                UPDATE 
                    departments
                SET 
                    name = d_name,
                    parent_id = d_parent_id,
                    flags = d_flags,
                    updated_at = d_updated_at
                WHERE
                    id = d_id;
            END;
        ');

        DB::statement('
            CREATE PROCEDURE IF NOT EXISTS get_department_by_id(
                IN d_id BIGINT
            ) BEGIN
                SELECT * FROM departments
                WHERE id = d_id;
            END;
        ');

        DB::statement('
            CREATE PROCEDURE IF NOT EXISTS get_departments(
                IN p_limit BIGINT,
                IN p_offset BIGINT
            ) BEGIN
                SELECT * FROM departments
                LiMIT p_limit
                OFFSET p_offset;
            END;
        ');

        DB::statement('
            CREATE PROCEDURE IF NOT EXISTS count_departments()
            BEGIN
                SELECT COUNT(id) as COUNT FROM departments;
            END;
        ');

        DB::statement('
            CREATE PROCEDURE IF NOT EXISTS get_department_hierarchy(IN d_name VARCHAR(50))
            BEGIN
                WITH RECURSIVE department_hierarchy AS (
                    SELECT d.*
                    FROM departments d
                    WHERE d.name COLLATE "utf8mb4_unicode_ci" = d_name COLLATE "utf8mb4_unicode_ci"
                    UNION ALL
                    SELECT d.*
                    FROM departments d
                    JOIN department_hierarchy dh ON d.parent_id = dh.id
                )
                SELECT * FROM department_hierarchy;
            END;
        ');

        DB::statement('
            CREATE PROCEDURE IF NOT EXISTS set_department_hierarchy_flag(
                IN d_id BIGINT,
                IN d_flag TINYINT
            )
            BEGIN
                UPDATE departments
                SET
                    flags = flags | d_flag
                WHERE id IN (
                    WITH RECURSIVE department_hierarchy AS (
                        SELECT d.id
                        FROM departments d
                        WHERE d.id = d_id
                        UNION ALL
                        SELECT d.id
                        FROM departments d
                        JOIN department_hierarchy dh ON d.parent_id = dh.id
                    )
                    SELECT * FROM department_hierarchy
                );
            END;
        ');

        DB::statement('
            CREATE PROCEDURE IF NOT EXISTS unset_department_hierarchy_flag(
                IN d_id BIGINT,
                IN d_flag TINYINT
            )
            BEGIN
                UPDATE departments
                SET
                    flags = flags & (~d_flag)
                WHERE id IN (
                    WITH RECURSIVE department_hierarchy AS (
                        SELECT d.id
                        FROM departments d
                        WHERE d.id = d_id
                        UNION ALL
                        SELECT d.id
                        FROM departments d
                        JOIN department_hierarchy dh ON d.parent_id = dh.id
                    )
                    SELECT * FROM department_hierarchy
                );
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP PROCEDURE IF EXISTS insert_department');
        DB::statement('DROP PROCEDURE IF EXISTS update_department');
        DB::statement('DROP PROCEDURE IF EXISTS get_department_by_id');
        DB::statement('DROP PROCEDURE IF EXISTS get_departments');
        DB::statement('DROP PROCEDURE IF EXISTS count_departments');
        DB::statement('DROP PROCEDURE IF EXISTS get_department_hierarchy');
        DB::statement('DROP PROCEDURE IF EXISTS set_department_hierarchy_flag');
        DB::statement('DROP PROCEDURE IF EXISTS unset_department_hierarchy_flag');

    }
};
