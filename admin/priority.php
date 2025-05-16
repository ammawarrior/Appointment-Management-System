<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('includes/header.php');
include 'temp_db.php';

// Initialize date filter
$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';

// Fetch confirmed appointments (status = 2) with date filtering
$query = "SELECT id, unique_id, lab_id, category, quantity, priority, submission_date_selected 
          FROM submissions 
          WHERE status = 2";

if (!empty($dateFilter)) {
    $query .= " AND submission_date_selected = ?";
}

$stmt = $conn->prepare($query);
if (!empty($dateFilter)) {
    $stmt->bind_param("s", $dateFilter);
}
$stmt->execute();
$result = $stmt->get_result();

// Lab ID Mapping
$labNames = [
    1 => 'Metrology Calibration',
    2 => 'Chemical Analysis',
    3 => 'Microbiological Analysis',
    4 => 'Shelf-life Analysis',
    5 => 'Get Certificates',
    6 => 'General Inquiry'
];
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
            <?php include('includes/topnav.php'); ?>
            <?php include('includes/sidebar.php'); ?>

            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1>Manage Priority</h1>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4>Priority Management</h4>
                                    </div>
                                    <div class="card-body">

                                        <!-- Date Filter -->
                                        <div class="mb-3">
                                            <label for="dateFilter"><strong>Filter by Date:</strong></label>
                                            <input type="date" id="dateFilter" class="form-control form-control-sm" style="width: 200px; display: inline-block;" value="<?= htmlspecialchars($dateFilter) ?>">
                                            <button id="resetFilter" class="btn btn-sm" style="background-color: #3b4c7d; color: white; border: none;">Show All</button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-striped" id="priorityTable">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Unique ID</th>
                                                        <th>Lab</th>
                                                        <th>Category</th>
                                                        <th>Quantity</th>
                                                        <th>Priority Level</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($row['submission_date_selected']) ?></td>
                                                            <td><?= htmlspecialchars($row['unique_id']) ?></td>
                                                            <td><?= htmlspecialchars($labNames[$row['lab_id']]) ?></td>
                                                            <td><?= htmlspecialchars($row['category']) ?></td>
                                                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                                                            <td><?= htmlspecialchars($row['priority']) ?></td>
                                                            <td>
                                                                <button class="btn btn-primary btn-sm edit-priority"
                                                                    data-id="<?= $row['id'] ?>"
                                                                    data-priority="<?= $row['priority'] ?>">
                                                                    Edit Priority
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
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

    <!-- Priority Modal -->
    <div class="modal fade" id="priorityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Priority</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="priorityForm">
                        <input type="hidden" id="appointmentId">
                        <div class="form-group">
                            <label for="priority">Priority Level</label>
                            <select class="form-control" id="priority">
                                <option value="5">5 - High Priority</option>
                                <option value="4">4 - Medium High Priority</option>
                                <option value="3">3 - Medium Priority</option>
                                <option value="2">2 - Medium Low Priority</option>
                                <option value="1">1 - Low Priority</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </form>
                </div>
            </div>
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
        $("#priorityTable").DataTable({
            "pageLength": 50,  // Default to 50 entries per page
            "lengthMenu": [[25, 50, 100], [25, 50, 100]] // Allow only 25, 50, or 100 entries per page
        });

        // Filter by date
        $("#dateFilter").change(function () {
            let selectedDate = $(this).val();
            window.location.href = "priority.php?date=" + selectedDate;
        });

        // Reset filter
        $("#resetFilter").click(function () {
            window.location.href = "priority.php";
        });

        $(".edit-priority").click(function () {
            let id = $(this).data("id");
            let priority = $(this).data("priority");
            $("#appointmentId").val(id);
            $("#priority").val(priority);
            $("#priorityModal").modal("show");
        });

        $("#priorityForm").submit(function (e) {
            e.preventDefault();
            let id = $("#appointmentId").val();
            let priority = $("#priority").val();

            $.post("update_priority.php", { id: id, priority: priority }, function (response) {
                swal("Success!", "Priority updated successfully.", "success")
                    .then(() => location.reload());
            }).fail(function () {
                swal("Error!", "Failed to update priority.", "error");
            });
        });
    });
</script>

</body>
</html>
