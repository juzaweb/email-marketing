<?php

namespace Juzaweb\Modules\EmailMarketing\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Juzaweb\Modules\Core\Http\Controllers\ThemeController;
use Juzaweb\Modules\EmailMarketing\Jobs\TrackCampaignActivity;
use Juzaweb\Modules\EmailMarketing\Models\CampaignTracking;

class TrackingController extends ThemeController
{
    public function trackOpen($campaignId, $subscriberId)
    {
        // 1. Ghi nhận vào DB (chỉ ghi nhận nếu chưa tồn tại hoặc ghi nhận mọi lần tùy nhu cầu)
        TrackCampaignActivity::dispatch([
            'campaign_id' => $campaignId,
            'subscriber_id' => $subscriberId,
            'type' => 'opened',
            'ip_address' => client_ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // 2. Trả về ảnh 1x1 trong suốt
        $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        return response($pixel, 200)->header('Content-Type', 'image/gif');
    }

    public function trackClick(Request $request): RedirectResponse
    {
        $targetUrl = decrypt($request->query('url')); // Giải mã URL đích
        $campaignId = $request->query('cid');
        $subscriberId = $request->query('sid');

        // 1. Ghi nhận lượt click
        TrackCampaignActivity::dispatch([
            'campaign_id' => $campaignId,
            'subscriber_id' => $subscriberId,
            'type' => 'clicked',
            'link_url' => $targetUrl,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // 2. Redirect đến trang đích
        return redirect()->away($targetUrl);
    }
}
