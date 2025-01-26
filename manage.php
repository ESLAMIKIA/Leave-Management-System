<?php
include 'db.php';

$sql = "SELECT * FROM Leaves"; 
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
    <h1 class="text-center">Eevery Leave Request</h1>
    <form method="POST" action="confirm.php">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Confirm</th>
                    <th>EndDate</th>
                    <th>StartDate</th>
                    <th>LeaveType</th>
                    <th>ID</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="confirm_ids[]" value="<?php echo $row['LeaveID']; ?>">
                        </td>
                        <td><?php echo htmlspecialchars($row['EndDate'] -> format('Y/m/d')); ?></td>
                        <td><?php echo htmlspecialchars($row['StartDate'] -> format('Y/m/d')); ?></td>
                        <td><?php echo htmlspecialchars($row['LeaveType']); ?></td>
                        <td><?php echo htmlspecialchars($row['EmployeeID']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-success" >Confrim the Selected one</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>