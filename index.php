<?php
session_start();
ob_start(); // Prevent "headers already sent" errors by buffering output

require_once __DIR__ . '/app/config/database.php';

if (!isset($_SESSION['role'])) {
    header("Location: /" . PROJECT_DIR . "/login.php");
    exit;
}

$role = $_SESSION['role'];
$page = $_GET['page'] ?? 'dashboard';

// whitelist pages (frontend only)
$allowedPages = [
  // radtech pages
  'dashboard',
  'patient-registration',
  'patient-lists',
  'patient-approval',
  'xray-patient-records',
  'record-request',
  'view-record-request',
  // added for viewing case details from patient list
  'patient-details',
  'records-history',

  // radiologist pages
  'worklist',
  'patient-queue',
  'case-review',
  'patient-history',
  'patient-records-history',

  // patient portal pages
  'dashboard',
  'xray-status',
  'my-records',
  'registration',
  'download-report',
  'view-report',
  
  // branch admin pages
  'patient-approvals',
  'record-requests',
  'branch-xray-cases',
  'patient-details',
  'records-history',
  'reports',

  // admin central pages
  'users',
  'branches',
  'patient-records',
  'patient-details',
  'records-history',
  'patient-history',
  'reports',
  'audit-logs',
  'user-role-defaults',
  'settings',
  'security-settings',
  'backup-maintenance',
];

// fallback
if (!in_array($page, $allowedPages, true)) {
  $page = 'dashboard';
}

// --- DYNAMIC RBAC GUARD ---
require_once __DIR__ . '/app/helpers/AuthHelper.php';

// Map pages to permission keys
$pagePermMap = [
    'users'               => 'user_mgmt',
    'branches'            => 'branch_mgmt',
    'patient-registration' => 'patient_reg',
    'patient-approvals'   => 'approvals',
    'audit-logs'          => 'audit_logs',
    'reports'             => 'global_reports',
    'security-settings'   => 'system_security',
    'user-role-defaults'  => 'system_security',
    'settings'            => 'system_security',
    'backup-maintenance'  => 'backup_mgmt'
];

if (isset($pagePermMap[$page])) {
    guardPermission($role, $pagePermMap[$page]);
}
// --------------------------

$controllerPath = __DIR__ . "/app/controllers/{$role}/" . str_replace('-', '', ucwords($page, '-')) . "Controller.php";
if (file_exists($controllerPath)) {
    require_once $controllerPath;
}

$contentView = __DIR__ . "/app/views/pages/{$role}/{$page}.php";

// Intercept specific AJAX requests before loading the layout
if (isset($_GET['ajax_polling']) || ($page === 'patient-registration' && isset($_GET['ajax_search']))) {
    if (file_exists($contentView)) {
        require $contentView;
    }
    exit;
}

// Load layout
if ($page === 'view-report') {
    if (file_exists($contentView)) {
        require $contentView;
    }
} else {
    require __DIR__ . "/app/views/layouts/dashboard.php";
}