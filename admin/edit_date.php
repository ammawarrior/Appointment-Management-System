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

// Fetch confirmed submissions
$query = "SELECT id, unique_id, lab_id, category, quantity, submission_date_selected, request_type, analysis 
          FROM submissions 
          WHERE status = 2 AND category != 'Walk-in'";


if (!empty($dateFilter)) {
    $query .= " AND submission_date_selected = ?";
}

$stmt = $conn->prepare($query);
if (!empty($dateFilter)) {
    $stmt->bind_param("s", $dateFilter);
}
$stmt->execute();
$result = $stmt->get_result();

$labNames = [
    1 => 'Metrology Calibration',
    2 => 'Chemical Analysis',
    3 => 'Microbiological Analysis',
    4 => 'Shelf-life Analysis',
    5 => 'Get Certificates',
    6 => 'General Inquiry'
];

// Build events array for calendar
$calendarEvents = [];
$submissions = [];
while ($row = $result->fetch_assoc()) {
    $calendarEvents[] = [
        'id' => $row['id'],
        'title' => $row['unique_id'],
        'start' => $row['submission_date_selected'],
        'allDay' => true
    ];
    $submissions[] = $row; // Store for table display
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/header.php'); ?>
    <link rel="stylesheet" href="assets/modules/datatables/datatables.min.css">
    <link rel="stylesheet" href="assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css">
    <link rel="icon" type="image/png" href="assets/img/dost.png">
    <style>
        #dateTable {
            font-size: 13px;
        }
        #dateTable td, #dateTable th {
            padding: 4px 8px;
            vertical-align: middle;
        }
        #calendar {
            max-width: 100%;
            margin: 0 auto;
        }
    </style>
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
                    <h1>Manage Submission Dates</h1>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4>Date Management</h4>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="dateFilter"><strong>Filter by Date:</strong></label>
                                        <input type="date" id="dateFilter" class="form-control form-control-sm" style="width: 200px; display: inline-block;" value="<?= htmlspecialchars($dateFilter) ?>">
                                        <button id="resetFilter" class="btn btn-sm" style="background-color: #3b4c7d; color: white; border: none;">Show All</button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="dateTable">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Lab</th>
                                                    <th>Category</th>
                                                    <th>Request Type</th>
                                                    <th>Analysis</th>
                                                    <th>Quantity</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($submissions as $row) { ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($row['submission_date_selected']) ?></td>
                                                        <td><?= htmlspecialchars($labNames[$row['lab_id']]) ?></td>
                                                        <td><?= htmlspecialchars($row['category']) ?></td>
                                                        <td><?= htmlspecialchars($row['request_type']) ?></td>
                                                        <td><?= htmlspecialchars($row['analysis']) ?></td>
                                                        <td><?= htmlspecialchars($row['quantity']) ?></td>
                                                        <td>
                                                            <button class="btn btn-primary btn-sm edit-date"
                                                                data-id="<?= $row['id'] ?>"
                                                                data-unique="<?= $row['unique_id'] ?>">
                                                                Edit Date
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

<!-- Edit Date Modal with FullCalendar -->
<div class="modal fade" id="dateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Submission Date</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="calendar"></div>
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
<script src="js/page/modules-datatables.js"></script>
<script src="js/page/modules-sweetalert.js"></script>
<script src="assets/modules/sweetalert/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script src="js/scripts.js"></script>
<script src="js/custom.js"></script>
<script>
const allSubmissions = <?= json_encode($submissions) ?>;
const allEvents = <?= json_encode($calendarEvents) ?>;

let selectedId = null;

function formatLocalDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

$(document).ready(function () {
    $("#dateTable").DataTable({
        pageLength: 50,
        lengthMenu: [[25, 50, 100], [25, 50, 100]]
    });

    $("#dateFilter").change(function () {
        window.location.href = "edit_date.php?date=" + $(this).val();
    });

    $("#resetFilter").click(function () {
        window.location.href = "edit_date.php";
    });

    let calendar;

    $(".edit-date").click(function () {
        selectedId = $(this).data("id");

        const selectedData = allSubmissions.find(s => s.id == selectedId);
        const { category, lab_id, request_type, submission_date_selected, analysis, quantity } = selectedData;

        const submissions = allSubmissions.filter(s => s.lab_id == lab_id);
        const calendarEvents = allEvents.filter(e => e.lab_id == lab_id);

        $("#dateModal").modal("show");

        setTimeout(() => {
            if (calendar) calendar.destroy();

            const calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: calendarEvents.map(event => ({
                    ...event,
                    color: '#3788d8',
                    allDay: true
                })),
                editable: false,
                dateClick: function (info) {
                    const dateStr = info.dateStr;
                    const clickedDate = new Date(dateStr);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    const day = clickedDate.getDay();

                    if (clickedDate < today) {
                        swal("Invalid Date!", "You cannot select a past date.", "error");
                        return;
                    }

                    const submissionsOnDate = submissions.filter(s => s.submission_date_selected === dateStr);
                    const totalQuantity = submissionsOnDate.reduce((sum, s) => sum + parseInt(s.quantity), 0);

                    if (lab_id == 1) {
                        const isCategoryValid =
                            (category === "New" && [3, 4, 5, 6].includes(day)) ||
                            (category === "Renewal" && day === 6);

                        const isRequestTypeValid =
                            (request_type === "On-Site" && [3, 4, 5].includes(day)) ||
                            (request_type === "In-House" && day === 6);

                        if (!isCategoryValid) {
                            swal("Invalid Date!", `Category "${category}" not allowed on this day.`, "error");
                            return;
                        }

                        if (!isRequestTypeValid) {
                            swal("Invalid Date!", `Request type "${request_type}" not allowed on this day.`, "error");
                            return;
                        }

                        if ([3, 4, 5].includes(day) && totalQuantity >= 1) {
                            swal("Date Full!", "Only 1 request allowed on Wed–Fri.", "error");
                            return;
                        }

                        if (day === 6 && totalQuantity >= 4) {
                            swal("Date Full!", "Only 4 requests allowed on Saturday.", "error");
                            return;
                        }
                    }

                    // Lab 2–4 logic unchanged
                    if (lab_id == 2) {
                        if ((["Feed - No Waste", "Food - Perishable"].includes(category) && ![1, 2].includes(day)) ||
                            (category === "Waste Water - Treated" && day !== 3)) {
                            swal("Invalid Date!", "Category not allowed on this day.", "error");
                            return;
                        }
                        if (totalQuantity + parseInt(quantity) > 10) {
                            swal("Date Full!", "Max 10 quantity per category per day in Lab 2.", "error");
                            return;
                        }
                    }

                    if (lab_id == 3) {
                        if ((["Food - Perishable", "Swab"].includes(category) && ![1, 2].includes(day)) ||
                            (category === "Waste Water - Treated" && day !== 3)) {
                            swal("Invalid Date!", "Category not allowed on this day.", "error");
                            return;
                        }
                        if (totalQuantity + parseInt(quantity) > 10) {
                            swal("Date Full!", "Max 10 quantity per category per day in Lab 3.", "error");
                            return;
                        }
                    }

                    if (lab_id == 4) {
                        if (analysis !== "Shelf Life Evaluation" && analysis !== "Sensory Evaluation") {
                            swal("Invalid Request!", "Invalid analysis type for Lab 4.", "error");
                            return;
                        }
                        if (![1, 2].includes(day)) {
                            swal("Invalid Date!", "Lab 4 only accepts requests on Mon and Tue.", "error");
                            return;
                        }
                        if (submissionsOnDate.length >= 2) {
                            swal("Date Full!", "Only 2 requests allowed per day in Lab 4.", "error");
                            return;
                        }
                    }

                    updateDate(selectedId, dateStr);
                },
                dayCellDidMount: function (arg) {
                    const cellDate = arg.date;
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    const day = cellDate.getDay();
                    const dateStr = formatLocalDate(cellDate);
                    const isSelected = dateStr === submission_date_selected;

                    const submissionsOnDate = submissions.filter(s => s.submission_date_selected === dateStr);
                    const totalQuantity = submissionsOnDate.reduce((sum, s) => sum + parseInt(s.quantity), 0);

                    let isValid = false;

                    if (lab_id == 1) {
                        const isCategoryValid =
                            (category === "New" && [3, 4, 5, 6].includes(day)) ||
                            (category === "Renewal" && day === 6);

                        const isRequestTypeValid =
                            (request_type === "On-Site" && [3, 4, 5].includes(day)) ||
                            (request_type === "In-House" && day === 6);

                        if (isCategoryValid && isRequestTypeValid) {
                            if ([3, 4, 5].includes(day) && totalQuantity < 1) isValid = true;
                            if (day === 6 && totalQuantity < 4) isValid = true;
                        }
                    } else if (lab_id == 2) {
                        if ((["Feed - No Waste", "Food - Perishable"].includes(category) && [1, 2].includes(day) && totalQuantity < 10) ||
                            (category === "Waste Water - Treated" && day === 3 && totalQuantity < 10)) isValid = true;
                    } else if (lab_id == 3) {
                        if ((["Food - Perishable", "Swab"].includes(category) && [1, 2].includes(day) && totalQuantity < 10) ||
                            (category === "Waste Water - Treated" && day === 3 && totalQuantity < 10)) isValid = true;
                    } else if (lab_id == 4) {
                        if ((analysis === "Shelf Life Evaluation" || analysis === "Sensory Evaluation") &&
                            [1, 2].includes(day) && submissionsOnDate.length < 2) isValid = true;
                    } else {
                        isValid = true;
                    }

                    if (cellDate < today) {
                        arg.el.style.pointerEvents = "none";
                        arg.el.style.opacity = "0.6";
                    }

                    const quantityLeft = (lab_id == 4) ? (2 - submissionsOnDate.length)
                        : (lab_id == 2 || lab_id == 3) ? (10 - totalQuantity)
                        : (lab_id == 1 && [3, 4, 5].includes(day)) ? (1 - totalQuantity)
                        : (lab_id == 1 && day === 6) ? (4 - totalQuantity) : null;

                    if (isSelected) {
                        arg.el.style.backgroundColor = "#fff3cd";
                    } else if (isValid) {
                        arg.el.style.backgroundColor = "#d4f8d4";

                        const availabilityLabel = document.createElement("div");
                        availabilityLabel.textContent = quantityLeft !== null ? `${quantityLeft} left` : "";
                        availabilityLabel.style.position = "absolute";
                        availabilityLabel.style.bottom = "4px";
                        availabilityLabel.style.left = "50%";
                        availabilityLabel.style.transform = "translateX(-50%)";
                        availabilityLabel.style.fontSize = "0.75em";
                        availabilityLabel.style.color = "#555";

                        arg.el.style.position = "relative";
                        arg.el.appendChild(availabilityLabel);
                    } else {
                        arg.el.style.backgroundColor = "#f8d4d4";
                    }
                }
            });

            calendar.render();
        }, 300);
    });

    function updateDate(id, newDate) {
        swal({
            title: "Update Date?",
            text: "Move to: " + newDate,
            icon: "warning",
            buttons: true,
        }).then((willUpdate) => {
            if (!willUpdate) return;

            $.post("update_submission_date.php", { id: id, submission_date_selected: newDate }, function (response) {
                swal("Success!", "Date updated.", "success").then(() => location.reload());
            }).fail(() => {
                swal("Error!", "Failed to update date.", "error");
            });
        });
    }
});
</script>


</body>
</html>
