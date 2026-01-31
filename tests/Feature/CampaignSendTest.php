<?php

namespace Juzaweb\Modules\EmailMarketing\Tests\Feature;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Juzaweb\Modules\Core\Models\User;
use Juzaweb\Modules\EmailMarketing\Enums\CampaignStatusEnum;
use Juzaweb\Modules\EmailMarketing\Enums\SubscriberStatusEnum;
use Juzaweb\Modules\EmailMarketing\Models\Campaign;
use Juzaweb\Modules\EmailMarketing\Models\Segment;
use Juzaweb\Modules\EmailMarketing\Models\Subscriber;
use Juzaweb\Modules\EmailMarketing\Tests\TestCase;

use Illuminate\Database\Schema\Blueprint;

class CampaignSendTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_admin')->default(0);
            });
        }
    }

    public function test_send_campaign_successfully()
    {
        $this->withoutMiddleware();
        Bus::fake();

        // Create a user and authenticate
        $user = User::factory()->create(['is_admin' => 1]);
        $this->actingAs($user);

        // Create Segment
        $segment = Segment::create(['name' => 'Test Segment']);

        // Create Subscribers
        $subscriber1 = Subscriber::create([
            'email' => 'user1@example.com',
            'name' => 'User One',
            'status' => SubscriberStatusEnum::SUBSCRIBED,
        ]);

        $subscriber2 = Subscriber::create([
            'email' => 'user2@example.com',
            'name' => 'User Two',
            'status' => SubscriberStatusEnum::SUBSCRIBED,
        ]);

        // Attach subscribers to segment
        $segment->subscribers()->attach([$subscriber1->id, $subscriber2->id]);

        // Create Campaign
        $campaign = Campaign::create([
            'name' => 'Test Campaign',
            'subject' => 'Hello',
            'content' => 'Content',
            'status' => CampaignStatusEnum::DRAFT,
        ]);

        // Attach segment to campaign
        $campaign->segments()->attach($segment->id);

        // Act
        // Use the route name we added
        $response = $this->getJson(route('email-marketing.campaigns.send', ['id' => $campaign->id]));

        // Assert
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Campaign sending started successfully.']);

        $campaign->refresh();
        $this->assertEquals(CampaignStatusEnum::SENDING, $campaign->status);

        Bus::assertBatched(function ($batch) use ($campaign) {
             return $batch->name == "Campaign: {$campaign->name} - Batch #1/1" &&
                    $batch->jobs->count() == 2;
        });
    }
}
