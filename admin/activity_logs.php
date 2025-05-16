<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the user is an Admin
if (!in_array($_SESSION['role'], [1, 6, 7])) {

    header("Location: index.php");
    exit();
}

// Include database connection
include 'temp_db.php';

// Handle delete request
if (isset($_POST['delete_all'])) {
    $deleteQuery = "DELETE FROM user_activity";
    if ($conn->query($deleteQuery)) {
        echo "<script>alert('All activity logs have been deleted successfully!'); window.location.href='activity_logs.php';</script>";
    } else {
        echo "<script>alert('Error deleting logs: " . $conn->error . "');</script>";
    }
}

// Fetch activity logs grouped by date
$query = "SELECT user_activity.id, users.code_name, user_activity.activity, user_activity.timestamp, DATE(user_activity.timestamp) as log_date
          FROM user_activity 
          JOIN users ON user_activity.user_id = users.user_id 
          ORDER BY user_activity.timestamp DESC";
$result = $conn->query($query);

// Store logs by date
$logs_by_date = [];
while ($row = $result->fetch_assoc()) {
    $logs_by_date[$row['log_date']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/header.php'); ?>
    <link rel="stylesheet" href="assets/modules/datatables/datatables.min.css">
    <link rel="stylesheet" href="assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">
    <link rel="icon" type="image/png" href="assets/img/dost.png">
</head>
<body class="layout-4">
<div class="page-loader-wrapper">
        <span class="loader"><span class="loader-inner"></span></span>
    </div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <?php include('includes/topnav.php'); include('includes/sidebar.php'); ?>
            
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1>User Activity Logs</h1>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4>Activity Overview</h4>
                                        
                                        <!-- Delete button for admins -->
                                        <form method="post" onsubmit="return confirm('Are you sure you want to delete all logs?');">
                                            <button type="submit" name="delete_all" class="btn btn-danger">Delete All Activities</button>
                                        </form>
                                    </div>
                                    <div class="card-body">
                                        <!-- Date Filter Labels -->
                                        <!-- Dropdown Date Filter -->
<div class="mb-3">
    <label for="dateFilter"><strong>Filter by Date:</strong></label>
    <input type="date" id="dateFilter" class="form-control form-control-sm" style="width: 200px; display: inline-block;">
    <button id="resetFilter" class="btn btn-sm" style="background-color: #3b4c7d; color: white; border: none;">Show All</button>


</div>


                                        <div class="table-responsive">
                                            <table class="table table-striped v_center" id="table-1">
                                                <thead>
                                                    <tr>
                                                        <th>Username</th>
                                                        <th>Activity</th>
                                                        <th>Timestamp</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($logs_by_date as $date => $logs): ?>
                                                        <tr class="date-label" data-date="<?= $date ?>">
                                                            <td colspan="3" class="text-center bg-light font-weight-bold"><?= $date ?></td>
                                                        </tr>
                                                        <?php foreach ($logs as $log): ?>
                                                            <tr class="log-entry" data-date="<?= $date ?>">
                                                                <td><?= $log['code_name'] ?></td>
                                                                <td><?= $log['activity'] ?></td>
                                                                <td><?= $log['timestamp'] ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>

    <script src="assets/bundles/lib.vendor.bundle.js"></script>
    <script src="js/CodiePie.js"></script>
    <script src="assets/modules/datatables/datatables.min.js"></script>
    <script src="assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
    <script src="assets/modules/jquery-ui/jquery-ui.min.js"></script>
    <script src="assets/modules/sweetalert/sweetalert.min.js"></script>
    <script src="js/page/modules-datatables.js"></script>
    <script src="js/page/modules-sweetalert.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/custom.js"></script>

    <script>
$(document).ready(function () {
    if (!$.fn.DataTable.isDataTable('#table-1')) {
        $('#table-1').DataTable({
            "order": [[2, "desc"]],
            "autoWidth": false,
            "scrollY": "400px",
            "scrollCollapse": true,
            "paging": false,
            "fixedHeader": true
        });
    }

    // Filter logs based on selected date
    $('#dateFilter').on('change', function () {
        var selectedDate = $(this).val();
        if (selectedDate === "") {
            $('.log-entry, .date-label').show(); // Show all logs if no date is selected
        } else {
            $('.log-entry, .date-label').hide(); // Hide all first
            $('.log-entry[data-date="' + selectedDate + '"], .date-label[data-date="' + selectedDate + '"]').show();
        }
    });

    // Reset filter button
    $('#resetFilter').on('click', function () {
        $('#dateFilter').val(""); // Clear the date input
        $('.log-entry, .date-label').show(); // Show all logs
    });
});





    </script>
</body>
</html>
