<?php
// Prevent direct script execution
define('TESTING', true);

// Capture all output
ob_start();
require_once(__DIR__ . '/../src/vsp.php');
ob_end_clean();
