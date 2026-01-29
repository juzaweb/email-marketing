<?php

namespace Juzaweb\Modules\EmailMarketing\Commands;

use Illuminate\Console\Command;
use Juzaweb\Modules\EmailMarketing\Services\AutomationService;
use Juzaweb\Models\User;

class CheckBirthdayAutomationCommand extends Command
{
    protected $signature = 'email-marketing:check-birthday';
    protected $description = 'Check for user birthdays and trigger automation';

    public function handle(AutomationService $automationService): void
    {
        if (!class_exists(User::class)) {
            $this->error('User model not found');
            return;
        }

        $users = User::whereMonth('birthday', now()->month)
            ->whereDay('birthday', now()->day)
            ->get();

        foreach ($users as $user) {
            $automationService->trigger('user_birthday', $user);
        }

        $this->info("Triggered birthday automation for {$users->count()} users.");
    }
}
