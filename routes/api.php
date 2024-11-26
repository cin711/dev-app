<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::post("/login", [AuthController::class, "login"])->name("login");
Route::post("/register", [AuthController::class, "register"])->name("register");

Route::group(['prefix' => '/departments', 'middleware'=> ['auth:sanctum']], function () {
    Route::get('/', [DepartmentController::class, 'index'])->name('department.index');
    Route::get('/{id}', [DepartmentController::class, 'get'])->name('department.get');
    Route::get('/{name}/hierachy', [DepartmentController::class, 'hierarchy'])->name('department.hierarchy');
    Route::post('/', [DepartmentController::class, 'create'])->name('department.create');
    Route::put('/{id}', [DepartmentController::class,'update'])->name('department.update');
    Route::delete('/{id}', [DepartmentController::class,'delete'])->name('department.delete');
    Route::patch('/{id}/approve', [DepartmentController::class,'approve'])->name('department.approve');
    Route::patch('/{id}/activate', [DepartmentController::class,'activate'])->name('department.activate');
    Route::patch('/{id}/deactivate', [DepartmentController::class,'deactivate'])->name('department.deactivate');
});
