<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Panel</title>
    <link rel="stylesheet" href="css/stye2.css">
</head>
<body style="background-image: url(images/img2.png); background-repeat:no-repeat ;background-size: cover;">
<div class="sidebar">
    <h2>Employee Panel</h2>
    <ul>
        <li><a href="#">Dashboard</a></li>
        <li><a href="#">Profile</a></li>
        <li><a href="#">Setting</a></li>
        <li><a href="http://localhost/">Logout</a></li>
    </ul>
</div>
<div class="main-content">

    <div class="navbar">
        <span class="user-name">Hello <?php echo ""?> </span>
    </div>

    <div class="content">
        <h1 style="color: black;text-align: left;">Welcome To Employee Panel</h1>
        <?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $EmployeeID = $_POST['EmployeeID'];
    $LeaveType = $_POST['LeaveType'];
    $StartDate = $_POST['StartDate'];
    $EndDate = $_POST['EndDate'];

    $query = "INSERT INTO Leaves(EmployeeID,LeaveType,StartDate,EndDate) VALUES (?, ?, ?, ?)";
    $params = array($EmployeeID, $LeaveType, $StartDate,$EndDate);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt) {
        echo "<script>alert('Leave request successfully added.');</script>";
    } else {
        echo "<script>alert('Eror in Adding Leave request');</script>";
    }
}
?>
<form method="POST" action="" class="fromM" style="font-size: 20px;" >
    <label style="font-weight: 1000;" id="label">Add a new leave request</label>
    <label style="font-weight: bolder;">:ID</label>
    <input type="text" name="EmployeeID" required>
    <label style="font-weight: bolder;">:Type of leave</label>
    <input type="text" name="LeaveType" required>
    <label style="font-weight: bolder;">:Start Date</label>
    <input type="date" name="StartDate" required>
    <label style="font-weight: bolder;">:End Date</label>
    <input type="date" name="EndDate" required>
    <button type="submit" id="button">Apply a Leave request</button>
</form>
 
    </div>
</div>

</body>
</html>