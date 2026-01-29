<?php

namespace Juzaweb\Modules\EmailMarketing\Services;

use Illuminate\Support\Facades\URL;

class MailContentProcessor
{
    public function transform(string $content, $campaign, $subscriber): string
    {
        // 1. Xử lý các link (<a> tags)
        $content = preg_replace_callback('/<a\s+(?:[^>]*?\s+)?href=(["\'])(.*?)\1/', function ($matches) use ($campaign, $subscriber) {
            $originalUrl = $matches[2];

            // Bỏ qua các link mailto: hoặc tel:
            if (str_starts_with($originalUrl, 'mailto:') || str_starts_with($originalUrl, 'tel:')) {
                return $matches[0];
            }

            $trackingUrl = route('track.click', [
                'url' => encrypt($originalUrl),
                'cid' => $campaign->id,
                'sid' => $subscriber->id
            ]);

            return str_replace($originalUrl, $trackingUrl, $matches[0]);
        }, $content);

        // 2. Chèn Tracking Pixel vào cuối thẻ <body> hoặc cuối nội dung
        $pixelUrl = route('track.open', [$campaign->id, $subscriber->id]);
        $pixelHtml = "<img src='{$pixelUrl}' width='1' height='1' style='display:none !important;' alt='' />";

        $unsubscribeUrl = URL::signedRoute('unsubscribe.confirm', [
            'subscriber' => $subscriber->id,
            'campaign_id' => $campaign->id // Để biết họ hủy từ chiến dịch nào
        ]);

        $footerHtml = "
            <hr style='border:none;border-top:1px solid #eee;margin:20px 0;'>
            <footer style='font-size:12px;color:#888;text-align:center;'>
                <a href='{$unsubscribeUrl}' style='color:#888;text-decoration:underline;'>". __('Unsubscribe') ."</a>
            </footer>
        ";

        return $content . $pixelHtml . $footerHtml;
    }
}
