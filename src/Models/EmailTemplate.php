<?php

namespace Juzaweb\Modules\EmailMarketing\Models;

use Juzaweb\Modules\Admin\Models\Model;
use Juzaweb\Modules\Admin\Traits\HasAPI;
use Juzaweb\Modules\Admin\Traits\Networkable;

class EmailTemplate extends Model
{
    use HasAPI, Networkable;

    protected $table = 'email_marketing_templates';

    protected $fillable = [
        'name',
        'content',
    ];
}
