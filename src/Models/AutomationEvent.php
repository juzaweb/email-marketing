<?php

namespace Juzaweb\Modules\EmailMarketing\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\HasAPI;
use Juzaweb\Modules\Core\Traits\Networkable;

class AutomationEvent extends Model
{
    use HasAPI, Networkable;

    protected $table = 'email_automation_events';

    protected $fillable = [
        'trigger_code',
        'object_id',
        'object_type',
        'occurred_at',
        'payload',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'payload' => 'array',
    ];

    public function object(): MorphTo
    {
        return $this->morphTo('object');
    }

    public function scopeByTrigger($query, string $triggerCode)
    {
        return $query->where('trigger_code', $triggerCode);
    }

    public function scopeByObject($query, $objectId, string $objectType)
    {
        return $query->where('object_id', $objectId)
            ->where('object_type', $objectType);
    }

    public function scopeOccurredAfter($query, $datetime)
    {
        return $query->where('occurred_at', '>', $datetime);
    }

    public function scopeOccurredBefore($query, $datetime)
    {
        return $query->where('occurred_at', '<', $datetime);
    }
}
