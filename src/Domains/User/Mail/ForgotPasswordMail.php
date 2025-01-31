<?php
namespace RA\Auth\Domains\User\Mail;

use RA\Mail;

class ForgotPasswordMail extends Mail
{
    public function build() {
        $subject = str_replace('{{app_name}}', config('app.name'), config('ra-auth.mail_subjects.forgot-password'));
        $this->subject($subject);

        $view = \View::exists('Auth.User::forgot-password') ? 'Auth.User::forgot-password' : 'RA.Auth.User::forgot-password';
        $this->view($view, $this->params);

        return $this;
    }
}
