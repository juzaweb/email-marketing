<?php

namespace Juzaweb\Modules\EmailMarketing\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\HasAPI;
use Juzaweb\Modules\Core\Traits\Networkable;

class AutomationRule extends Model
{
    use HasAPI, Networkable;

    protected $table = 'email_automation_rules';

    protected $fillable = [
        'template_id',
        'name',
        'description',
        'trigger_type',
        'active',
        'conditions',
        'delay_hours',
    ];

    protected $casts = [
        'active' => 'boolean',
        'conditions' => 'array',
        'delay_hours' => 'integer',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'template_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(AutomationLog::class, 'automation_rule_id');
    }

    public function pendingLogs(): HasMany
    {
        return $this->hasMany(AutomationLog::class, 'automation_rule_id')
            ->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByTriggerType($query, string $triggerType)
    {
        return $query->where('trigger_type', $triggerType);
    }
}
