<?php

namespace Juzaweb\Modules\EmailMarketing\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Juzaweb\Modules\EmailMarketing\Models\Campaign;
use Juzaweb\Modules\EmailMarketing\Models\Subscriber;
use Juzaweb\Modules\EmailMarketing\Services\MailContentProcessor;

class DynamicCampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Campaign $campaign,
        public Subscriber $subscriber
    ) {}

    /**
     * Get the message envelope (Subject, From).
     */
    public function envelope(): Envelope
    {
        // Xử lý placeholder cho tiêu đề (Ví dụ: Chào {{first_name}})
        $subject = str_replace('{{first_name}}', $this->subscriber->first_name, $this->campaign->subject);

        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Gọi Service để xử lý Tracking Pixel, Link Redirect và Unsubscribe Link
        $processor = app(MailContentProcessor::class);
        $processedContent = $processor->transform(
            $this->campaign->content,
            $this->campaign,
            $this->subscriber
        );

        return new Content(
            view: 'emails.campaign',
            with: [
                'content' => $processedContent,
            ],
        );
    }

    /**
     * Get the message headers (List-Unsubscribe).
     */
    public function headers(): Headers
    {
        // Tạo link hủy đăng ký có chữ ký (Signed URL)
        $unsubscribeUrl = URL::signedRoute('unsubscribe.confirm', [
            'subscriber' => $this->subscriber->id,
            'campaign_id' => $this->campaign->id
        ]);

        return new Headers(
            text: [
                'List-Unsubscribe' => "<{$unsubscribeUrl}>",
            ],
        );
    }
}
