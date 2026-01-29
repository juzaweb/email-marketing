<?php

namespace Juzaweb\Modules\EmailMarketing\Enums;

enum CampaignStatusEnum: string
{
    case DRAFT = 'draft';
    case SCHEDULED = 'scheduled';
    case SENDING = 'sending';
    case SENT = 'sent';
    case PAUSED = 'paused';
}
