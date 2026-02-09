<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup
                            {--path= : Custom backup directory}
                            {--retention= : Days to retain backups (overrides config)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a PostgreSQL database backup';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (config('database.default') !== 'pgsql') {
            $this->error('Database backup is only supported for PostgreSQL.');

            return self::FAILURE;
        }

        $path = $this->option('path') ?? config('backup.path', storage_path('app/backups'));

        if (! is_dir($path)) {
            if (! mkdir($path, 0755, true)) {
                $this->error("Could not create backup directory: {$path}");

                return self::FAILURE;
            }
        }

        $filename = 'backup_'.date('Y-m-d_His').'.sql';
        $filepath = $path.DIRECTORY_SEPARATOR.$filename;

        $config = config('database.connections.pgsql');
        $host = $config['host'];
        $port = $config['port'];
        $database = $config['database'];
        $username = $config['username'];
        $password = $config['password'];

        $env = [
            'PGPASSWORD' => $password,
        ];

        $command = sprintf(
            'pg_dump -h %s -p %s -U %s -d %s --no-owner --no-acl -f %s',
            escapeshellarg($host),
            escapeshellarg((string) $port),
            escapeshellarg($username),
            escapeshellarg($database),
            escapeshellarg($filepath)
        );

        $process = proc_open(
            $command,
            [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ],
            $pipes,
            null,
            array_merge($_ENV, $env)
        );

        if (! is_resource($process)) {
            $this->error('Failed to start pg_dump process.');

            return self::FAILURE;
        }

        fclose($pipes[0]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        if ($exitCode !== 0) {
            $this->error('pg_dump failed: '.trim($stderr));

            return self::FAILURE;
        }

        $this->info("Backup created: {$filepath}");

        // Cleanup old backups
        $retention = (int) ($this->option('retention') ?? config('backup.retention_days', 7));
        $this->pruneOldBackups($path, $retention);

        return self::SUCCESS;
    }

    /**
     * Remove backup files older than retention period.
     */
    protected function pruneOldBackups(string $path, int $retentionDays): void
    {
        $files = glob($path.DIRECTORY_SEPARATOR.'backup_*.sql');

        if ($files === false) {
            return;
        }

        $cutoff = time() - ($retentionDays * 86400);
        $removed = 0;

        foreach ($files as $file) {
            if (filemtime($file) < $cutoff) {
                if (unlink($file)) {
                    $removed++;
                }
            }
        }

        if ($removed > 0) {
            $this->info("Removed {$removed} old backup(s).");
        }
    }
}
