<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Panel</title>
    <link rel="stylesheet" href="css/styles3.css">

</head>
</head>
<body style="background-image: url(images/img3.jpg); background-repeat:no-repeat ;background-size: cover;">

<!-- Sidebar  -->
<div class="sidebar">
    <h2>Manager Panel</h2>
    <ul>
        <li><a href="#">Dashboard</a></li>
        <li><a href="http://localhost/manage.php">Leave Requests</a></li>
        <li><a href="http://localhost/reports.php">Leave Authorization</a></li>
        <li><a href="http://localhost/ListEmployee.php">Employees</a></li>
        <li><a href="https://localhost/report.php">Report</a></li>
        <li><a href="#">Setting</a></li>
        <li><a href="http://localhost/">Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Upper Navbar -->
    <div class="navbar">
        <span class="user-name">Hello Manager</span>
    </div>

    <!--  Main for Panel  -->
    <div class="content">
        <h1 style="color: white;text-align: left;">Welcome To Manager Panel</h1>
        <?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $department = $_POST['department'];

    $query = "INSERT INTO Employees (FullName, Role, Department) VALUES (?, ?, ?)";
    $params = array($name, $role, $department);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt) {
        echo "<script>alert('Employee Added Successfully.');</script>";
    } else {
        echo "<script>alert('There Was an eror in adding Empolyee');</script>";
    }
}
?>
<form method="POST" action="">
    <label style="font-size: 20px;" for="form" id="label"> Add a Employee</label><br>
    <label>FullName:</label>
    <input type="text" name="name" required >
    <label>Role:</label>
    <input type="text" name="role" required>
    <label>Department:</label>
    <input type="text" name="department" required>
    <button type="submit" id="button">Add</button>
</form>
    </div>

</body>
</html>