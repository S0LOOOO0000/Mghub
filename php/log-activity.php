<?php
/**
 * Logs user activity for admin and staff actions.
 *
 * Usage:
 * logActivity($conn, $user_id, $user_role, $module, $record_id, $action, $details = []);
 */

function logActivity($conn, $user_id, $user_role, $module, $record_id, $action, $details = []) {
    $details_json = json_encode($details, JSON_UNESCAPED_UNICODE);

    $stmt = $conn->prepare("
        INSERT INTO activity_log (user_id, user_role, module, record_id, action, details, log_timestamp)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");
    $stmt->bind_param("ississ", $user_id, $user_role, $module, $record_id, $action, $details_json);
    $stmt->execute();
    $stmt->close();
}
?>
