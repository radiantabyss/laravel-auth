<?php
return [
    'default_user_type' => 'user',
    
    'default_redirect_if_logged' => '/',
    'default_redirect_if_not_logged' => '/login',

    'redirect_after_login' => '/',
    'redirect_after_register' => '/after-registration',

    'patch_allowed_fields' => ['name', 'password', 'image_url', 'phone'],
    'image_url_path' => storage_path().'/user-profile-images',

    'mail_subjects' => [
        'activate-account' => 'Activate your account',
        'reset-password' => 'Reset your password',
    ],
];
