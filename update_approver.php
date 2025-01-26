<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Get selected approvers
    $approvers = $_POST['approver'] ?? [];

    if (!empty($approvers)) {
        foreach ($approvers as $approvalID) {
            //Update the approver (eg new admin)
            $query = "UPDATE ApprovalChart SET ApproverName = 'new manager' WHERE ApprovalID = ?";
            $params = array($approvalID);
            $stmt = sqlsrv_query($conn, $query, $params);

            if (!$stmt) {
                die(print_r(sqlsrv_errors(), true));
            }
        }
        echo "<script>alert('َApprovers successfully updated!');</script>";
    } else {
        echo "<script>alert('No verifiers have been selected');</script>";
    }
}
?>