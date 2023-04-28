<?php
namespace RA\Auth\Domains\Team\Mail;

use RA\Core\Mail;

class InviteMail extends Mail
{
    public function build() {
        $this->subject(config('ra-auth.mail_subjects.invite'));

        if ( \View::exists('Auth.Team::invite') ) {
            $this->view('Auth.Team::invite', $this->params);
        }
        else {
            $this->view('RA.Auth.Team::invite', $this->params);
        }

        return $this;
    }
}
