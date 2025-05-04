<?php
include("../../Config/conect.php");
session_start();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Fetch overtime data
$sql = "SELECT * FROM provertime WHERE ID = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$overtime = $result->fetch_assoc();

if (!$overtime) {
    header("Location: index.php");
    exit();
}

// Fetch employee list for dropdown
$empQuery = "SELECT EmpCode, EmpName FROM hrstaffprofile ORDER BY EmpName";
$empResult = $con->query($empQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Overtime Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
                            <h4 class="mb-0">Edit Overtime Request</h4>
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="../../action/PROvertime/edit.php" method="POST" id="overtimeForm" novalidate>
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($overtime['ID']); ?>">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label required">Employee</label>
                                    <select name="empcode" class="form-select" required disabled>
                                        <option value="">Select Employee</option>
                                        <?php while($emp = $empResult->fetch_assoc()): ?>
                                            <option value="<?php echo htmlspecialchars($emp['EmpCode']); ?>"
                                                <?php echo ($emp['EmpCode'] == $overtime['Empcode']) ? 'selected' : ''; ?>>
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
                                            <option value="<?php echo htmlspecialchars($ot['Code']); ?>" <?php if($ot['Code'] == $overtime['OTType']) echo "selected"; ?>>
                                                <?php echo htmlspecialchars($ot['Des'] . ' (Rate: ' . $ot['Rate'] . ')'); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label required">OT Date</label>
                                    <input type="date" name="otdate" class="form-control date-picker" 
                                           value="<?php echo htmlspecialchars($overtime['OTDate']); ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">From Time</label>
                                    <input type="time" name="fromtime" class="form-control time-picker" 
                                           value="<?php echo htmlspecialchars($overtime['FromTime']); ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">To Time</label>
                                    <input type="time" name="totime" class="form-control time-picker" 
                                           value="<?php echo htmlspecialchars($overtime['ToTime']); ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Reason</label>
                                <textarea name="reason" class="form-control" rows="3" required><?php echo htmlspecialchars($overtime['Reason']); ?></textarea>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Overtime
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize date picker
            flatpickr(".date-picker", {
                dateFormat: "Y-m-d",
                maxDate: "today"
            });

            // Form validation
            $("#overtimeForm").on("submit", function(e) {
                e.preventDefault();
                
                // Check required fields
                let isValid = true;
                $(this).find('[required]').each(function() {
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please fill in all required fields'
                    });
                    return false;
                }

                // Validate time
                const fromTime = new Date(`2000-01-01 ${$("input[name='fromtime']").val()}`);
                const toTime = new Date(`2000-01-01 ${$("input[name='totime']").val()}`);
                
                if (fromTime >= toTime) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Time Error',
                        text: 'End time must be after start time'
                    });
                    return false;
                }

                // If all validation passes, submit the form
                this.submit();
            });

            // Show success/error messages
            <?php if (isset($_GET['success'])): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: <?php echo json_encode($_GET['success']); ?>
                });
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: <?php echo json_encode($_GET['error']); ?>
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>