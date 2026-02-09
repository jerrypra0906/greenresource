<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Backup Path
    |--------------------------------------------------------------------------
    | Directory where backup files are stored (relative to storage_path() or absolute).
    */

    'path' => env('BACKUP_PATH', storage_path('app/backups')),

    /*
    |--------------------------------------------------------------------------
    | Retention Days
    |--------------------------------------------------------------------------
    | Number of days to keep backups. Older backups are deleted when running backup:run.
    */

    'retention_days' => (int) env('BACKUP_RETENTION_DAYS', 14),

    /*
    |--------------------------------------------------------------------------
    | Enable Database Backup
    |--------------------------------------------------------------------------
    | Set to false to skip database dump (e.g. when pg_dump is not available).
    */

    'database_enabled' => env('BACKUP_DATABASE_ENABLED', true),

];
