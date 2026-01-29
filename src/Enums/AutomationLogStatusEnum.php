<?php

namespace Juzaweb\Modules\EmailMarketing\Enums;

enum AutomationLogStatusEnum: string
{
    case PENDING = 'pending';
    case SENT = 'sent';
    case FAILED = 'failed';
}
