<?php

namespace Juzaweb\Modules\EmailMarketing\Tests\Feature;

use Juzaweb\Modules\EmailMarketing\Tests\TestCase;
use Juzaweb\Modules\EmailMarketing\Models\EmailTemplate;
use Juzaweb\Modules\Core\Models\User;

class AutomationControllerTest extends TestCase
{
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'is_admin' => 1
        ]);
    }

    public function test_index_automation_rules()
    {
        $this->withoutMiddleware();
        $this->actingAs($this->admin);

        $response = $this->get('admin/email-marketing/automation');

        $response->assertStatus(200);
    }

    public function test_create_automation_rule()
    {
        $this->withoutMiddleware();
        $this->actingAs($this->admin);

        $response = $this->get('admin/email-marketing/automation/create');

        $response->assertStatus(200);
    }

    public function test_store_automation_rule()
    {
        $this->withoutMiddleware();
        $this->actingAs($this->admin);

        $template = EmailTemplate::create([
            'name' => 'Test Template',
            'subject' => 'Test Subject',
            'content' => 'Test Content',
        ]);

        $response = $this->post('admin/email-marketing/automation', [
            'name' => 'Test Automation',
            'template_id' => $template->id,
            'trigger_type' => 'user_registered',
            'delay_hours' => 1,
            'active' => 1,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('email_automation_rules', [
            'name' => 'Test Automation',
            'trigger_type' => 'user_registered',
        ]);
    }
}
