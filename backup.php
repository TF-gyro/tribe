<?php
require __DIR__ . '/_init.php';
$backup = new \App\Backup;

$backup->mysqlDatabase();
$backup->uploadsFolder();
$backup->deleteOldMySQLBackups();
?>
