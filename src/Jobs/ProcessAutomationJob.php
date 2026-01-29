<?php

namespace Juzaweb\Modules\EmailMarketing\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Juzaweb\Modules\EmailMarketing\Services\AutomationService;

class ProcessAutomationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(AutomationService $service): void
    {
        $service->processPendingLogs();
    }
}
