<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class BackupRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:run
                            {--no-db : Skip database dump}
                            {--no-files : Skip storage/files backup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run automated backup (database + storage)';

    public function handle(): int
    {
        $backupPath = Config::get('backup.path', storage_path('app/backups'));
        $retentionDays = Config::get('backup.retention_days', 14);
        $doDb = ! $this->option('no-db') && Config::get('backup.database_enabled', true);
        $doFiles = ! $this->option('no-files');

        if (! is_dir($backupPath)) {
            if (! @mkdir($backupPath, 0755, true)) {
                $this->error("Cannot create backup directory: {$backupPath}");

                return self::FAILURE;
            }
        }

        $date = now()->format('Y-m-d_H-i-s');
        $dateDir = $backupPath.'/'.$date;
        if (! @mkdir($dateDir, 0755, true)) {
            $this->error("Cannot create backup date directory: {$dateDir}");

            return self::FAILURE;
        }

        $ok = true;

        if ($doDb && config('database.default') === 'pgsql') {
            $ok = $this->backupDatabase($dateDir) && $ok;
        } elseif ($doDb) {
            $this->warn('Database backup only supported for PostgreSQL.');
        }

        if ($doFiles) {
            $ok = $this->backupStorage($dateDir) && $ok;
        }

        $this->pruneOldBackups($backupPath, $retentionDays);

        return $ok ? self::SUCCESS : self::FAILURE;
    }

    private function backupDatabase(string $dateDir): bool
    {
        $host = config('database.connections.pgsql.host');
        $port = config('database.connections.pgsql.port');
        $database = config('database.connections.pgsql.database');
        $username = config('database.connections.pgsql.username');
        $password = config('database.connections.pgsql.password');

        $file = $dateDir.'/database.sql';

        $cmd = sprintf(
            'pg_dump -h %s -p %s -U %s -F p --no-owner --no-acl %s > %s',
            escapeshellarg($host),
            escapeshellarg((string) $port),
            escapeshellarg($username),
            escapeshellarg($database),
            escapeshellarg($file)
        );

        $this->info('Backing up database...');
        $pwd = getenv('PGPASSWORD');
        putenv('PGPASSWORD='.$password);
        exec($cmd, $out, $code);
        putenv('PGPASSWORD='.(string) $pwd);

        if ($code !== 0 || ! is_file($file)) {
            $this->error('Database backup failed.');

            return false;
        }
        $this->info('Database backup saved to '.$file);

        return true;
    }

    private function backupStorage(string $dateDir): bool
    {
        $storagePath = storage_path();
        $archive = $dateDir.'/storage.tar.gz';

        $this->info('Backing up storage...');
        $cmd = sprintf(
            'tar czf %s -C %s %s 2>/dev/null',
            escapeshellarg($archive),
            escapeshellarg(dirname($storagePath)),
            escapeshellarg(basename($storagePath))
        );
        exec($cmd, $out, $code);

        if ($code !== 0 || ! is_file($archive)) {
            $this->error('Storage backup failed.');

            return false;
        }
        $this->info('Storage backup saved to '.$archive);

        return true;
    }

    private function pruneOldBackups(string $backupPath, int $retentionDays): void
    {
        if ($retentionDays <= 0) {
            return;
        }
        $cutoff = now()->subDays($retentionDays);
        $dirs = @scandir($backupPath);
        if ($dirs === false) {
            return;
        }
        foreach ($dirs as $name) {
            if ($name === '.' || $name === '..') {
                continue;
            }
            $path = $backupPath.DIRECTORY_SEPARATOR.$name;
            if (! is_dir($path)) {
                continue;
            }
            $mtime = @filemtime($path);
            if ($mtime !== false && $mtime < $cutoff->timestamp) {
                $this->deleteDirectory($path);
                $this->info("Removed old backup: {$name}");
            }
        }
    }

    private function deleteDirectory(string $path): void
    {
        $items = @scandir($path);
        if ($items === false) {
            return;
        }
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $full = $path.DIRECTORY_SEPARATOR.$item;
            if (is_dir($full)) {
                $this->deleteDirectory($full);
            } else {
                @unlink($full);
            }
        }
        @rmdir($path);
    }
}
