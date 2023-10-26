<?php
namespace Lumi\Auth\Domains\User\Commands;

use Illuminate\Console\Command;
use Lumi\Auth\Services\ClassName;

class ExpireCodesCommand extends Command
{
    protected $signature = 'ra-auth:expire-codes';
    protected $description = 'Expires user codes and invites.';

    public function handle() {
        ClassName::Model('UserCode')::where('expires_at', '<', date('Y-m-d H:i:s'))->delete();
    }
}
