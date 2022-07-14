<?php
namespace RA\Auth\Http\Actions;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as Action;
use App\Core\MailSender;
use App\Core\Response;
use RA\Auth\Validators\ForgotPasswordValidator as Validator;
use RA\Auth\Mail\ResetPasswordMail;
use RA\Auth\Services\ClassName;

class ForgotPasswordAction extends Action
{
    public function run(Request $request) {
        $data = $request->all();

        $validation = Validator::run($data);
        if ( $validation !== true) {
            return Response::error($validation);
        }

        //get user by email
        $item = ClassName::Model()::where('email', $data['email'])->first();

        //set reset code
        $item->update([
            'reset_code' => \Str::random(20),
            'reset_code_date' => date('Y-m-d H:i:s'),
        ]);

        //send mail
        MailSender::send(ResetPasswordMail::class, $item->email, $item);

        return Response::success();
    }
}
