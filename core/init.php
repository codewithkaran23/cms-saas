<?php
// core/init.php
// Include this at the TOP of every file

// 1. Error Reporting (Useful for dev)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3. Define Paths (Absolute paths are safer)
define('ROOT_PATH', dirname(__DIR__));

// 4. Auto-Include Core Files
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/core/Auth.php';
require_once ROOT_PATH . '/core/Functions.php';
require_once ROOT_PATH . '/core/TenantManager.php';

// 5. Identify Tenant (Clinic)
// This will still work for both localhost (?clinic=X) and Subdomains
$context = TenantManager::identify();
$clinic = TenantManager::getClinic();
