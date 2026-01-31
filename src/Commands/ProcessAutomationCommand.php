<?php

namespace Juzaweb\Modules\EmailMarketing\Commands;

use Illuminate\Console\Command;
use Juzaweb\Modules\EmailMarketing\Services\AutomationService;

class ProcessAutomationCommand extends Command
{
    protected $signature = 'email-marketing:process-automation';
    protected $description = 'Process pending automation logs and send emails';

    public function handle(AutomationService $service): void
    {
        $this->info('Starting automation processing...');

        $service->processPendingLogs();

        $this->info('Automation processing completed.');
    }
}
