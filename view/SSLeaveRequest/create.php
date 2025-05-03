<?php
include("../../Config/conect.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Leave Request</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../style/career.css" rel="stylesheet">
    <style>
        .required:after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Create Leave Request</h4>
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="leaveRequestForm" action="../../action/SSLeaveRequest/create.php" method="POST" novalidate>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="employeeID" class="form-label required">Employee ID</label>
                                        <select class="form-select" id="employeeID" name="empCode" required>
                                            <option value="">Select Employee</option>
                                            <?php
                                            $sql = "SELECT EmpCode, EmpName FROM hrstaffprofile WHERE Status = 'Active' ORDER BY EmpName";
                                            $result = $con->query($sql);
                                            while($row = $result->fetch_assoc()) {
                                                echo "<option value='" . htmlspecialchars($row['EmpCode']) . "'>" . 
                                                     htmlspecialchars($row['EmpCode'] . ' - ' . $row['EmpName']) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="leaveType" class="form-label required">Leave Type</label>
                                        <select class="form-select" id="leaveType" name="leaveType" required>
                                            <option value="">Select Leave Type</option>
                                            <?php
                                            $sql = "SELECT * FROM lmleavetype";
                                            $result = $con->query($sql);
                                            while($row = $result->fetch_assoc()) {
                                                echo "<option value='" . htmlspecialchars($row['Code']) . "'>" . 
                                                     htmlspecialchars($row['LeaveType']) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fromDate" class="form-label required">From Date</label>
                                        <input type="date" class="form-control" id="fromDate" name="fromDate" onchange="calculateLeaveDays()" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="toDate" class="form-label required">To Date</label>
                                        <input type="date" class="form-control" id="toDate" name="toDate" onchange="calculateLeaveDays()" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="leaveDay" class="form-label required">Leave Day</label>
                                        <input type="number" class="form-control" id="leaveDay" name="leaveDay" value="0" readonly required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                   
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="reason" class="form-label required">Reason</label>
                                        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Submit Request
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#employeeID').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select Employee'
            });

            $('#leaveType').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select Leave Type'
            });

            // Form validation
            $('#leaveRequestForm').on('submit', function(e) {
                e.preventDefault();
                
                // Validate required fields
                if (!this.checkValidity()) {
                    e.stopPropagation();
                    $(this).addClass('was-validated');
                    return;
                }

                // Additional validation for dates
                const fromDate = $('#fromDate').val();
                const toDate = $('#toDate').val();
                
                if (toDate < fromDate) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Dates',
                        text: 'To Date cannot be earlier than From Date'
                    });
                    return;
                }

                // Submit the form
                this.submit();
            });

            // Check for error message in URL
            const urlParams = new URLSearchParams(window.location.search);
            const errorMsg = urlParams.get('error');
            if (errorMsg) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: decodeURIComponent(errorMsg)
                });
            }
        });

        function calculateLeaveDays() {
            const fromDate = new Date($('#fromDate').val());
            const toDate = new Date($('#toDate').val());
            
            if (fromDate && toDate && toDate >= fromDate) {
                // Calculate total days (including weekends)
                const timeDiff = toDate.getTime() - fromDate.getTime();
                const totalDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // Add 1 to include both start and end dates
                
                // Count weekends
                let weekends = 0;
                let currentDate = new Date(fromDate);
                
                while (currentDate <= toDate) {
                    // 0 is Sunday, 6 is Saturday
                    if (currentDate.getDay() === 0 || currentDate.getDay() === 6) {
                        weekends++;
                    }
                    currentDate.setDate(currentDate.getDate() + 1);
                }
                
                // Calculate working days (excluding weekends)
                const workingDays = totalDays - weekends;
                
                // Update the leave days field
                $('#leaveDay').val(workingDays);
            } else {
                $('#leaveDay').val(0);
            }
        }
    </script>
</body>
</html>