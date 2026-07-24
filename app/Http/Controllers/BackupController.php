<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class BackupController extends Controller
{
    public function download()
    {
        $connection = config('database.default');

        if ($connection === 'sqlite') {
            $path = config('database.connections.sqlite.database');
            return Response::download($path, "backup_PantauSaja_" . date('Y-m-d_H-i-s') . ".sqlite");
        }

        $pdo = DB::connection()->getPdo();
        $tables = DB::select('SHOW TABLES');
        $databaseName = config('database.connections.mysql.database');
        $property = "Tables_in_{$databaseName}";

        $output = "-- PantauSaja Database Backup\n";
        $output .= "-- Date: " . date('Y-m-d H:i:s') . "\n";
        $output .= "SET NAMES utf8mb4;\n";
        $output .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$property;

            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`")[0];
            $output .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $output .= $createTable->{'Create Table'} . ";\n\n";

            $rows = DB::table($tableName)->get();
            foreach ($rows as $row) {
                $row = (array)$row;
                $keys = array_keys($row);
                $values = array_values($row);

                $escapedValues = array_map(function($value) use ($pdo) {
                    if (is_null($value)) return "NULL";
                    return $pdo->quote((string) $value);
                }, $values);

                $output .= "INSERT INTO `{$tableName}` (`" . implode("`, `", $keys) . "`) VALUES (" . implode(", ", $escapedValues) . ");\n";
            }
            $output .= "\n\n";
        }

        $output .= "SET FOREIGN_KEY_CHECKS = 1;\n";

        $filename = "backup_PantauSaja_" . date('Y-m-d_H-i-s') . ".sql";

        return Response::make($output, 200, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }
}
