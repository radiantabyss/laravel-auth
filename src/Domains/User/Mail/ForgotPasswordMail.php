<?php
namespace RA\Auth\Domains\User\Mail;

use RA\Core\Mail;

class ForgotPasswordMail extends Mail
{
    public function build() {
        $this->subject(config('ra-auth.mail_subjects.forgot-password'));

        if ( \View::exists('Auth.User::forgot-password') ) {
            $this->view('Auth.User::forgot-password', $this->params);
        }
        else {
            $this->view('RA.Auth.User::forgot-password', $this->params);
        }

        return $this;
    }
}
