<?php

namespace Juzaweb\Modules\EmailMarketing\Enums;

enum SubscriberStatusEnum: string
{
    case SUBSCRIBED = 'subscribed';
    case UNSUBSCRIBED = 'unsubscribed';
    case BOUNCED = 'bounced';
}
