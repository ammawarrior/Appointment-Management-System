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
if (in_array($user_role, [1, 6, 7])) { // Roles 1, 6, and 7 see all

    $query = "SELECT unique_id, lab_id, submission_date_selected, status FROM submissions";
} else {
    $lab_id = $user_role - 1; // Map role to lab_id
    $query = "SELECT unique_id, lab_id, submission_date_selected, status FROM submissions WHERE lab_id = $lab_id";
}
$result = $conn->query($query);

$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $color = '#5cb85c'; // Default color for all statuses
        
        if ($row['status'] == 1) { // Pending
            $color = '#f0ad4e'; // Warning color
        } elseif ($row['status'] == 2) { // Confirmed
            $color = '#5cb85c'; // Success color
        } elseif ($row['status'] == 3) { // Rejected
            $color = '#d9534f'; // Danger color
        }
        
        $events[] = [
            'title' => $row['unique_id'], // Store transaction ID as title
            'start' => $row['submission_date_selected'],
            'extendedProps' => [
                'lab' => $labNames[$row['lab_id']] ?? 'Unknown',
                'unique_id' => $row['unique_id'],
            ],
            'color' => $color
        ];
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/header.php'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <link rel="icon" type="image/png" href="assets/img/dost.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
    .swal-button--confirm {
        background-color: #5cb85c !important;
        color: white !important;
    }
    .swal-button--reject {
        background-color: #d9534f !important;
        color: white !important;
    }

    @media (max-width: 768px) {
        #calendar {
            font-size: 14px;
        }

        .fc-toolbar-title {
            font-size: 1.1rem;
        }

        .fc-button {
            font-size: 0.8rem;
            padding: 4px 8px;
        }

        .swal-modal {
            width: 90% !important;
        }
    }
    </style>
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
                        <h1>Appointment Calendar</h1>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4>Scheduled Appointments</h4>
                                    </div>
                                    <div class="card-body">
                                        <div id='calendar'></div>
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

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="assets/bundles/lib.vendor.bundle.js"></script>
    <script src="js/CodiePie.js"></script>
    <script src="assets/modules/jquery-ui/jquery-ui.min.js"></script>
    <script src="assets/modules/sweetalert/sweetalert.min.js"></script>
    <script src="js/page/modules-sweetalert.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/custom.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
            events: <?php echo json_encode($events); ?>,
            eventClick: function(info) {
                const eventData = info.event.extendedProps;
                const buttonId = eventData.unique_id;

                $.ajax({
                    url: "fetch_transaction.php",
                    type: "POST",
                    data: { id: buttonId },
                    dataType: "json",
                    success: function(response) {
                        if (response.status !== "error") {
                            const statusInt = parseInt(response.status, 10);
                            const statusText = statusInt === 2 ? "Confirmed" : statusInt === 3 ? "Rejected" : "Pending";

                            const labDescriptions = {
                                1: "Metrology Calibration",
                                2: "Chemical Analysis",
                                3: "Microbiological Analysis",
                                4: "Shelf-life Analysis"
                            };

                            const labType = labDescriptions[response.lab_id] || "Unknown";

                            swal({
                                title: "Transaction Details",
                                content: {
                                    element: "div",
                                    attributes: {
                                        innerHTML: `
                                            <table style="width: 100%; border-collapse: collapse;">
                                                <tr><td><strong>Transaction ID</strong></td><td>${response.unique_id || "N/A"}</td></tr>
                                                <tr><td><strong>Sample Type</strong></td><td>${labType}</td></tr>
                                                <tr><td><strong>Category</strong></td><td>${response.category}</td></tr>
                                                <tr><td><strong>Quantity</strong></td><td>${response.quantity}</td></tr>
                                                <tr><td><strong>Request Type</strong></td><td>${response.request_type}</td></tr>
                                                <tr><td><strong>Fullname</strong></td><td>${response.full_name}</td></tr>
                                                <tr><td><strong>Contact Number</strong></td><td>${response.contact_number}</td></tr>
                                                <tr><td><strong>Address</strong></td><td>${response.address}</td></tr>
                                                <tr><td><strong>Email Address</strong></td><td>${response.email_address}</td></tr>
                                                <tr><td><strong>Date Submitted</strong></td><td>${response.submission_date}</td></tr>
                                                <tr><td><strong>Date Appointed</strong></td><td>${response.submission_date_selected}</td></tr>
                                            </table>
                                        `,
                                    },
                                },
                                buttons: {
                                    cancel: "Close",
                                    reject: {
                                        text: "Reject",
                                        value: "reject",
                                        visible: true,
                                    },
                                    confirm: {
                                        text: "Confirm",
                                        value: "confirm",
                                        visible: true,
                                    },
                                },
                                closeOnClickOutside: false,
                            }).then((value) => {
                                if (value === "confirm") {
                                    updateTransactionStatus(buttonId, 2);
                                } else if (value === "reject") {
                                    updateTransactionStatus(buttonId, 3);
                                }
                            });
                        } else {
                            swal("Error", "Transaction details not found.", "error");
                        }
                    },
                    error: function () {
                        swal("Error", "Failed to fetch data. Please try again later.", "error");
                    },
                });
            },
            windowResize: function() {
                const width = window.innerWidth;
                calendar.changeView(width < 768 ? 'listWeek' : 'dayGridMonth');
            }
        });

        calendar.render();
    });

    function updateTransactionStatus(id, status) {
        swal({
            title: "Processing...",
            text: "Please wait while we update the status.",
            icon: "info",
            buttons: false,
            closeOnClickOutside: false,
            closeOnEsc: false
        });

        $.ajax({
            url: "update_transaction.php",
            type: "POST",
            data: { id: id, status: status },
            success: function(response) {
                if (response.trim() === "success") {
                    swal("Success", "Transaction status updated successfully!", "success").then(() => {
                        location.reload();
                    });
                } else {
                    swal("Error", "Could not update transaction.", "error");
                }
            },
            error: function() {
                swal("Error", "Server error. Please try again later.", "error");
            }
        });
    }
    </script>
</body>
</html>