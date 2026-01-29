<?php

namespace Juzaweb\Modules\EmailMarketing\Enums;

enum BatchStatusEnum: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
}
