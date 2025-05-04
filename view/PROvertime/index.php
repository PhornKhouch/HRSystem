<?php
include("../../Config/conect.php");
session_start();

// Fetch overtime data
$sql = "SELECT ot.*, sp.EmpName 
        FROM provertime ot 
        LEFT JOIN hrstaffprofile sp ON ot.Empcode = sp.EmpCode 
        ORDER BY ot.Provtime DESC";
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overtime Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../../style/career.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-4 mb-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Overtime List</h4>
                            <a href="create.php" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>New Overtime
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="overtimeTable" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Employee Code</th>
                                    <th>Name</th>
                                    <th>OT Type</th>
                                    <th>OT Date</th>
                                    <th>From Time</th>
                                    <th>To Time</th>
                                    <th>Hours</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="action-buttons">
                                        <a href="edit.php?id=<?php echo urlencode($row['ID']); ?>" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger delete-btn" 
                                                data-id="<?php echo htmlspecialchars($row['ID']); ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['Empcode']); ?></td>
                                    <td><?php echo htmlspecialchars($row['EmpName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['OTType']); ?></td>
                                    <td><?php echo date('d M Y', strtotime($row['OTDate'])); ?></td>
                                    <td><?php echo date('H:i', strtotime($row['FromTime'])); ?></td>
                                    <td><?php echo date('H:i', strtotime($row['ToTime'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['hour']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Reason'] ?? '-'); ?></td>
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
            $('#overtimeTable').DataTable({
                pageLength: 10,
                order: [[4, 'desc']], // Sort by OT Date by default
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
                        window.location.href = `../../action/PROvertime/delete.php?id=${encodeURIComponent(id)}`;
                    }
                });
            });
        });
    </script>
</body>
</html>