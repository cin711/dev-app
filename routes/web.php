<?php

use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return response()->view("layouts/app");
});

Route::get("/app/{any?}", function () {
    return response()->view("layouts/app");
})->where('any', '.*');