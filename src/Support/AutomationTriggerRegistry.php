<?php

namespace Juzaweb\Modules\EmailMarketing\Support;

use Juzaweb\Modules\EmailMarketing\Contracts\AutomationTriggerRegistryInterface;

class AutomationTriggerRegistry implements AutomationTriggerRegistryInterface
{
    protected array $triggers = [];

    /**
     * Register a new automation trigger type
     *
     * @param string $key Unique trigger key (e.g., 'user_registered')
     * @param array $config Configuration: ['label', 'description', 'event' (optional), 'delay_support' (optional)]
     * @return void
     */
    public function register(string $key, array $config): void
    {
        $this->triggers[$key] = array_merge([
            'key' => $key,
            'label' => $config['label'] ?? $key,
            'description' => $config['description'] ?? '',
            'event' => $config['event'] ?? null,
            'delay_support' => $config['delay_support'] ?? true,
        ], $config);
    }

    /**
     * Get all registered trigger types
     *
     * @return array
     */
    public function all(): array
    {
        return $this->triggers;
    }

    /**
     * Get a specific trigger by key
     *
     * @param string $key
     * @return array|null
     */
    public function get(string $key): ?array
    {
        return $this->triggers[$key] ?? null;
    }

    /**
     * Check if a trigger exists
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->triggers[$key]);
    }

    /**
     * Get all trigger keys
     *
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->triggers);
    }

    /**
     * Get trigger labels for dropdown
     *
     * @return array Key-value pairs [key => label]
     */
    public function labels(): array
    {
        return array_map(fn($trigger) => $trigger['label'], $this->triggers);
    }

    /**
     * Unregister a trigger
     *
     * @param string $key
     * @return void
     */
    public function unregister(string $key): void
    {
        unset($this->triggers[$key]);
    }
}
