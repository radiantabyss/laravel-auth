<?php
namespace RA\Auth\Domains\User\Mail;

use RA\Mail;

class WelcomeMail extends Mail
{
    public function build() {
        $subject = str_replace('{{app_name}}', config('app.name'), config('ra-auth.mail_subjects.welcome'));
        $this->subject($subject);

        $view = \View::exists('Auth.User::welcome') ? 'Auth.User::welcome' : 'RA.Auth.User::welcome';
        $this->view($view, $this->params);

        return $this;
    }
}
