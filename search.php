<?php
// Connect To data base
include 'db.php';

$startDate = $_POST['start_date'] ?? null;
$endDate = $_POST['end_date'] ?? null;

if ($startDate && $endDate) {
    $sql = "SELECT * FROM Missions WHERE StartDate >= ? AND EndDate <= ?";
    $params = array($startDate, $endDate);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees</title>
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
    <h1 class="text-center">Display the most Leaves in the time period</h1>
    <form method="POST" action="confirm.php">
        <table class="table table-striped table-bordered ">
            <thead class="table-warning">
                <tr>
                    <th>EndDate</th>
                    <th>StartDate</th>
                    <th>MissionType</th>
                    <th>EmployeeID</th>
                    <th>MissionID</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['EndDate'] -> format('Y-m-d')); ?></td>
                        <td><?php echo htmlspecialchars($row['StartDate'] -> format('Y-m-d')); ?></td>
                        <td><?php echo htmlspecialchars($row['MissionType']); ?></td>
                        <td><?php echo htmlspecialchars($row['EmployeeID']); ?></td>
                        <td><?php echo htmlspecialchars($row['MissionID']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </form>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>