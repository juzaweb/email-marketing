<?php

namespace Juzaweb\Modules\EmailMarketing\Tests\Feature;

use Illuminate\Support\Facades\Queue;
use Juzaweb\Modules\EmailMarketing\Jobs\TrackCampaignActivity;
use Juzaweb\Modules\EmailMarketing\Models\Campaign;
use Juzaweb\Modules\EmailMarketing\Models\Subscriber;
use Juzaweb\Modules\EmailMarketing\Tests\TestCase;
use Illuminate\Support\Str;

class TrackingControllerTest extends TestCase
{
    public function test_track_click_dispatches_job()
    {
        Queue::fake();

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

        $targetUrl = 'https://example.com';
        $encryptedUrl = encrypt($targetUrl);

        // Act
        $response = $this->get(route('track.click', [
            'cid' => $campaign->id,
            'sid' => $subscriber->id,
            'url' => $encryptedUrl,
        ]));

        // Assert
        $response->assertRedirect($targetUrl);

        Queue::assertPushed(TrackCampaignActivity::class, function ($job) use ($campaign, $subscriber, $targetUrl) {
            $reflection = new \ReflectionClass($job);
            $property = $reflection->getProperty('data');
            $property->setAccessible(true);
            $jobData = $property->getValue($job);

            return (string) $jobData['campaign_id'] === (string) $campaign->id &&
                   (string) $jobData['subscriber_id'] === (string) $subscriber->id &&
                   $jobData['type'] === 'clicked' &&
                   $jobData['link_url'] === $targetUrl;
        });
    }
}
