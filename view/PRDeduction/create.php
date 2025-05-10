<?php
include("../../Config/conect.php");
session_start();

// Fetch employee list
$sql = "SELECT EmpCode, EmpName FROM hrstaffprofile WHERE Status = 'Active' ORDER BY EmpName";
$employees = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Deduction</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../../style/career.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Create New Deduction</h4>
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="../../action/PRDeduction/create" method="POST" id="bonusForm">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="empCode" class="form-label">Employee</label>
                                    <select class="form-select" name="empCode" id="empCode" required>
                                        <option value="">Select Employee</option>
                                        <?php while($emp = $employees->fetch_assoc()): ?>
                                            <option value="<?php echo htmlspecialchars($emp['EmpCode']); ?>">
                                                <?php echo htmlspecialchars($emp['EmpCode'] . ' - ' . $emp['EmpName']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="deductType" class="form-label">Deduction Type</label>
                                    <select name="deductType" id="" class="form-select" required>
                                        <option value="">Select Deduction Type</option>
                                        <option value="Late">Late</option>
                                        <option value="Early">Early</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="fromDate" class="form-label">From Date</label>
                                    <input type="date" class="form-control" name="fromDate" id="fromDate" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="toDate" class="form-label">To Date</label>
                                    <input type="date" class="form-control" name="toDate" id="toDate" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" class="form-control" name="amount" id="amount" 
                                           step="0.01" min="0" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" name="status" id="status" required>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="remark" class="form-label">Remark</label>
                                <textarea class="form-control" name="remark" id="remark" rows="2"></textarea>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Deduction
                                </button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Date validation
            $('#fromDate, #toDate').on('change', function() {
                const fromDate = new Date($('#fromDate').val());
                const toDate = new Date($('#toDate').val());
                
                if (fromDate && toDate && fromDate > toDate) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Date Range',
                        text: 'To Date must be after From Date',
                        timer: 3000,
                        timerProgressBar: true
                    });
                    $('#toDate').val('');
                }
            });

            // Amount validation
            $('#amount').on('input', function() {
                const amount = parseFloat($(this).val());
                if (amount <= 0) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // Form validation before submit
            $('#bonusForm').on('submit', function(e) {
                const amount = parseFloat($('#amount').val());
                const fromDate = new Date($('#fromDate').val());
                const toDate = new Date($('#toDate').val());

                if (amount <= 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Amount',
                        text: 'Amount must be greater than 0',
                        timer: 3000,
                        timerProgressBar: true
                    });
                    return false;
                }

                if (fromDate > toDate) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Date Range',
                        text: 'To Date must be after From Date',
                        timer: 3000,
                        timerProgressBar: true
                    });
                    return false;
                }
            });
        });
    </script>
</body>
</html>