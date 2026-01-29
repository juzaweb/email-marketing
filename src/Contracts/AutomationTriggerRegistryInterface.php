<?php

namespace Juzaweb\Modules\EmailMarketing\Contracts;

interface AutomationTriggerRegistryInterface
{
    /**
     * Register a new automation trigger type
     *
     * @param string $key Unique trigger key (e.g., 'user_registered')
     * @param array $config Configuration: ['label', 'description', 'event' (optional), 'delay_support' (optional)]
     * @return void
     */
    public function register(string $key, array $config): void;

    /**
     * Get all registered trigger types
     *
     * @return array
     */
    public function all(): array;

    /**
     * Get a specific trigger by key
     *
     * @param string $key
     * @return array|null
     */
    public function get(string $key): ?array;

    /**
     * Check if a trigger exists
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Get all trigger keys
     *
     * @return array
     */
    public function keys(): array;

    /**
     * Get trigger labels for dropdown
     *
     * @return array Key-value pairs [key => label]
     */
    public function labels(): array;

    /**
     * Unregister a trigger
     *
     * @param string $key
     * @return void
     */
    public function unregister(string $key): void;
}
