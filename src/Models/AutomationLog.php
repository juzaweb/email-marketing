<?php

namespace Juzaweb\Modules\EmailMarketing\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\HasAPI;
use Juzaweb\Modules\EmailMarketing\Enums\AutomationLogStatusEnum;

class AutomationLog extends Model
{
    use HasAPI;

    protected $table = 'email_automation_logs';

    protected $fillable = [
        'automation_rule_id',
        'user_id',
        'user_type',
        'status',
        'error_message',
        'scheduled_at',
        'sent_at',
    ];

    protected $casts = [
        'status' => AutomationLogStatusEnum::class,
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function automationRule(): BelongsTo
    {
        return $this->belongsTo(AutomationRule::class, 'automation_rule_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', AutomationLogStatusEnum::PENDING);
    }

    public function scopeSent($query)
    {
        return $query->where('status', AutomationLogStatusEnum::SENT);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', AutomationLogStatusEnum::FAILED);
    }

    public function scopeScheduledBefore($query, $datetime)
    {
        return $query->where('scheduled_at', '<=', $datetime);
    }

    public function scopeReadyToSend($query)
    {
        return $query->pending()
            ->scheduledBefore(now())
            ->whereNotNull('scheduled_at');
    }

    public function markAsSent(): bool
    {
        return $this->update([
            'status' => AutomationLogStatusEnum::SENT,
            'sent_at' => now(),
        ]);
    }

    public function markAsFailed(string $errorMessage): bool
    {
        return $this->update([
            'status' => AutomationLogStatusEnum::FAILED,
            'error_message' => $errorMessage,
        ]);
    }
}
