<?php
return [
    'send_welcome_mail' => true,
    'activation_required' => false,
    'use_teams' => true,
    'allowed_team_roles' => ['member'],
    'country_guesser_installed' => false,

    'mail_subjects' => [
        'welcome' => 'Welcome to {{app_name}}',
        'forgot-password' => 'Reset your password - {{app_name}}',
        'invite' => 'You have been invited to join {{team_name}} - {{app_name}}',
    ],

    'encrypted_team_meta_fields' => [],
];
