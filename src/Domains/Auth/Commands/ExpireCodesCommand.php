<?php
namespace RA\Auth\Domains\Auth\Commands;

use Illuminate\Console\Command;
use RA\Auth\Services\ClassName;

class ExpireCodesCommand extends Command
{
    protected $signature = 'ra-auth:expire-codes';
    protected $description = 'Expires user codes and invites.';

    public function handle() {
        ClassName::Model('UserCode')::where('expires_at', '<', date('Y-m-d H:i:s'))->delete();
        ClassName::Model('UserInvite')::where('expires_at', '<', date('Y-m-d H:i:s'))->delete();
    }
}
