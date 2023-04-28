<?php
namespace RA\Auth\Domains\User\Commands;

use Illuminate\Console\Command;
use RA\Auth\Services\ClassName;

class ExpireInvitesCommand extends Command
{
    protected $signature = 'ra-auth:expire-invites';
    protected $description = 'Expires team invites.';

    public function handle() {
        ClassName::Model('UserInvite')::where('expires_at', '<', date('Y-m-d H:i:s'))->delete();
    }
}
