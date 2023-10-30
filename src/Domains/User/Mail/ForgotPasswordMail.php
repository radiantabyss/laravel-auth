<?php
namespace Lumi\Auth\Domains\User\Mail;

use Lumi\Core\Mail;

class ForgotPasswordMail extends Mail
{
    public function build() {
        $subject = str_replace('{{app_name}}', config('app.name'), config('lumi-auth.mail_subjects.forgot-password'));
        $this->subject($subject);

        $view = \View::exists('Auth.User::forgot-password') ? 'Auth.User::forgot-password' : 'Lumi.Auth.User::forgot-password';
        $this->view($view, $this->params);

        return $this;
    }
}
