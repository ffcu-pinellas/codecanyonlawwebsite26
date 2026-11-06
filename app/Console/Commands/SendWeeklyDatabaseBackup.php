<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DatabaseBackupService;

class SendWeeklyDatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'practice:weekly-backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate full database dump and send encrypted backup to Telegram';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting weekly practice database backup...");
        $res = DatabaseBackupService::createAndSendBackup("📅 <b>Scheduled Weekly Practice Backup</b>\n✅ Encrypted MySQL dump delivered.");
        if ($res['success']) {
            $this->info("Backup succeeded: " . $res['message']);
            return 0;
        } else {
            $this->error("Backup failed: " . $res['message']);
            return 1;
        }
    }
}

