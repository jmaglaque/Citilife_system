<?php
/**
 * PatientApprovalsController.php
 * Handles backend logic for Branch Admin's patient sign-up approvals.
 */

require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../models/PatientModel.php';
require_once __DIR__ . '/../../models/NotificationModel.php';
require_once __DIR__ . '/../../models/AuditLogModel.php';

$userModel = new \UserModel($pdo);
$patientModel = new \PatientModel($pdo);
$notificationModel = new \NotificationModel($pdo);
$auditLogModel = new \AuditLogModel($pdo);
$currentUserId = $_SESSION['user_id'] ?? 0;
$branchId = $_SESSION['branch_id'] ?? null;

$message = '';
$messageType = '';

// 1. Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'] ?? null;
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'Delete') {
            $result = $userModel->deletePatientAccount($userId);
        } else {
            $result = $userModel->processAccountStatus($userId, $action, $notificationModel);
        }
        
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'error';
        
        if ($result['success']) {
            $logAction = "Patient account " . strtolower($action) . "d";
            if ($action === 'Approve') $logAction = "Patient account approved";
            if ($action === 'Reject') $logAction = "Patient account rejected";
            if ($action === 'Restore') $logAction = "Patient account restored to pending";
            
            $auditLogModel->addLog($currentUserId, $logAction, 'Patient Records', 'User', $userId, "Account ID: $userId", $branchId);
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = "error";
    }
}

// 2. Fetch patients
$pendingPatients = $patientModel->getPendingPatients();
$rejectedPatients = $patientModel->getRejectedPatients();
