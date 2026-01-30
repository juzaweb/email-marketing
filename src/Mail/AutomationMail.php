<?php

namespace Juzaweb\Modules\EmailMarketing\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\EmailMarketing\Models\EmailTemplate;

class AutomationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public EmailTemplate $template,
        public Model $user
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->replaceVariables($this->template->subject);

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        $content = $this->replaceVariables($this->template->content);

        return new Content(
            view: 'email-marketing::emails.simple',
            with: [
                'content' => $content,
            ],
        );
    }

    protected function replaceVariables(?string $content): string
    {
        $content = $content ?? '';
        // Simple variable replacement
        if (isset($this->user->name)) {
            $content = str_replace('{{name}}', $this->user->name, $content);
        }

        if (isset($this->user->email)) {
            $content = str_replace('{{email}}', $this->user->email, $content);
        }

        return $content;
    }
}
