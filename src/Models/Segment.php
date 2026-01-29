<?php

namespace Juzaweb\Modules\EmailMarketing\Models;

use Juzaweb\Modules\Admin\Models\Model;
use Juzaweb\Modules\Admin\Traits\HasAPI;
use Juzaweb\Modules\Admin\Traits\Networkable;

class Segment extends Model
{
    use HasAPI, Networkable;

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
}
