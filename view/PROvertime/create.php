<?php
include("../../Config/conect.php");
session_start();

// Fetch employee list for dropdown
$empQuery = "SELECT EmpCode, EmpName FROM hrstaffprofile WHERE Status = 'Active' ORDER BY EmpName";
$empResult = $con->query($empQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Overtime Request</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <!-- Flatpickr CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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
                            <h4 class="mb-0">New Overtime </h4>
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="../../action/PROvertime/create.php" method="POST" id="overtimeForm" novalidate>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label required">Employee</label>
                                    <select name="empcode" class="form-select" required>
                                        <option value="">Select Employee</option>
                                        <?php while($emp = $empResult->fetch_assoc()): ?>
                                            <option value="<?php echo htmlspecialchars($emp['EmpCode']); ?>">
                                                <?php echo htmlspecialchars($emp['EmpCode'] . ' - ' . $emp['EmpName']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">OT Type</label>
                                    <select name="ottype" class="form-select" required>
                                        <option value="">Select OT Type</option>
                                        <?php
                                        // Fetch OT types from protrate table
                                        $otTypeQuery = "SELECT Code, Des, Rate FROM protrate ORDER BY Code";
                                        $otTypeResult = $con->query($otTypeQuery);
                                        
                                        while($ot = $otTypeResult->fetch_assoc()): ?>
                                            <option value="<?php echo htmlspecialchars($ot['Code']); ?>">
                                                <?php echo htmlspecialchars($ot['Des'] . ' (Rate: ' . $ot['Rate'] . ')'); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label required">OT Date</label>
                                    <input type="date" name="otdate" class="form-control date-picker" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">From Time</label>
                                    <input type="time" name="fromtime" class="form-control time-picker" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">To Time</label>
                                    <input type="time" name="totime" class="form-control time-picker" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Reason</label>
                                <textarea name="reason" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="d-flex justify-content-start gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save
                                </button>
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for dropdowns
            $('select[name="empcode"]').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select Employee'
            });

            $('select[name="ottype"]').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select Type'
            });

            // Initialize date picker
            flatpickr(".date-picker", {
                dateFormat: "Y-m-d",
            });

            // Form validation
            $("#overtimeForm").on("submit", function(e) {
                e.preventDefault();
                
                // Validate required fields
                if (!this.checkValidity()) {
                    e.stopPropagation();
                    $(this).addClass('was-validated');
                    return;
                }

                // Additional validation for times
                const fromTime = new Date(`2000-01-01 ${$("input[name='fromtime']").val()}`);
                const toTime = new Date(`2000-01-01 ${$("input[name='totime']").val()}`);
                
                if (fromTime >= toTime) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Times',
                        text: 'End time must be after start time'
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
    </script>
</body>
</html>