<?php

namespace Juzaweb\Modules\EmailMarketing\Services;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Juzaweb\Modules\Admin\Services\BaseService;
use Juzaweb\Modules\EmailMarketing\Jobs\SendCampaignJob;
use Juzaweb\Modules\EmailMarketing\Models\Campaign;
use Juzaweb\Modules\EmailMarketing\Models\CampaignBatch;
use Throwable;

class CampaignService extends BaseService
{
    /**
     * Dispatch a campaign sending process
     * Creates multiple batches based on batch_size config
     */
    public function execute(Campaign $campaign)
    {
        // 1. Count total subscribers first
        $totalSubscribers = $campaign->segment->subscribers()
            ->where('status', 'subscribed')
            ->count();

        if ($totalSubscribers === 0) {
            return $this->result(false, null, 'No subscribers found for this campaign.');
        }

        // 2. Lấy batch size từ config
        $batchSize = config('email_marketing.batch_size', 100);

        // 3. Calculate total chunks
        $totalChunks = (int) ceil($totalSubscribers / $batchSize);

        // 4. Update campaign status before processing
        $campaign->update(['status' => 'sending']);

        $chunkIndex = 0;

        // 5. Chunk by ID - mỗi chunk có transaction riêng
        $campaign->segment->subscribers()
            ->where('status', 'subscribed')
            ->chunkById($batchSize, function ($subscribers) use ($campaign, &$chunkIndex, $totalChunks) {
                $chunkIndex++;
                $batchNumber = $chunkIndex;

                // Mỗi chunk có transaction riêng
                $this->transaction(function () use ($campaign, $subscribers, $batchNumber, $totalChunks) {
                    // Tạo jobs cho chunk này
                    $jobs = $subscribers->map(function ($subscriber) use ($campaign) {
                        return new SendCampaignJob($campaign, $subscriber);
                    });

                    // Dispatch batch cho chunk này
                    $batch = Bus::batch($jobs)
                        ->then(function ($batch) {
                            // Chạy khi batch này đã gửi thành công
                            CampaignBatch::where('batch_id', $batch->id)->update([
                                'status' => 'completed',
                                'progress' => 100,
                                'finished_at' => now(),
                            ]);
                        })
                        ->catch(function ($batch, Throwable $e) {
                            // Chạy khi có lỗi xảy ra trong batch
                            Log::error("Batch {$batch->id} failed: " . $e->getMessage());
                            CampaignBatch::where('batch_id', $batch->id)->update([
                                'status' => 'failed',
                                'finished_at' => now(),
                            ]);
                        })
                        ->finally(function ($batch) use ($campaign) {
                            // Luôn chạy sau khi kết thúc batch (dù thành công hay lỗi)
                            Log::info("Campaign {$campaign->id} batch {$batch->id} completed.");

                            // Check if ALL batches are completed or failed
                            $pendingBatcheExists = $campaign->batches()
                                ->whereNotIn('status', ['completed', 'failed'])
                                ->exists();

                            if (! $pendingBatcheExists) {
                                // All batches done, mark campaign as sent
                                $campaign->update(['status' => 'sent', 'sent_at' => now()]);
                                Log::info("Campaign {$campaign->id} fully completed - all batches processed.");
                            }
                        })
                        ->name("Campaign: {$campaign->name} - Batch #{$batchNumber}/{$totalChunks}")
                        ->dispatch();

                    // Tạo record tracking cho batch này
                    CampaignBatch::create([
                        'campaign_id' => $campaign->id,
                        'batch_id' => $batch->id,
                        'name' => "Campaign: {$campaign->name} - Batch #{$batchNumber}/{$totalChunks}",
                        'status' => 'processing',
                        'total_jobs' => $subscribers->count(),
                        'pending_jobs' => $subscribers->count(),
                        'failed_jobs' => 0,
                        'progress' => 0,
                        'started_at' => now(),
                        'website_id' => $campaign->website_id,
                    ]);
                });
            });

        Log::info("Campaign {$campaign->id} started with {$totalChunks} batches ({$totalSubscribers} total subscribers).");

        return $this->result(true, ['chunks' => $totalChunks, 'subscribers' => $totalSubscribers], "Campaign execution started with {$totalChunks} batches.");
    }
}
