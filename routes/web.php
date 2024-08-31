<?php

use Illuminate\Support\Facades\Route;

// Default welcome route
Route::get('/', function () {
    return view('welcome');
});

// Route for testing the "Email Verification Successful" page
Route::get('/email/verified/success', function () {
    // Example user data, you can change it as needed
    $user = (object) ['email' => 'test@example.com'];
    return view('verify-success', compact('user'));
})->name('email.verified.success');

// Route for testing the "Email Already Verified" page
Route::get('/email/verified/already', function () {
    // Example user data, you can change it as needed
    $user = (object) ['email' => 'test@example.com'];
    return view('verify-already', compact('user'));
})->name('email.verified.already');

Route::get('/test-email', function () {
    $user = (object) [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com'
    ];
    $password = 'XRTy0945';
    $verificationUrl = 'https://example.com/verify';

    return view('emails.verify', compact('user', 'password', 'verificationUrl'));
});

