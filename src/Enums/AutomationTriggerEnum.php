<?php

namespace Juzaweb\Modules\EmailMarketing\Enums;

enum AutomationTriggerEnum: string
{
    case USER_REGISTERED = 'user_registered';
    case USER_REGISTERED_7_DAYS = 'user_registered_7_days';
    case USER_BIRTHDAY = 'user_birthday';
    case MEMBER_REGISTERED = 'member_registered';
    case MEMBER_REGISTERED_7_DAYS = 'member_registered_7_days';
    case MEMBER_BIRTHDAY = 'member_birthday';
}
