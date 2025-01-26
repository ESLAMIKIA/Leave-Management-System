<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Take ID
    $confirmIds = $_POST['confirm_ids'] ?? [];

    if (!empty($confirmIds)) {
        foreach ($confirmIds as $id) {
            // Getting Records From Table
            $selectSql = "SELECT * FROM Leaves WHERE LeaveID = ?";
            $selectStmt = sqlsrv_query($conn, $selectSql, [$id]);

            if ($selectStmt && $row = sqlsrv_fetch_array($selectStmt, SQLSRV_FETCH_ASSOC)) {
                // Moving Record To the secound table
                $insertSql = "INSERT INTO Missions (EmployeeID, MissionType,StartDate,EndDate) VALUES (?, ?, ?, ?)";
                $insertStmt = sqlsrv_query($conn, $insertSql, [$row['EmployeeID'], $row['LeaveType'],$row['StartDate'],$row['EndDate']]);

                if ($insertStmt) {
                    // Delete Record from the first Table
                    $deleteSql = "DELETE FROM Leaves WHERE LeaveID = ?";
                    sqlsrv_query($conn, $deleteSql, [$id]);
                }
            }
        }

        echo "<script>alert('The selected data has been moved Successful.');</script>";
    } else {
        echo "<script>alert('There Was no Selected data');</script>";
    }
}
?>

<?php
include 'db.php';

$sql = "SELECT * FROM Missions"; 
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approval</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            direction: rtl;
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center">Every Confirmed Leave</h1>
    <form method="POST" action="#">
        <table class="table table-striped table-bordered">
            <thead class="table-success">
                <tr>
                    <th>EndDate</th>
                    <th>StartDate</th>
                    <th>MissionType</th>
                    <th>ID</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['EndDate'] -> format('Y/m/d')); ?></td>
                        <td><?php echo htmlspecialchars($row['StartDate'] -> format('Y/m/d')); ?></td>
                        <td><?php echo htmlspecialchars($row['MissionType']); ?></td>
                        <td><?php echo htmlspecialchars($row['EmployeeID']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>