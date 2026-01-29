<?php

namespace Juzaweb\Modules\EmailMarketing\Models;

use Juzaweb\Modules\Admin\Models\Model;
use Juzaweb\Modules\Admin\Traits\HasAPI;
use Juzaweb\Modules\Admin\Traits\Networkable;
use Juzaweb\Modules\EmailMarketing\Enums\SubscriberStatusEnum;

class Subscriber extends Model
{
    use HasAPI, Networkable;

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
