<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Backup Configuration
    |--------------------------------------------------------------------------
    |
    | Configure automated database backups for disaster recovery.
    |
    */

    'enabled' => env('BACKUP_ENABLED', env('APP_ENV') === 'production'),

    'retention_days' => env('BACKUP_RETENTION_DAYS', 7),

    'path' => storage_path('app/backups'),

];
