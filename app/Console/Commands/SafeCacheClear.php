<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SafeCacheClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'safe:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Safely clear all Laravel caches on Windows systems';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Safely clearing Laravel cache...');
        
        // Clear application cache
        $this->call('cache:clear');
        
        // Clear config cache
        $this->call('config:clear');
        
        // Clear route cache
        $this->call('route:clear');
        
        // Clear view cache
        $this->call('view:clear');
        
        // Manually clear bootstrap cache files
        $this->safelyClearBootstrapCache();
        
        $this->info('All caches cleared successfully!');
        
        return 0;
    }
    
    /**
     * Safely clear bootstrap cache files.
     *
     * @return void
     */
    protected function safelyClearBootstrapCache()
    {
        $this->info('Clearing bootstrap cache files...');
        
        $bootstrapCachePath = base_path('bootstrap/cache');
        $files = File::glob($bootstrapCachePath . '/*.php');
        
        foreach ($files as $file) {
            $this->safelyDeleteFile($file);
        }
        
        // Also clear any temporary files
        $tempFiles = File::glob($bootstrapCachePath . '/*.tmp');
        foreach ($tempFiles as $file) {
            $this->safelyDeleteFile($file);
        }
    }
    
    /**
     * Safely delete a file with multiple attempts.
     *
     * @param string $file
     * @return bool
     */
    protected function safelyDeleteFile($file)
    {
        $attempts = 0;
        $maxAttempts = 5;
        
        while ($attempts < $maxAttempts) {
            try {
                if (@unlink($file)) {
                    $this->line("Deleted: " . basename($file));
                    return true;
                }
            } catch (\Exception $e) {
                // Ignore and retry
            }
            
            $attempts++;
            if ($attempts < $maxAttempts) {
                // Wait before retrying
                usleep(100000); // 100ms
            }
        }
        
        $this->warn("Could not delete: " . basename($file));
        return false;
    }
}