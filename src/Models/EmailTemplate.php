<?php

namespace Juzaweb\Modules\EmailMarketing\Models;

use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\HasAPI;
use Juzaweb\Modules\EmailMarketing\Support\Traits\HasUuid;

class EmailTemplate extends Model
{
    use HasAPI, HasUuid;

    protected $table = 'email_marketing_templates';

    protected $fillable = [
        'name',
        'content',
    ];
}
