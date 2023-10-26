<?php
namespace Lumi\Auth\Domains\Team\Commands;

use Illuminate\Console\Command;
use Lumi\Auth\Services\ClassName;

class ExpireInvitesCommand extends Command
{
    protected $signature = 'ra-auth:expire-invites';
    protected $description = 'Expires team invites.';

    public function handle() {
        ClassName::Model('TeamInvite')::where('expires_at', '<', date('Y-m-d H:i:s'))->delete();
    }
}
