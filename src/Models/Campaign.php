<?php

namespace Juzaweb\Modules\EmailMarketing\Models;

use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\HasAPI;
use Juzaweb\Modules\Core\Traits\Networkable;
use Juzaweb\Modules\EmailMarketing\Enums\CampaignStatusEnum;
use Juzaweb\Modules\EmailMarketing\Enums\CampaignSendTypeEnum;

class Campaign extends Model
{
    use HasAPI, Networkable;

    protected $table = 'email_campaigns';

    protected $fillable = [
        'template_id',
        'name',
        'subject',
        'content',
        'status',
        'send_type',
        'automation_trigger_type',
        'automation_conditions',
        'automation_delay_hours',
        'scheduled_at',
        'sent_at',
        'views',
        'clicks',
    ];

    protected $casts = [
        'status' => CampaignStatusEnum::class,
        'send_type' => CampaignSendTypeEnum::class,
        'automation_conditions' => 'array',
        'automation_delay_hours' => 'integer',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'views' => 'integer',
        'clicks' => 'integer',
    ];

    public function template()
    {
        return $this->belongsTo(EmailTemplate::class, 'template_id');
    }

    public function segments()
    {
        return $this->belongsToMany(
            Segment::class,
            'email_segment_campaign',
            'campaign_id',
            'segment_id'
        );
    }

    public function batches()
    {
        return $this->hasMany(CampaignBatch::class, 'campaign_id');
    }

    public function latestBatch()
    {
        return $this->hasOne(CampaignBatch::class, 'campaign_id')->latestOfMany();
    }

    public function scopeManual($query)
    {
        return $query->where('send_type', CampaignSendTypeEnum::MANUAL);
    }

    public function scopeAuto($query)
    {
        return $query->where('send_type', CampaignSendTypeEnum::AUTO);
    }

    public function scopeByTriggerType($query, string $triggerType)
    {
        return $query->auto()->where('automation_trigger_type', $triggerType);
    }

    public function isManual(): bool
    {
        return $this->send_type === CampaignSendTypeEnum::MANUAL;
    }

    public function isAuto(): bool
    {
        return $this->send_type === CampaignSendTypeEnum::AUTO;
    }
}
