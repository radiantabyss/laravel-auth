<?php
use RA\Core\Route;

//no auth
Route::group(['middleware' => ['RA\Auth\NotLogged']], function() {
    Route::post('/register', 'RegisterAction');
    Route::post('/forgot-password', 'ForgotPasswordAction');
    Route::post('/reset-password', 'ResetPasswordAction');
    Route::post('/confirm', 'ConfirmAction');
    Route::post('/login', 'LoginAction');

    Route::post('/accept-invite', 'AcceptInviteAction');
});

//with auth
Route::group(['middleware' => ['RA\Auth\Logged']], function() {
    Route::get('/get', 'GetAction');
    Route::post('/patch', 'PatchAction');
    Route::post('/upload-profile-image', 'UploadProfileImageAction');
    Route::options('/upload-profile-image', 'UploadProfileImageAction');

    Route::post('/create-team', 'CreateTeamAction');
    Route::post('/update-team/{team_id}', 'UpdateTeamAction');
    Route::post('/switch-team/{team_id}', 'SwitchTeamAction');
    Route::post('/invite', 'InviteAction');
    Route::post('/upload-team-logo', 'UploadTeamLogoAction');
    Route::options('/upload-team-logo', 'UploadTeamLogoAction');
});
