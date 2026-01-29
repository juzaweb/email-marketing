<?php

namespace Juzaweb\Modules\EmailMarketing\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Juzaweb\Modules\EmailMarketing\Mail\DynamicCampaignMail;
use Juzaweb\Modules\EmailMarketing\Models\Campaign;
use Juzaweb\Modules\EmailMarketing\Models\CampaignTracking;
use Juzaweb\Modules\EmailMarketing\Models\Subscriber;

class SendCampaignJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Campaign $campaign,
        protected Subscriber $subscriber
    ) {}

    public function handle(): void
    {
        // Kiểm tra nếu batch đã bị hủy (người dùng nhấn Stop)
        if ($this->batch()->cancelled()) {
            return;
        }

        try {
            // Gửi mail thực tế
            Mail::to($this->subscriber->email)
                ->send(new DynamicCampaignMail($this->campaign, $this->subscriber));

            // Lưu tracking 'sent'
            CampaignTracking::create([
                'campaign_id' => $this->campaign->id,
                'subscriber_id' => $this->subscriber->id,
                'type' => 'sent',
            ]);
        } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
            // Email chắc chắn KHÔNG gửi được do lỗi kết nối SMTP/API
            Log::error($e->getMessage());
        } catch (\Exception $e) {
            // Log lỗi hoặc xử lý bounce mail tại đây
            report($e);
        }
    }
}
