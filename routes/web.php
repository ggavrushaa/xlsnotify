<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users/{user}/unsigned-documents', [UserController::class, 'unsignedDocuments']);

