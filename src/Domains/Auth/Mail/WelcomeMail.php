<?php
namespace RA\Auth\Domains\Auth\Mail;

use RA\Core\Mail;

class WelcomeMail extends Mail
{
    public function build() {
        $this->subject(config('ra-auth.mail_subjects.welcome'));

        try {
            $this->view('Auth::welcome', $this->params);
        }
        catch(Exception $e) {
            $this->view('RA.Auth::welcome', $this->params);
        }

        return $this;
    }
}
