<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AutoExportDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:auto-export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically export database to SQL file every hour';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database auto-export...');
        
        try {
            $connection = config('database.default');
            $config = config("database.connections.{$connection}");
            
            $filename = 'ojt_backup_' . date('Y-m-d_H-i-s') . '.sql';
            $backupPath = storage_path('app/backups');
            
            // Create backups directory if it doesn't exist
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }
            
            $filePath = $backupPath . '/' . $filename;
            
            if ($connection === 'mysql') {
                // MySQL export using mysqldump
                $command = sprintf(
                    'mysqldump --user=%s --password=%s --host=%s %s > %s',
                    escapeshellarg($config['username']),
                    escapeshellarg($config['password']),
                    escapeshellarg($config['host']),
                    escapeshellarg($config['database']),
                    escapeshellarg($filePath)
                );
                
                exec($command . ' 2>&1', $output, $returnVar);
                
                if ($returnVar !== 0) {
                    $this->error('Database export failed: ' . implode("\n", $output));
                    return 1;
                }
            } elseif ($connection === 'sqlite') {
                // SQLite export - just copy the database file
                $dbPath = $config['database'];
                if (!file_exists($dbPath)) {
                    $this->error('SQLite database file not found.');
                    return 1;
                }
                copy($dbPath, $filePath);
            } else {
                $this->error('Unsupported database connection type.');
                return 1;
            }
            
            // Clean up old backups (keep only last 24 backups)
            $this->cleanupOldBackups($backupPath);
            
            $this->info("Database exported successfully to: {$filename}");
            $this->info("File size: " . $this->formatBytes(filesize($filePath)));
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Database export failed: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Clean up old backup files (keep only last 24)
     */
    private function cleanupOldBackups($backupPath)
    {
        $files = glob($backupPath . '/ojt_backup_*.sql');
        
        if (count($files) > 24) {
            // Sort by modification time
            usort($files, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            
            // Delete oldest files
            $filesToDelete = array_slice($files, 0, count($files) - 24);
            $deletedCount = 0;
            foreach ($filesToDelete as $file) {
                if (@unlink($file)) {
                    $deletedCount++;
                }
            }
            
            if ($deletedCount > 0) {
                $this->info("Cleaned up {$deletedCount} old backup file(s).");
            }
        }
    }

    /**
     * Format bytes to human readable size
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

