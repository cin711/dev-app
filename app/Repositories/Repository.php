<?php

namespace App\Repositories;

use Illuminate\Database\DatabaseManager;

abstract class Repository
{

    public function __construct(
        protected DatabaseManager $databaseManager
    ) {
    }
}