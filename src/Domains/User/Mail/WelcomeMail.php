<?php
namespace RA\Auth\Domains\User\Mail;

use RA\Core\Mail;

class WelcomeMail extends Mail
{
    public function build() {
        $this->subject(config('ra-auth.mail_subjects.welcome'));

        if ( \View::exists('Auth::welcome') ) {
            $this->view('Auth::welcome', $this->params);
        }
        else {
            $this->view('RA.Auth::welcome', $this->params);
        }

        return $this;
    }
}
