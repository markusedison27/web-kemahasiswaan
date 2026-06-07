<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    public function index()
    {
        $backupDir = storage_path('app/backups');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $files = File::files($backupDir);
        $backups = [];

        foreach ($files as $file) {
            if ($file->getExtension() === 'sql') {
                $backups[] = [
                    'name' => $file->getFilename(),
                    'size' => round($file->getSize() / 1024, 2) . ' KB',
                    'date' => date('Y-m-d H:i:s', $file->getMTime()),
                ];
            }
        }

        // Sort backups by date descending
        usort($backups, function ($a, $b) {
            return strcmp($b['date'], $a['date']);
        });

        return view('admin.backups.index', compact('backups'));
    }

    public function create()
    {
        $backupDir = storage_path('app/backups');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $filename = 'backup_' . date('Ymd_His') . '.sql';
        $filepath = $backupDir . DIRECTORY_SEPARATOR . $filename;

        // Path to mysqldump in XAMPP
        $mysqldumpPath = 'D:\\xampp\\mysql\\bin\\mysqldump.exe';

        if (!File::exists($mysqldumpPath)) {
            // Fallback if not on D drive or custom path
            $mysqldumpPath = 'mysqldump';
        }

        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        $passwordArg = $dbPass ? "-p" . $dbPass : "";

        // Under Windows, we can invoke cmd to redirect output to file securely
        $command = sprintf(
            '"%s" -u %s %s %s > "%s"',
            $mysqldumpPath,
            $dbUser,
            $passwordArg,
            $dbName,
            $filepath
        );

        $output = [];
        $returnVar = -1;
        
        // Execute command
        exec($command, $output, $returnVar);

        if ($returnVar === 0 && File::exists($filepath) && File::size($filepath) > 0) {
            ActivityLogger::log('BACKUP_DB', "Membuat backup database berhasil: {$filename}");
            return redirect()->route('admin.backups')->with('success', 'Backup database berhasil dibuat.');
        }

        // If mysqldump executable call failed, let's write a pure PHP fallback exporter to be extremely robust!
        // This ensures the backup ALWAYS works even if exec() is restricted or paths are wrong.
        return $this->fallbackPhpBackup($filepath, $filename);
    }

    private function fallbackPhpBackup($filepath, $filename)
    {
        try {
            $tables = [];
            $result = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
            $dbProp = 'Tables_in_' . config('database.connections.mysql.database');
            
            $sql = "-- SIMAWA Backup SQL Dump\n";
            $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($result as $row) {
                $tableName = $row->$dbProp;
                
                // Structure
                $createTable = \Illuminate\Support\Facades\DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createProp = 'Create Table';
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sql .= $createTable[0]->$createProp . ";\n\n";

                // Data
                $rows = \Illuminate\Support\Facades\DB::table($tableName)->get();
                if ($rows->count() > 0) {
                    $sql .= "INSERT INTO `{$tableName}` VALUES \n";
                    $insertLines = [];
                    foreach ($rows as $item) {
                        $values = array_map(function ($val) {
                            if (is_null($val)) return 'NULL';
                            return "'" . addslashes($val) . "'";
                        }, (array)$item);
                        $insertLines[] = "(" . implode(',', $values) . ")";
                    }
                    $sql .= implode(",\n", $insertLines) . ";\n\n";
                }
            }
            
            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
            
            File::put($filepath, $sql);
            ActivityLogger::log('BACKUP_DB', "Membuat backup database (PHP Fallback) berhasil: {$filename}");
            return redirect()->route('admin.backups')->with('success', 'Backup database (PHP Fallback) berhasil dibuat.');
        } catch (\Exception $e) {
            ActivityLogger::log('BACKUP_FAILED', "Gagal membuat backup database: " . $e->getMessage());
            return redirect()->route('admin.backups')->with('error', 'Gagal mencadangkan database: ' . $e->getMessage());
        }
    }

    public function download($filename)
    {
        // Path Traversal Security Protection
        $filename = basename($filename);
        $filepath = storage_path('app/backups') . DIRECTORY_SEPARATOR . $filename;

        if (File::exists($filepath)) {
            ActivityLogger::log('DOWNLOAD_BACKUP', "Mengunduh file backup: {$filename}");
            return response()->download($filepath);
        }

        abort(404, 'File backup tidak ditemukan.');
    }

    public function delete($filename)
    {
        // Path Traversal Security Protection
        $filename = basename($filename);
        $filepath = storage_path('app/backups') . DIRECTORY_SEPARATOR . $filename;

        if (File::exists($filepath)) {
            File::delete($filepath);
            ActivityLogger::log('DELETE_BACKUP', "Menghapus file backup: {$filename}");
            return redirect()->route('admin.backups')->with('success', 'File backup berhasil dihapus.');
        }

        return redirect()->route('admin.backups')->with('error', 'File backup tidak ditemukan.');
    }
}
