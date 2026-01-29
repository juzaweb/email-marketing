<?php

namespace Juzaweb\Modules\EmailMarketing\Mail;

use Illuminate\Mail\Mailable;

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
            from: new Address($this->campaign->emailServer->from_address, $this->campaign->emailServer->from_name),
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
