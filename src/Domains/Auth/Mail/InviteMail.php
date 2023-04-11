<?php
namespace RA\Auth\Domains\Auth\Mail;

use RA\Core\Mail;

class InviteMail extends Mail
{
    public function build() {
        $this->subject(config('ra-auth.mail_subjects.invite'));

        if ( \View::exists('Auth::invite') ) {
            $this->view('Auth::invite', $this->params);
        }
        else {
            $this->view('RA.Auth::invite', $this->params);
        }

        return $this;
    }
}
