<?php

return [
    /**
     * Number of jobs per batch when sending campaigns
     * Smaller batches = better progress tracking, easier retry
     * Larger batches = fewer batches to manage
     */
    'batch_size' => env('EMAIL_MARKETING_BATCH_SIZE', 100),

    /*
    |--------------------------------------------------------------------------
    | Email Automation Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for email automation triggers and rules
    |
    */
    'default_delay_minutes' => 0,
    'max_retry_attempts' => 3,
];
