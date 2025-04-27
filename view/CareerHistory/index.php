<?php
include("../../Config/conect.php");
session_start();

// Fetch career history data
$sql = "SELECT ch.*, sp.EmpName 
        FROM careerhistory ch 
        LEFT JOIN hrstaffprofile sp ON ch.EmployeeID = sp.EmpCode 
        ORDER BY ch.CreatedAt DESC";
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career History</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Career History CSS -->
    <link href="../../style/career.css" rel="stylesheet">

    <style>
    .dropdown-menu {
        min-width: 120px;
    }
    .dropdown-item {
        padding: 8px 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .dropdown-item i {
        width: 16px;
    }
    </style>
</head>
<body>
    <div class="container-fluid mt-4 mb-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Career History List</h4>
                            <div class="d-flex gap-2">
                                <a href="create.php" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Create New
                                </a>
                                <!-- export -->
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Export
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="../../action/CareerHistory/export_excel.php">
                                                <i class="far fa-file-excel text-success"></i>
                                                Export Excel
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="../../action/CareerHistory/export_pdf.php">
                                                <i class="far fa-file-pdf text-danger"></i>
                                                Export PDF
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="careerHistoryTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Career</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Department</th>
                                    <th>Effective Date</th>
                                    <th>Resignation Date</th>
                                    <th>Remark</th>
                                    <th>Increase</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="action-buttons">
                                        <a href="edit.php?id=<?php echo urlencode($row['ID']); ?>" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit me-1"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger delete-btn" 
                                                data-id="<?php echo htmlspecialchars($row['ID']); ?>">
                                            <i class="fas fa-trash-alt me-1"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <?php 
                                            $careerCode = strtolower($row['CareerHistoryType']);
                                            echo "<span class='career-code {$careerCode}'>" . htmlspecialchars($row['CareerHistoryType']) . "</span>";
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['EmployeeID']); ?></td>
                                    <td><?php echo htmlspecialchars($row['EmpName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['PositionTitle']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Department']); ?></td>
                                    <td><?php echo date('d M Y', strtotime($row['StartDate'])); ?></td>
                                    <td><?php echo $row['EndDate'] ? date('d M Y', strtotime($row['EndDate'])) : '-'; ?></td>
                                    <td><?php echo htmlspecialchars($row['Remark'] ?? '-'); ?></td>
                                    <td><?php echo $row['Increase'] ? number_format($row['Increase'], 2) : '-'; ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable with custom options
            $('#careerHistoryTable').DataTable({
                pageLength: 10,
                order: [[6, 'desc']], // Sort by Start Date by default
                responsive: true,
                language: {
                    search: "<i class='fas fa-search'></i> Search:",
                    lengthMenu: "_MENU_ records per page",
                },
                columnDefs: [
                    { orderable: false, targets: 0 }, // Disable sorting on action column
                ]
            });

            // Check for success message
            const urlParams = new URLSearchParams(window.location.search);
            const successMsg = urlParams.get('success');
            if (successMsg) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: decodeURIComponent(successMsg),
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            }

            // Handle delete button click
            $('.delete-btn').click(function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--danger-color)',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash-alt me-2"></i>Yes, delete it!',
                    cancelButtonText: '<i class="fas fa-times me-2"></i>Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `../../action/CareerHistory/delete.php?id=${encodeURIComponent(id)}`;
                    }
                });
            });
        });
    </script>
</body>
</html>