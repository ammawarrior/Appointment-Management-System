"use strict";

$(".swal-button").click(function () {
  const buttonId = $(this).data("id");

  swal({
    title: "Fetching Details...",
    text: "Please wait while we retrieve the transaction data.",
    icon: "info",
    buttons: false,
    closeOnClickOutside: false,
    closeOnEsc: false
  });

  $.ajax({
    url: "fetch_transaction.php",
    type: "POST",
    data: { id: buttonId },
    dataType: "json",
    success: function (response) {
      if (response.status !== "error") {
        const statusInt = parseInt(response.status, 10);
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
                  <tr><td style="font-weight: bold;">Transaction ID</td><td>${response.unique_id || "N/A"}</td></tr>
                  <tr><td style="font-weight: bold;">Sample Type</td><td>${labType}</td></tr>
                  <tr><td style="font-weight: bold;">Category</td><td>${response.category}</td></tr>
                  <tr><td style="font-weight: bold;">Quantity</td><td>${response.quantity}</td></tr>
                  <tr><td style="font-weight: bold;">Request Type</td><td>${response.request_type}</td></tr>
                  <tr><td style="font-weight: bold;">Fullname</td><td>${response.full_name}</td></tr>
                  <tr><td style="font-weight: bold;">Contact Number</td><td>${response.contact_number}</td></tr>
                  <tr><td style="font-weight: bold;">Address</td><td>${response.address}</td></tr>
                  <tr><td style="font-weight: bold;">Email Address</td><td>${response.email_address}</td></tr>
                  <tr><td style="font-weight: bold;">Date Submitted</td><td>${response.submission_date}</td></tr>
                  <tr><td style="font-weight: bold;">Date Appointed</td><td>${response.submission_date_selected}</td></tr>
                </table>
              `
            }
          },
          buttons: {
            cancel: "Close",
            reject: {
              text: "Reject",
              value: "reject",
              className: ""
            },
            confirm: {
              text: "Confirm",
              value: "confirm",
              className: ""
            }
          },
          closeOnClickOutside: false
        }).then((value) => {
          if (value === "confirm") {
            updateTransactionStatus(buttonId, 2);
          } else if (value === "reject") {
            updateTransactionStatus(buttonId, 3);
          }
        });

        setTimeout(() => {
          $(".swal-button--reject").css({
            "background-color": "#d9534f",
            "color": "white"
          });
          $(".swal-button--confirm").css({
            "background-color": "#5cb85c",
            "color": "white"
          });
        }, 100);
      } else {
        swal("Error", "Transaction details not found.", "error");
      }
    },
    error: function () {
      swal("Error", "Failed to fetch data. Please try again later.", "error");
    }
  });
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
    success: function (response) {
      if (response.trim() === "success") {
        swal("Success", "Transaction status updated successfully!", "success").then(() => {
          location.reload();
        });
      } else {
        swal("Error", "Could not update transaction.", "error");
      }
    },
    error: function () {
      swal("Error", "Server error. Please try again later.", "error");
    }
  });
}
