<?php
namespace Lumi\Auth\Domains\User\Mail;

use Lumi\Core\Mail;

class WelcomeMail extends Mail
{
    public function build() {
        $subject = str_replace('{{app_name}}', config('app.name'), config('lumi-auth.mail_subjects.welcome'));
        $this->subject($subject);

        $view = \View::exists('Auth.User::welcome') ? 'Auth.User::welcome' : 'Lumi.Auth.User::welcome';
        $this->view($view, $this->params);

        return $this;
    }
}
