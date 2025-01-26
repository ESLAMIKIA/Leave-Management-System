<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $leaveCategory = $_POST['leaveCategory'];
    $approverRole = $_POST['approverRole'];
    $approverName = $_POST['approverName'] ?? NULL;

    $query = "INSERT INTO ApprovalChart (LeaveCategory, ApproverRole, ApproverName)
              VALUES (?, ?, ?)";
    $params = array($leaveCategory, $approverRole, $approverName);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt) {
        echo "<script>alert('New approver added successfully');</script>";
        header("http://localhost/reports.php"); 
    } else {
        echo "<script>alert('Error in adding verifier');</script>";
        die(print_r(sqlsrv_errors(), true));
    }
}

?>