<?php

namespace Juzaweb\Modules\EmailMarketing\Tests\Unit;

use Juzaweb\Modules\EmailMarketing\Tests\TestCase;
use Juzaweb\Modules\EmailMarketing\Services\AutomationService;
use Juzaweb\Modules\EmailMarketing\Models\AutomationRule;
use Juzaweb\Modules\EmailMarketing\Models\EmailTemplate;
use Juzaweb\Modules\EmailMarketing\Models\AutomationLog;
use Juzaweb\Modules\EmailMarketing\Enums\AutomationLogStatusEnum;
use Juzaweb\Modules\Core\Models\User;
use Illuminate\Support\Facades\Mail;
use Juzaweb\Modules\EmailMarketing\Mail\AutomationMail;

class AutomationServiceTest extends TestCase
{
    public function test_trigger_creates_logs()
    {
        $template = EmailTemplate::create([
            'name' => 'Test Template',
            'subject' => 'Subject',
            'content' => 'Content',
        ]);

        $rule = AutomationRule::create([
            'name' => 'Test Rule',
            'template_id' => $template->id,
            'trigger_type' => 'user_registered',
            'active' => true,
            'delay_hours' => 0,
        ]);

        $user = User::factory()->create();

        $service = new AutomationService();
        $service->trigger('user_registered', $user);

        $this->assertDatabaseHas('email_automation_logs', [
            'automation_rule_id' => $rule->id,
            'user_id' => $user->id,
            'status' => AutomationLogStatusEnum::PENDING,
        ]);
    }

    public function test_process_pending_logs_sends_email()
    {
        Mail::fake();

        $template = EmailTemplate::create([
            'name' => 'Test Template',
            'subject' => 'Subject',
            'content' => 'Content',
        ]);

        $rule = AutomationRule::create([
            'name' => 'Test Rule',
            'template_id' => $template->id,
            'trigger_type' => 'user_registered',
            'active' => true,
            'delay_hours' => 0,
        ]);

        $user = User::factory()->create();

        $log = AutomationLog::create([
            'automation_rule_id' => $rule->id,
            'user_id' => $user->id,
            'user_type' => get_class($user),
            'status' => AutomationLogStatusEnum::PENDING,
            'scheduled_at' => now()->subMinute(),
        ]);

        $service = new AutomationService();
        $service->processPendingLogs();

        Mail::assertSent(AutomationMail::class, function ($mail) use ($user, $template) {
            return $mail->hasTo($user->email) &&
                   $mail->template->id === $template->id;
        });

        $this->assertEquals(AutomationLogStatusEnum::SENT, $log->fresh()->status);
    }
}
