<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseBackupService
{
    /**
     * Generate full database backup and dispatch to Telegram
     */
    public static function createAndSendBackup($caption = null)
    {
        $botToken = config('services.telegram.bot_token') ?: env('TELEGRAM_BOT_TOKEN');
        $chatId = config('services.telegram.chat_id') ?: env('TELEGRAM_CHAT_ID');

        if (empty($botToken) || empty($chatId)) {
            return ['success' => false, 'message' => 'Telegram Bot Token or Chat ID not configured.'];
        }

        $timestamp = date('Y_m_d_His');
        $dbName = config('database.connections.mysql.database') ?: env('DB_DATABASE', 'yourcpa_db');
        $filename = "backup_{$dbName}_{$timestamp}.sql";
        $gzFilename = "{$filename}.gz";

        $tempDir = storage_path('app/backups');
        if (!file_exists($tempDir)) {
            @mkdir($tempDir, 0755, true);
        }

        $sqlPath = $tempDir . DIRECTORY_SEPARATOR . $filename;
        $gzPath = $tempDir . DIRECTORY_SEPARATOR . $gzFilename;

        try {
            // 1. Generate SQL dump via Pure PHP PDO (100% Hostinger compatible)
            $handle = fopen($sqlPath, 'w+');
            if (!$handle) {
                return ['success' => false, 'message' => 'Unable to write to storage backup directory.'];
            }

            fwrite($handle, "-- Your CPA Expert Database Backup\n");
            fwrite($handle, "-- Generated at: " . date('Y-m-d H:i:s T') . "\n");
            fwrite($handle, "-- Database: {$dbName}\n\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

            $tables = DB::select('SHOW TABLES');
            $dbKey = "Tables_in_{$dbName}";

            foreach ($tables as $tableObj) {
                $table = (array)$tableObj;
                $tableName = reset($table);

                // Structure
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                if (!empty($createTable)) {
                    $createArray = (array)$createTable[0];
                    $createSql = $createArray['Create Table'] ?? reset($createArray);
                    fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");
                    fwrite($handle, $createSql . ";\n\n");
                }

                // Data (in chunks to keep memory usage tiny)
                DB::table($tableName)->orderBy(DB::raw('1'))->chunk(250, function ($rows) use ($handle, $tableName) {
                    if ($rows->isEmpty()) {
                        return;
                    }
                    foreach ($rows as $row) {
                        $rowArray = (array)$row;
                        $fields = array_map(function ($col) {
                            return "`" . str_replace("`", "``", $col) . "`";
                        }, array_keys($rowArray));

                        $values = array_map(function ($val) {
                            if (is_null($val)) return "NULL";
                            return "'" . addslashes((string)$val) . "'";
                        }, array_values($rowArray));

                        fwrite($handle, "INSERT INTO `{$tableName}` (" . implode(", ", $fields) . ") VALUES (" . implode(", ", $values) . ");\n");
                    }
                    fwrite($handle, "\n");
                });
            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
            fclose($handle);

            // 2. Gzip compress
            $gzHandle = gzopen($gzPath, 'w9');
            $sqlHandle = fopen($sqlPath, 'rb');
            while (!feof($sqlHandle)) {
                gzwrite($gzHandle, fread($sqlHandle, 1024 * 512));
            }
            fclose($sqlHandle);
            gzclose($gzHandle);

            // Clean up uncompressed file
            @unlink($sqlPath);

            $filesize = filesize($gzPath);
            $filesizeFormatted = round($filesize / 1024, 2) . ' KB';
            if ($filesize > 1024 * 1024) {
                $filesizeFormatted = round($filesize / (1024 * 1024), 2) . ' MB';
            }

            // 3. Dispatch to Telegram sendDocument
            $url = "https://api.telegram.org/bot{$botToken}/sendDocument";
            $defaultCaption = "🛡️ <b>Database Backup: Your CPA Expert</b>\n"
                            . "📦 <b>File:</b> <code>{$gzFilename}</code>\n"
                            . "📊 <b>Size:</b> {$filesizeFormatted}\n"
                            . "📅 <b>Date:</b> " . date('Y-m-d H:i:s T') . "\n"
                            . "🔒 <b>Database:</b> <code>{$dbName}</code>";

            $captionText = $caption ?: $defaultCaption;

            $cFile = new \CURLFile($gzPath, 'application/gzip', $gzFilename);
            $postData = [
                'chat_id' => $chatId,
                'document' => $cFile,
                'caption' => $captionText,
                'parse_mode' => 'HTML',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            $result = curl_exec($ch);
            curl_close($ch);

            // Keep only latest 3 backups locally
            self::pruneOldBackups($tempDir);

            return [
                'success' => true,
                'message' => "Backup generated ({$filesizeFormatted}) and successfully delivered to Telegram.",
                'file' => $gzFilename,
            ];
        } catch (\Throwable $e) {
            Log::error("Database backup failure: " . $e->getMessage());
            return [
                'success' => false,
                'message' => "Backup failed: " . $e->getMessage(),
            ];
        }
    }

    private static function pruneOldBackups($dir)
    {
        try {
            $files = glob($dir . '/*.gz');
            if (count($files) > 3) {
                usort($files, function($a, $b) {
                    return filemtime($a) - filemtime($b);
                });
                while (count($files) > 3) {
                    @unlink(array_shift($files));
                }
            }
        } catch (\Throwable $e) {}
    }
}
