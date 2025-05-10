<?php
include("../../Config/conect.php");
session_start();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?error=" . urlencode("Invalid bonus ID"));
    exit();
}

$id = intval($_GET['id']);
$sql = "SELECT b.*, sp.EmpName 
        FROM prbonus b 
        LEFT JOIN hrstaffprofile sp ON b.EmpCode = sp.EmpCode 
        WHERE b.ID = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php?error=" . urlencode("Bonus not found"));
    exit();
}

$bonus = $result->fetch_assoc();

// Fetch employee list
$empQuery = "SELECT EmpCode, EmpName FROM hrstaffprofile WHERE Status = 'Active' ORDER BY EmpName";
$employees = $con->query($empQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bonus</title>
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
                            <h4 class="mb-0">Edit Bonus</h4>
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="../../action/PRBonus/edit.php" method="POST" id="bonusForm">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($bonus['ID']); ?>">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="empCode" class="form-label">Employee</label>
                                    <select class="form-select" name="empCode" id="empCode" required>
                                        <option value="">Select Employee</option>
                                        <?php while($emp = $employees->fetch_assoc()): ?>
                                            <option value="<?php echo htmlspecialchars($emp['EmpCode']); ?>"
                                                <?php echo ($emp['EmpCode'] === $bonus['EmpCode']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($emp['EmpCode'] . ' - ' . $emp['EmpName']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="bonusType" class="form-label">Bonus Type</label>
                                    <input type="text" class="form-control" name="bonusType" id="bonusType" 
                                           value="<?php echo htmlspecialchars($bonus['BonusType']); ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" name="description" id="description" rows="3" required><?php echo htmlspecialchars($bonus['Description']); ?></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="fromDate" class="form-label">From Date</label>
                                    <input type="date" class="form-control" name="fromDate" id="fromDate" 
                                           value="<?php echo htmlspecialchars($bonus['FromDate']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="toDate" class="form-label">To Date</label>
                                    <input type="date" class="form-control" name="toDate" id="toDate" 
                                           value="<?php echo htmlspecialchars($bonus['ToDate']); ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" class="form-control" name="amount" id="amount" 
                                           step="0.01" min="0" value="<?php echo htmlspecialchars($bonus['Amount']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" name="status" id="status" required>
                                        <option value="Active" <?php echo ($bonus['Status'] === 'Active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="Inactive" <?php echo ($bonus['Status'] === 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="remark" class="form-label">Remark</label>
                                <textarea class="form-control" name="remark" id="remark" rows="2"><?php echo htmlspecialchars($bonus['Remark']); ?></textarea>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Bonus
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

            // Check for error message
            const urlParams = new URLSearchParams(window.location.search);
            const errorMsg = urlParams.get('error');
            if (errorMsg) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: decodeURIComponent(errorMsg),
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        });
    </script>
</body>
</html>