<?php
include("../../Config/conect.php");
session_start();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?error=" . urlencode("Invalid allowance ID"));
    exit();
}

$id = intval($_GET['id']);
$sql = "SELECT a.*, sp.EmpName 
        FROM prallowance a 
        LEFT JOIN hrstaffprofile sp ON a.EmpCode = sp.EmpCode 
        WHERE a.ID = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php?error=" . urlencode("Allowance not found"));
    exit();
}

$allowance = $result->fetch_assoc();

// Fetch employee list
$empQuery = "SELECT EmpCode, EmpName FROM hrstaffprofile WHERE Status = 'Active' ORDER BY EmpName";
$employees = $con->query($empQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Allowance</title>
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
                            <h4 class="mb-0">Edit Allowance</h4>
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="../../action/PRAllowance/edit.php" method="POST" id="allowanceForm">
                            <input type="hidden" name="ID" value="<?php echo htmlspecialchars($allowance['ID']); ?>">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="EmpCode" class="form-label">Employee</label>
                                    <select class="form-select" name="EmpCode" id="EmpCode" required>
                                        <option value="">Select Employee</option>
                                        <?php while($emp = $employees->fetch_assoc()): ?>
                                            <option value="<?php echo htmlspecialchars($emp['EmpCode']); ?>"
                                                <?php echo ($emp['EmpCode'] === $allowance['EmpCode']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($emp['EmpCode'] . ' - ' . $emp['EmpName']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="AllowanceType" class="form-label">Allowance Type</label>
                                    <select class="form-select" name="AllowanceType" id="AllowanceType" required>
                                        <option value="">Select Type</option>
                                        <?php
                                        $types = ['Transportation', 'Housing', 'Meal', 'Phone', 'Other'];
                                        foreach ($types as $type):
                                        ?>
                                            <option value="<?php echo $type; ?>" 
                                                <?php echo ($type === $allowance['AllowanceType']) ? 'selected' : ''; ?>>
                                                <?php echo $type; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="Description" class="form-label">Description</label>
                                    <textarea class="form-control" name="Description" id="Description" rows="3" required><?php echo htmlspecialchars($allowance['Description']); ?></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="FromDate" class="form-label">From Date</label>
                                    <input type="date" class="form-control" name="FromDate" id="FromDate" 
                                           value="<?php echo htmlspecialchars($allowance['FromDate']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="ToDate" class="form-label">To Date</label>
                                    <input type="date" class="form-control" name="ToDate" id="ToDate" 
                                           value="<?php echo htmlspecialchars($allowance['ToDate']); ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="Amount" class="form-label">Amount</label>
                                    <input type="number" class="form-control" name="Amount" id="Amount" 
                                           step="0.01" min="0" value="<?php echo htmlspecialchars($allowance['Amount']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="Status" class="form-label">Status</label>
                                    <select class="form-select" name="Status" id="Status" required>
                                        <option value="Active" <?php echo ($allowance['Status'] === 'Active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="Inactive" <?php echo ($allowance['Status'] === 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="Remark" class="form-label">Remark</label>
                                <textarea class="form-control" name="Remark" id="Remark" rows="2"><?php echo htmlspecialchars($allowance['Remark']); ?></textarea>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Allowance
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
    <script>
        $(document).ready(function() {
            // Date validation
            $('#FromDate, #ToDate').on('change', function() {
                const fromDate = new Date($('#FromDate').val());
                const toDate = new Date($('#ToDate').val());
                
                if (fromDate && toDate && fromDate > toDate) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Date Range',
                        text: 'To Date must be after From Date',
                        timer: 3000,
                        timerProgressBar: true
                    });
                    $('#ToDate').val('');
                }
            });

            // Amount validation
            $('#Amount').on('input', function() {
                const amount = parseFloat($(this).val());
                if (amount <= 0) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // Form validation before submit
            $('#allowanceForm').on('submit', function(e) {
                const amount = parseFloat($('#Amount').val());
                const fromDate = new Date($('#FromDate').val());
                const toDate = new Date($('#ToDate').val());

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