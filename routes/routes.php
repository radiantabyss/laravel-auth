<?php
use Illuminate\Support\Facades\Route;

//no auth
Route::group(['middleware' => ['RA\Auth\NoAuth']], function() {
    Route::post('/register', ['uses' => 'RegisterAction@run']);
    Route::post('/forgot-password', ['uses' => 'ForgotPasswordAction@run']);
    Route::post('/reset-password', ['uses' => 'ResetPasswordAction@run']);
    Route::post('/activate/{id}/{activation_code}', ['uses' => 'ActivateAction@run']);
    Route::post('/login', ['uses' => 'LoginAction@run']);
    Route::post('/accept-invite', ['uses' => 'AcceptInviteAction@run']);
});

//with auth
Route::group(['middleware' => ['RA\Auth\Auth']], function() {
    Route::get('/get', ['uses' => 'GetAction@run']);
    Route::post('/patch', ['uses' => 'PatchAction@run']);
    Route::post('/upload-logo', ['uses' => 'UploadLogoAction@run']);
    Route::options('/upload-logo', ['uses' => 'UploadLogoAction@run']);
});
