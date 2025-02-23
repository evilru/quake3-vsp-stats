<?php
// Set project root path dynamically
define('Ce5c65ec5', dirname(__DIR__));  // Parent of /tests directory
define('cIS_SHELL', 1);       // Shell mode
define('cBIG_STRING_LENGTH', 1024);

// Load base config first (using relative paths)
require('pub/configs/cfg-default.php');

// Store initial parser config (already using relative path - good!)
$initialParserConfig = [
    'config' => 'pub/configs/cfg-default.php',
    'log-gamecode' => 'q3a',
    'logfile' => 'baseq3.log'
];

// Override with test settings
$GLOBALS['cfg']['db'] = [
    'table_prefix' => 'vsp_',
    'hostname' => getenv('DB_HOST') ?: 'db',
    'dbname' => getenv('DB_DATABASE') ?: 'vsp_test',
    'username' => getenv('DB_USERNAME') ?: 'vsp',
    'password' => getenv('DB_PASSWORD') ?: 'vsp',
    'adodb_driver' => 'mysqli'
];

// Initialize parser config
$GLOBALS['V0f14082c'] = $initialParserConfig;

// Load application (could be relative)
require_once('vsp.php');

// Initialize database connection and ADOdb setup
F68c076b3();  // Sets up database connection and loads required libraries

// Helper function to reset global state between tests
function resetGlobalState() {
    global $V0f14082c, $V9c1ebee8, $initialParserConfig;
    $V0f14082c = $initialParserConfig;
    if (isset($V9c1ebee8)) {
        $V9c1ebee8->close();
        $V9c1ebee8 = null;
    }
}
