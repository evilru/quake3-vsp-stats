<?php
// Set project root path dynamically
define('APP_ROOT_DIR', dirname(__DIR__));
define('IS_SHELL', 1);
define('LOG_READ_SIZE', 1024);

// Load base config first
require('pub/configs/cfg-default.php');

// Store initial parser config
$GLOBALS['options'] = [
    'config' => APP_ROOT_DIR . '/pub/configs/cfg-default.php',
    'log-gamecode' => 'q3a',
    'logfile' => 'baseq3.log'
];

// Merge database settings with existing config
$GLOBALS['cfg']['db'] = array_merge($GLOBALS['cfg']['db'] ?? [], [
    'table_prefix' => 'vsp_',
    'hostname' => getenv('DB_HOST') ?: 'db',
    'dbname' => getenv('DB_DATABASE') ?: 'vsp_test',
    'username' => getenv('DB_USERNAME') ?: 'vsp',
    'password' => getenv('DB_PASSWORD') ?: 'vsp',
    'adodb_driver' => 'mysqli'
]);

// Load application (could be relative)
require_once('vsp.php');

// Initialize database connection and ADOdb setup
configureAndProcessGameLogs();  // Sets up database connection and loads required libraries

// Helper function to reset global state between tests
function resetGlobalState() {
    global $db, $options;
    if (isset($db)) {
        $db->close();
        $db = null;
    }
}
