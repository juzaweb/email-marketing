<?php

namespace Juzaweb\Modules\EmailMarketing\Models;

use Juzaweb\Modules\Admin\Models\Model;
use Juzaweb\Modules\Admin\Traits\HasAPI;
use Juzaweb\Modules\EmailMarketing\Enums\BatchStatusEnum;

class CampaignBatch extends Model
{
    use HasAPI;

    protected $table = 'email_campaign_batches';

    protected $fillable = [
        'campaign_id',
        'batch_id',
        'name',
        'status',
        'total_jobs',
        'pending_jobs',
        'failed_jobs',
        'progress',
        'started_at',
        'finished_at',
        'website_id',
    ];

    protected $casts = [
        'status' => BatchStatusEnum::class,
        'total_jobs' => 'integer',
        'pending_jobs' => 'integer',
        'failed_jobs' => 'integer',
        'progress' => 'integer',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
}
