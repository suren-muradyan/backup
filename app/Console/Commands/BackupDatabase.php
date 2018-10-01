<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database backup';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $dump_path = config('database.mysqldump_path');
            $connections = config('database')['connections'];

            foreach ($connections as $key => $connection) {
                if (strpos($key,'mysql') !== false) {
                    $name = 'backups\backup_' . $key . '_' . $connection['database'] . '_' .date('m-d-Y_hia') . '.sql';
                    $this->process = new Process(sprintf(
                        '%s -u%s %s > %s',
                        $dump_path,
                        $connection['username'],
                        $connection['database'],
                        storage_path($name)
                    ));

                    $this->process->run();
                }
            }

            $this->info('The backup has been proceed successfully.');
        } catch (ProcessFailedException $exception) {
            $this->error('The backup process has been failed.');
        }
    }
}
