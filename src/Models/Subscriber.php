<?php

namespace Juzaweb\Modules\EmailMarketing\Models;

use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\HasAPI;
use Juzaweb\Modules\EmailMarketing\Enums\SubscriberStatusEnum;

class Subscriber extends Model
{
    use HasAPI;

    protected $table = 'email_subscribers';

    protected $fillable = [
        'email',
        'name',
        'status',
    ];

    protected $casts = [
        'status' => SubscriberStatusEnum::class,
    ];
}
