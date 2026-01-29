<?php

namespace Juzaweb\Modules\EmailMarketing\Facades;

use Illuminate\Support\Facades\Facade;
use Juzaweb\Modules\EmailMarketing\Contracts\AutomationTriggerRegistryInterface;

/**
 * @method static void register(string $key, array $config)
 * @method static array all()
 * @method static array|null get(string $key)
 * @method static bool has(string $key)
 * @method static array keys()
 * @method static array labels()
 * @method static void unregister(string $key)
 *
 * @see \Juzaweb\Modules\EmailMarketing\Support\AutomationTriggerRegistry
 */
class AutomationTrigger extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AutomationTriggerRegistryInterface::class;
    }
}
