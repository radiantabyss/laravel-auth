<?php
namespace RA\Auth\Domains\Auth\Mail;

use RA\Core\Mail;

class ForgotPasswordMail extends Mail
{
    public function build() {
        $this->subject(config('ra-auth.mail_subjects.forgot-password'));

        try {
            $this->view('Auth::forgot-password', $this->params);
        }
        catch(Exception $e) {
            $this->view('RA.Auth::forgot-password', $this->params);
        }

        return $this;
    }
}
