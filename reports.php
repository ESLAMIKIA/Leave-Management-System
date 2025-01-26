<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave approval organizational chart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007BFF;
            color: white;
        }
        .btn-primary {
            background-color: #0069D9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Leave approval organizational chart</h1>
        <div class="card">
            <div class="card-header">Leave approvers</div>
            <div class="card-body">
                <form method="POST" action="update_approver.php">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>Type of leave</th>
                                <th>Validator role</th>
                                <th>Validator name</th>
                                <th>New Validator</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'db.php';

                            $query = "SELECT * FROM ApprovalChart";
                            $result = sqlsrv_query($conn, $query);

                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . $row['LeaveCategory'] . "</td>";
                                echo "<td>" . $row['ApproverRole'] . "</td>";
                                echo "<td>" . ($row['ApproverName'] ?? '--') . "</td>";
                                echo "<td>
                                        <input type='checkbox' name='approver[]' value='" . $row['ApprovalID'] . "'>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary" style="margin-left: 550px;">Update Validators</button>
                </form>
            </div>
        </div>

        <!--Form to add a new approver-->
        <div class="card">
            <div class="card-header">Adding new Validator</div>
            <div class="card-body">
                <form method="POST" action="add_approver.php">
                    <div class="mb-3">
                        <label for="leaveCategory" class="form-label">Leave Type</label>
                        <select class="form-select" id="leaveCategory" name="leaveCategory" required>
                            <option value="Estehghaghi">Entitlement</option>
                            <option value="Estehlaji">illness</option>
                            <option value="mamoriat">Mission</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="approverRole" class="form-label">Validator role</label>
                        <input type="text" class="form-control" id="approverRole" name="approverRole" placeholder="Like:Human resources manager" required>
                    </div>
                    <div class="mb-3">
                    <label for="approver Name" Class= form-label label>Validator Name(optional)</label>
                    <input type="text" class="form-control" id="approverName" name="approverName"  placeh0lder = "Like:David"> 
                    </div > 
                    <button type="submit" class="btn btn-success" style="margin-left: 550px;">Adding new Validator</button> 
                    </form>
                </div> 
                    
 

</div> 
</div> 
<script src="https://cdn.jsdelivr.net/npm /bootstrap@5.3.0-alpha3/dist/js /bootstrap.bundle.min.js">


