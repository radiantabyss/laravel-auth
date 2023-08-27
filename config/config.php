<?php
return [
    'send_welcome_mail' => true,
    'activation_required' => true,
    'use_teams' => true,
    'allowed_team_roles' => ['member'],
    'country_guesser_installed' => true,

    'mail_subjects' => [
        'welcome' => 'Welcome to '.config('app.name'),
        'forgot-password' => 'Reset your password - '.config('app.name'),
        'invite' => 'You have been invited to join a team - '.config('app.name'),
    ],
];
