<?php
$last_run_file = __DIR__ . '/.last_reminder_run';
$now = time();
$interval = 86400; // 24 hours

// Only run if 24 hours have passed
if (!file_exists($last_run_file) || ($now - filemtime($last_run_file)) > $interval) {
    touch($last_run_file); // Update timestamp first to avoid race conditions
    include_once('invoice-reminder.php');
}
?>
