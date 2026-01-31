<?php

namespace Juzaweb\Modules\EmailMarketing\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\HasAPI;
use Juzaweb\Modules\EmailMarketing\Enums\SubscriberStatusEnum;

class Subscriber extends Model
{
    use HasAPI, HasUuids;

    protected $table = 'email_subscribers';

    protected $fillable = [
        'email',
        'name',
        'status',
    ];

    protected $casts = [
        'status' => SubscriberStatusEnum::class,
    ];

    public function segments()
    {
        return $this->belongsToMany(
            Segment::class,
            'email_segment_subscriber',
            'subscriber_id',
            'segment_id'
        );
    }

    public function getFirstNameAttribute()
    {
        return explode(' ', $this->name)[0] ?? '';
    }
}
