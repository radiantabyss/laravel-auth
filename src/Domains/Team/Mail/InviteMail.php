<?php
namespace RA\Auth\Domains\Team\Mail;

use RA\Mail;

class InviteMail extends Mail
{
    public function build() {
        $subject = str_replace('{{app_name}}', config('app.name'), config('ra-auth.mail_subjects.invite'));
        $subject = str_replace('{{team_name}}', $this->params['team']->name, $subject);
        $this->subject($subject);

        $view = \View::exists('Auth.Team::invite') ? 'Auth.Team::invite' : 'RA.Auth.Team::invite';
        $this->view($view, $this->params);

        return $this;
    }
}
