<?php

namespace Juzaweb\Modules\EmailMarketing\Models;

use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\HasAPI;

class EmailTemplate extends Model
{
    use HasAPI;

    protected $table = 'email_marketing_templates';

    protected $fillable = [
        'name',
        'content',
    ];
}
