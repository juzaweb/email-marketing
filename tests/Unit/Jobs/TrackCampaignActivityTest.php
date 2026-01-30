<?php

namespace Juzaweb\Modules\EmailMarketing\Tests\Unit\Jobs;

use Juzaweb\Modules\EmailMarketing\Jobs\TrackCampaignActivity;
use Juzaweb\Modules\EmailMarketing\Models\Campaign;
use Juzaweb\Modules\EmailMarketing\Models\Subscriber;
use Juzaweb\Modules\EmailMarketing\Tests\TestCase;
use Illuminate\Support\Str;

class TrackCampaignActivityTest extends TestCase
{
    public function test_handle_creates_tracking_record()
    {
        // Arrange
        $campaign = Campaign::forceCreate([
            'id' => Str::uuid()->toString(),
            'name' => 'Test Campaign',
            'subject' => 'Test Subject',
            'content' => 'Test Content',
        ]);

        $subscriber = Subscriber::forceCreate([
            'id' => Str::uuid()->toString(),
            'email' => 'test@example.com',
            'name' => 'Test Subscriber',
        ]);

        $data = [
            'campaign_id' => $campaign->id,
            'subscriber_id' => $subscriber->id,
            'type' => 'clicked',
            'link_url' => 'https://example.com',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Test Agent',
        ];

        $job = new TrackCampaignActivity($data);

        // Act
        $job->handle();

        // Assert
        $this->assertDatabaseHas('email_campaign_trackings', [
            'campaign_id' => $campaign->id,
            'subscriber_id' => $subscriber->id,
            'type' => 'clicked',
            'link_url' => 'https://example.com',
        ]);
    }
}
