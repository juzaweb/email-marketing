<?php

namespace Juzaweb\Modules\EmailMarketing\Http\Controllers;

use Illuminate\Http\Request;
use Juzaweb\Modules\EmailMarketing\Models\Subscriber;

class UnsubscribeController
{
    public function confirm(Subscriber $subscriber)
    {
        return view('email.unsubscribe_confirm', compact('subscriber'));
    }

    // Xử lý hủy đăng ký
    public function process(Request $request, Subscriber $subscriber)
    {
        // 1. Cập nhật trạng thái subscriber
        $subscriber->update(['status' => 'unsubscribed']);

        // 2. Ghi nhận vào tracking để báo cáo
        CampaignTracking::create([
            'campaign_id' => $request->campaign_id,
            'subscriber_id' => $subscriber->id,
            'type' => 'unsubscribed',
            'ip_address' => $request->ip()
        ]);

        return response()->json(['message' => 'Bạn đã hủy đăng ký thành công.']);
    }
}
