<?php
namespace RA\Auth\Domains\Team\Transformers;

use RA\Auth\Services\ClassName;

class InviteTransformer
{
    public static function run($data, $team_id) {
        $formatted = [];

        //get all emails from input
        preg_match_all("/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/", $data['emails'], $matches);
        $emails = array_unique($matches[0]);

        //check if users with email is already a part of the team
        $user_emails = keyBy(ClassName::Model('TeamMember')::select('email')
            ->leftJoin('user', 'user.id', '=', 'team_member.user_id')
            ->where('team_id', $team_id)
            ->whereIn('user.email', $emails)
            ->get(), 'email');

        //check if users with email were already invited
        $invited_emails = keyBy(ClassName::Model('TeamInvite')::where('team_id', $team_id)
            ->whereIn('email', $emails)
            ->get(), 'email');

        foreach ( $emails as $email ) {
            if ( isset($user_emails[$email]) || isset($invited_emails[$email]) ) {
                continue;
            }

            $formatted[] = [
                'team_id' => $team_id,
                'email' => $email,
                'role' => $data['role'],
                'code' => \Str::random(30),
                'expires_at' => date('Y-m-d H:i:s', strtotime('+2 hours')),
            ];
        }

        return $formatted;
    }
}
