<?php

namespace Juzaweb\Modules\EmailMarketing\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Juzaweb\Modules\EmailMarketing\Enums\AutomationLogStatusEnum;
use Juzaweb\Modules\EmailMarketing\Mail\AutomationMail;
use Juzaweb\Modules\EmailMarketing\Models\AutomationLog;
use Juzaweb\Modules\EmailMarketing\Models\AutomationRule;

class AutomationService
{
    public function trigger(string $key, Model $user, array $params = []): void
    {
        $rules = AutomationRule::where('trigger_type', $key)
            ->where('active', true)
            ->get();

        foreach ($rules as $rule) {
            $scheduledAt = $rule->delay_hours > 0
                ? now()->addHours($rule->delay_hours)
                : now();

            AutomationLog::create([
                'automation_rule_id' => $rule->id,
                'user_id' => $user->id,
                'user_type' => get_class($user),
                'status' => AutomationLogStatusEnum::PENDING,
                'scheduled_at' => $scheduledAt,
            ]);
        }
    }

    public function processPendingLogs(): void
    {
        $logs = AutomationLog::with(['automationRule.template'])
            ->readyToSend()
            ->limit(50)
            ->get();

        foreach ($logs as $log) {
            try {
                if (!class_exists($log->user_type)) {
                    $log->markAsFailed("Class {$log->user_type} not found");
                    continue;
                }

                $user = $log->user_type::find($log->user_id);
                if (!$user) {
                    $log->markAsFailed('User not found');
                    continue;
                }

                $email = $user->email ?? null;
                if (!$email) {
                    $log->markAsFailed('User has no email');
                    continue;
                }

                Mail::to($email)->send(new AutomationMail($log->automationRule->template, $user));

                $log->markAsSent();
            } catch (\Exception $e) {
                $log->markAsFailed($e->getMessage());
            }
        }
    }
}
