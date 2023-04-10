<?php
return [
    'send_welcome_mail' => true,
    'activation_required' => true,
    'country_guesser_installed' => true,

    'mail_subjects' => [
        'welcome' => 'Welcome to '.config('app.name'),
        'reset-password' => 'Reset your password - '.config('app.name'),
        'invite' => 'You have been invited to join a team - '.config('app.name'),
    ],
];
