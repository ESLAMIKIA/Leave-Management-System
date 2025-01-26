
<?php
include 'db.php';
// Check if Send
$error_message = '';
if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['username'] == 'Eslamikia' && $_POST['password'] == 1){
    header("Location: mainpanel.php"); // Move to another page
    exit;
}
else if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (!empty($username) && !empty($password)) {
        // Check if User exist in data base
        $sql = "SELECT * FROM Employees WHERE Fullname = ? AND EmployeeID = ?";
        $params = [$username, $password];
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die("eror in exe: " . print_r(sqlsrv_errors(), true));
        }
        // If User exist
        if (sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            header("Location: panel.php"); // Move to another page
            exit;
        } else {
            // IF User Did not exist in data base
            echo "<script>alert('Worng Name Or ID!');</script>";
        }
    } else {
        echo "<script>alert('Please Fill out every section');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="fa">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body style="background-image: url('images/img.jpg') ; z-index: -1;">
  <div class="login-container" style="background-color:rgba(255, 255, 255, 0.95);"> 
    <h1>Login</h1>
    <form action="" method="post" id="from" >
      <label for="username" style="margin-right: auto;"> FullName</label>
      <input type="text" id="username" name="username" placeholder="Enter your name" required>

      <label for="password" style="margin-right: auto;">EmployeeID</label>
      <input type="password" id="password" name="password" placeholder="Enter your ID" required>

      <button type="submit" style="border-radius: 20px;font-weight:bolder;">Submit</button>
    </form>
  </div>
</body>
</html>