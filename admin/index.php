<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'temp_db.php';

// Get user role from session
$user_role = $_SESSION['role'];

// Define lab_id mapping
$labNames = [
    1 => 'Metrology Calibration',
    2 => 'Chemical Analysis',
    3 => 'Microbiological Analysis',
    4 => 'Shelf-life Analysis',
    5 => 'Get Certificates',
    6 => 'General Inquiry'
];

// Set query based on role
if (in_array($user_role, [1, 6, 7])) {
    $query = "SELECT unique_id, lab_id, category, full_name, contact_number, submission_date_selected, quantity, status FROM submissions WHERE status = 1";
} else {
    $lab_id = $user_role - 1;
    $query = "SELECT unique_id, lab_id, category, full_name, contact_number, submission_date_selected, quantity, status FROM submissions WHERE lab_id = $lab_id AND status = 1";
}
$result = $conn->query($query);
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
                <div class="section-header d-flex justify-content-between align-items-center">
    <h1>Reservations Dashboard</h1>
    <div id="current-datetime" style="font-size: 1.1rem; font-weight: 500;"></div>
</div>



                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4>For Confirmation</h4>
                                        <button class="btn" style="background-color: #3b4c7d; color: white;" onclick="window.location.href='generate_pdf.php'">Download Report</button>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped v_center" id="table-1">
                                                <thead>
                                                    <tr>
                                                        <th>Transaction No.</th>
                                                        <th>Sample Classification</th>
                                                        <th>Category</th>
                                                        <th>Quantity</th>
                                                        <th>Client Name</th>
                                                        <th>Phone Number</th>
                                                        <th>Date Reserved</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            $labName = isset($labNames[$row['lab_id']]) ? $labNames[$row['lab_id']] : 'Unknown';
                                                            
                                                            switch ($row['status']) {
                                                                case 1:
                                                                    $statusBadge = '<div class="badge badge-warning">Pending</div>';
                                                                    break;
                                                                case 2:
                                                                    $statusBadge = '<div class="badge badge-success">Confirmed</div>';
                                                                    break;
                                                                case 3:
                                                                    $statusBadge = '<div class="badge badge-danger">Rejected</div>';
                                                                    break;
                                                                default:
                                                                    $statusBadge = '<div class="badge badge-secondary">Unknown</div>';
                                                            }
                                                            echo "<tr>
                                                                <td>{$row['unique_id']}</td>
                                                                <td>{$labName}</td>
                                                                <td>{$row['category']}</td>
                                                                <td>{$row['quantity']}</td>
                                                                <td>{$row['full_name']}</td>
                                                                <td>{$row['contact_number']}</td>
                                                                <td>{$row['submission_date_selected']}</td>
                                                                <td>{$statusBadge}</td>
                                                                <td><button class='btn btn-primary swal-button' data-id='{$row['unique_id']}'>Details</button></td>
                                                            </tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='9' class='text-center'>No reservations found</td></tr>";
                                                    }
                                                    $conn->close();
                                                    ?>
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
    function updateDateTime() {
        const now = new Date();

        // Formatting date and time
        const dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
        const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };

        // Get the formatted date and time
        const dateStr = now.toLocaleDateString('en-US', dateOptions);  // e.g., "April 10, 2025"
        const timeStr = now.toLocaleTimeString('en-US', timeOptions);  // e.g., "09:32:13 AM"

        // Combine date and time with '|' separator and update the content of #current-datetime
        document.getElementById('current-datetime').textContent = `${dateStr} | ${timeStr}`;
    }

    // Update both date and time every second
    setInterval(updateDateTime, 1000);

    // Initial call to display date and time immediately
    updateDateTime();
</script>




    
</body>
</html>
