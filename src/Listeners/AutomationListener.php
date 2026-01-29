<?php

namespace Juzaweb\Modules\EmailMarketing\Listeners;

use Illuminate\Auth\Events\Registered;
use Juzaweb\Modules\EmailMarketing\Services\AutomationService;

class AutomationListener
{
    public function __construct(protected AutomationService $automationService)
    {
    }

    public function handle($event): void
    {
        if ($event instanceof Registered) {
            $this->automationService->trigger('user_registered', $event->user);
            return;
        }

        // Handle generic or unknown event classes by checking properties or class name
        $className = get_class($event);

        // Juzaweb RegisterSuccessful event usually has public $user property
        if (str_ends_with($className, 'RegisterSuccessful') || str_ends_with($className, 'UserRegistered')) {
             if (isset($event->user)) {
                 $this->automationService->trigger('user_registered', $event->user);
             }
        }
    }
}
