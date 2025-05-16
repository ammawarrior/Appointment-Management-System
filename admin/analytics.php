<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include('includes/header.php');
include 'temp_db.php';

// Get user role from session
$user_role = $_SESSION['role'];

// Set query based on role
if (in_array($user_role, [1, 6, 7])) { // Roles 1, 6, and 7 see all

    $query = "SELECT submission_date_selected, COUNT(*) as count FROM submissions GROUP BY submission_date_selected ORDER BY submission_date_selected ASC";
    $totalQuery = "SELECT COUNT(*) as total FROM submissions";
    $statusQuery = "SELECT status, COUNT(*) as count FROM submissions GROUP BY status";
    $labQuery = "SELECT lab_id, COUNT(*) as count FROM submissions GROUP BY lab_id";
} else {
    $lab_id = $user_role - 1; // Ensure correct mapping
    $query = "SELECT submission_date_selected, COUNT(*) as count FROM submissions WHERE lab_id = ? GROUP BY submission_date_selected ORDER BY submission_date_selected ASC";
    $totalQuery = "SELECT COUNT(*) as total FROM submissions WHERE lab_id = ?";
    $statusQuery = "SELECT status, COUNT(*) as count FROM submissions WHERE lab_id = ? GROUP BY status";
    // This query should ALWAYS show all lab submissions, regardless of role
$labQuery = "SELECT lab_id, COUNT(*) as count FROM submissions GROUP BY lab_id";

}

// Use prepared statements to prevent SQL injection
function fetchData($conn, $sql, $param = null) {
    $stmt = $conn->prepare($sql);
    if ($param !== null) {
        $stmt->bind_param("i", $param);
    }
    $stmt->execute();
    return $stmt->get_result();
}

// Fetch submission counts over time
$allowed_roles = [1, 6, 7];
$result = fetchData($conn, $query, in_array($user_role, $allowed_roles) ? null : $lab_id);

$dates = [];
$counts = [];
while ($row = $result->fetch_assoc()) {
    $dates[] = $row['submission_date_selected'];
    $counts[] = $row['count'];
}

$allowed_roles = [1, 6, 7];

// Fetch total submissions
$totalResult = fetchData($conn, $totalQuery, in_array($user_role, $allowed_roles) ? null : $lab_id);
$totalSubmissions = $totalResult->fetch_assoc()['total'] ?? 0;

// Fetch status counts (Pending = 0, Approved = 1, Rejected = 2)
$statusResult = fetchData($conn, $statusQuery, in_array($user_role, $allowed_roles) ? null : $lab_id);
$statusCounts = [0 => 0, 1 => 0, 2 => 0]; // Default counts
while ($row = $statusResult->fetch_assoc()) {
    $statusCounts[$row['status']] = $row['count'];
}


// Fetch lab submissions count
$labResult = fetchData($conn, $labQuery); // ✅ No parameter for labQuery

$labCounts = [];
while ($row = $labResult->fetch_assoc()) {
    $labCounts[$row['lab_id']] = $row['count'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- PAGE SPECIFIC CSS -->
    <link rel="stylesheet" href="assets/modules/datatables/datatables.min.css">
    <link rel="stylesheet" href="assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="icon" type="image/png" href="assets/img/dost.png">

</head>

<body class="layout-4">
<div class="page-loader-wrapper">
        <span class="loader"><span class="loader-inner"></span></span>
    </div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <?php include('includes/topnav.php'); ?>
            <?php include('includes/sidebar.php'); ?>

            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1>Analytics Dashboard</h1>
                    </div>
                    <div class="section-body">
                    <div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary"><i class="fas fa-file-alt"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>Total Submissions</h4></div>
                <div class="card-body"> <?php echo $totalSubmissions; ?> </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success"><i class="fas fa-check-circle"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>Approved</h4></div>
                <div class="card-body"> <?php echo $statusCounts[2] ?? 0; ?> </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card card-statistic-1">
            <div class="card-icon bg-warning"><i class="fas fa-clock"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>Pending</h4></div>
                <div class="card-body"> <?php echo $statusCounts[1] ?? 0; ?> </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card card-statistic-1">
            <div class="card-icon bg-danger"><i class="fas fa-times-circle"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>Rejected</h4></div>
                <div class="card-body"> <?php echo $statusCounts[3] ?? 0; ?> </div>
            </div>
        </div>
    </div>
</div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header"><h4>Number of Submissions Over Time</h4></div>
                                    <div class="card-body"><div id="apex-timeline-chart"></div></div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header"><h4>Submissions Per Laboratory</h4></div>
                                    <div class="card-body"><div id="lab-chart"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="assets/bundles/lib.vendor.bundle.js"></script>
    <script src="js/CodiePie.js"></script>

    <!-- JS Libraries -->
    <script src="assets/modules/datatables/datatables.min.js"></script>
    <script src="assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
    <script src="assets/modules/jquery-ui/jquery-ui.min.js"></script>
    <script src="assets/modules/sweetalert/sweetalert.min.js"></script>

    <!-- Page Specific JS File -->
    <script src="js/page/modules-datatables.js"></script>
    <script src="js/page/modules-sweetalert.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/custom.js"></script>

    <!-- ApexCharts Script for Analytics -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        var options = {
            chart: { type: 'area', height: 350 },
            series: [{ name: 'Submissions', data: <?php echo json_encode(array_values($counts)); ?> }],
            xaxis: { categories: <?php echo json_encode(array_values($dates)); ?>, labels: { rotate: -45 } },
            colors: ['#3b4c7d'], stroke: { curve: 'smooth' },
            title: { text: "Number of Submissions Over Time", align: "center" }
        };
        new ApexCharts(document.querySelector("#apex-timeline-chart"), options).render();

        var labChartOptions = {
    chart: { type: 'bar', height: 350 },
    series: [{ name: 'Submissions', data: <?php echo json_encode(array_values($labCounts)); ?> }],
    xaxis: { 
        categories: ['Metrology Calibration', 'Chemical Analysis', 'Microbiological Analysis', 'Shelf-life Analysis', 'Get Certificates', 'General Inquiry'], 
        labels: { rotate: -45 }
    },
    colors: ['#3b4c7d'], 
    title: { text: "Submissions Per Laboratory", align: "center" }
};

        new ApexCharts(document.querySelector("#lab-chart"), labChartOptions).render();
    });
</script>

</body>
</html>