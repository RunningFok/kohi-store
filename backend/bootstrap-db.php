<?php

// Ensure database directory exists
$dbDir = __DIR__ . '/database';
if (!is_dir($dbDir)) {
    mkdir($dbDir, 0755, true);
}

// Create database file if it doesn't exist
$dbFile = $dbDir . '/database.sqlite';
if (!file_exists($dbFile)) {
    touch($dbFile);
    chmod($dbFile, 0666);
    echo "Database file created at: $dbFile\n";
} else {
    echo "Database file already exists at: $dbFile\n";
}
