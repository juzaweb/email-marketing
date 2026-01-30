<?php

namespace Juzaweb\Modules\EmailMarketing\Models;

use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\HasAPI;

class CampaignTracking extends Model
{
    use HasAPI;

    public const UPDATED_AT = null;

    protected $table = 'email_campaign_trackings';

    protected $fillable = [
        'campaign_id',
        'subscriber_id',
        'link_url',
        'type',
        'user_agent',
    ];
}
