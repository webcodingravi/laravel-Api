<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::view('login','login');
Route::view('posts','allPost');
Route::view('addPost','addPost');
        