<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/e', function (){
    return event(new \App\Events\UserRegisterd(\App\Models\User::find(1)));
});