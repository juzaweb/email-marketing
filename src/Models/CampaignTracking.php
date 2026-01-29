<?php

namespace Juzaweb\Modules\EmailMarketing\Models;

use Juzaweb\Modules\Admin\Models\Model;
use Juzaweb\Modules\Admin\Traits\HasAPI;

class CampaignTracking extends Model
{
    use HasAPI;

    public const UPDATED_AT = false;

    protected $table = 'email_campaign_trackings';

    protected $fillable = [
        'campaign_id',
        'subscriber_id',
        'link_url',
        'type',
        'user_agent',
    ];
}
