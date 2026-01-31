<?php

namespace Juzaweb\Modules\EmailMarketing\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\HasAPI;

class Segment extends Model
{
    use HasAPI, HasUuids;

    protected $table = 'email_segments';

    protected $fillable = [
        'name',
        'description',
    ];

    public function campaigns()
    {
        return $this->belongsToMany(
            Campaign::class,
            'email_segment_campaign',
            'segment_id',
            'campaign_id'
        );
    }

    public function subscribers()
    {
        return $this->belongsToMany(
            Subscriber::class,
            'email_segment_subscriber',
            'segment_id',
            'subscriber_id'
        );
    }
}
