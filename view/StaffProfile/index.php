<?php
    include("../../root/Header.php");
    include("../../Config/conect.php");
?>

<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
<link href="../../style/career.css" rel="stylesheet">

<!-- Add SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
<style>
/* Enhanced Table Styling */
.table {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
    border: none;
}

.table thead th {
    background: linear-gradient(to bottom, #f8f9fa 0%, #e9ecef 100%);
    color: #495057;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    padding: 1rem;
    vertical-align: middle;
    border: none;
    letter-spacing: 0.5px;
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border-top: 1px solid #dee2e6;
    color: #333;
    font-size: 0.9rem;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.02);
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.03);
    transition: all 0.2s ease;
}

/* Card Styling */
.card {
    background: #fff;
    border: none;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,.08);
    margin-bottom: 2rem;
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 0 30px rgba(0,0,0,.12);
}

.card-body {
    padding: 2rem;
}

/* Button Styling */
.btn {
    font-weight: 500;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    padding: 0.6rem 1.2rem;
    transition: all 0.2s ease;
    font-size: 0.85rem;
}

.btn-success {
    background: linear-gradient(45deg, #28a745 0%, #20c997 100%);
    border: none;
    box-shadow: 0 2px 6px rgba(40, 167, 69, 0.2);
}

.btn-success:hover {
    background: linear-gradient(45deg, #218838 0%, #1ba87e 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-sm {
    padding: 0.4rem 0.8rem;
    font-size: 0.785rem;
}

.btn-secondary {
    background: linear-gradient(45deg, #6c757d 0%, #5a6268 100%);
    border: none;
    box-shadow: 0 2px 6px rgba(108, 117, 125, 0.2);
}

.btn-secondary:hover {
    background: linear-gradient(45deg, #5a6268 0%, #4e555b 100%);
    transform: translateY(-1px);
}

.btn-danger {
    background: linear-gradient(45deg, #dc3545 0%, #c82333 100%);
    border: none;
    box-shadow: 0 2px 6px rgba(220, 53, 69, 0.2);
}

.btn-danger:hover {
    background: linear-gradient(45deg, #c82333 0%, #bd2130 100%);
    transform: translateY(-1px);
}

/* DataTables Enhancement */
.dataTables_wrapper .dataTables_length select {
    padding: 0.375rem 1.75rem 0.375rem 0.75rem;
    font-size: 0.9rem;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    background-color: #fff;
    transition: border-color 0.15s ease-in-out;
}

.dataTables_wrapper .dataTables_filter input {
    padding: 0.375rem 0.75rem;
    font-size: 0.9rem;
    line-height: 1.5;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color 0.15s ease-in-out;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.5rem 0.75rem;
    margin: 0 0.2rem;
    border-radius: 0.25rem;
    border: none;
    background: #f8f9fa;
    color: #007bff !important;
    transition: all 0.2s ease;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: linear-gradient(45deg, #007bff 0%, #0056b3 100%);
    border: none;
    color: #fff !important;
    box-shadow: 0 2px 6px rgba(0, 123, 255, 0.2);
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: linear-gradient(45deg, #0056b3 0%, #004085 100%);
    border: none;
    color: #fff !important;
    transform: translateY(-1px);
}

/* Profile Image Enhancement */
.profile-img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,.1);
    transition: all 0.2s ease;
}

.profile-img:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0,0,0,.15);
}

/* Status Badge Enhancement */
.badge {
    padding: 0.5em 1em;
    font-size: 0.75em;
    font-weight: 500;
    letter-spacing: 0.5px;
    border-radius: 30px;
    text-transform: uppercase;
    box-shadow: 0 2px 4px rgba(0,0,0,.05);
}

.badge-active {
    background: linear-gradient(45deg, #28a745 0%, #20c997 100%);
    color: white;
}

.badge-inactive {
    background: linear-gradient(45deg, #dc3545 0%, #c82333 100%);
    color: white;
}

/* Responsive Enhancement */
@media (max-width: 768px) {
    .card-body {
        padding: 1.5rem;
    }
    
    .table thead th {
        padding: 0.75rem;
        font-size: 0.8rem;
    }
    
    .table tbody td {
        padding: 0.75rem;
        font-size: 0.85rem;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }
}

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
.export-dropdown .btn {
    padding: 6px 8px;
}
.vertical-dots {
    font-size: 20px;
    line-height: 0;
}
</style>

<div class="container-fluid mt-3">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <a href="create.php" class="btn btn-success">
                        <i class="fas fa-plus"></i> Add New Staff
                    </a>
                </div>
                <div class="export-dropdown">
                    <div class="dropdown">
                        <button class="btn btn-light border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v vertical-dots"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="../../action/StaffProfile/export_excel.php">
                                    <i class="far fa-file-excel text-success"></i>
                                    Export Excel
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="../../action/StaffProfile/export_pdf.php">
                                    <i class="far fa-file-pdf text-danger"></i>
                                    Export PDF
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <table class="table table-striped" id="staffTable">
                <thead>
                    <tr>
                        <th>Actions</th>
                        <th>Photo</th>
                        <th>Employee Code</th>
                        <th>Full Name</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Division</th>
                        <th>Start Date</th>
                        <th>Status</th>
                        <th>Contact</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT hrstaffprofile.*, 
                    hrcompany.Description as CompanyName,
                    hrdepartment.Description as DepartmentName,
                    hrdivision.Description as DivisionName,
                    hrposition.Description as PositionName,
                    hrlevel.Description as LevelName 
                    FROM hrstaffprofile
                    LEFT JOIN hrcompany ON hrstaffprofile.Company = hrcompany.Code
                    LEFT JOIN hrdepartment ON hrstaffprofile.Department = hrdepartment.Code
                    LEFT JOIN hrdivision ON hrstaffprofile.Division = hrdivision.Code
                    LEFT JOIN hrposition ON hrstaffprofile.Position = hrposition.Code
                    LEFT JOIN hrlevel ON hrstaffprofile.Level = hrlevel.Code
                    ORDER BY EmpCode DESC";
                    $result = $con->query($sql);
                    
                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $photoPath = !empty($row['Photo']) ? "../../" . $row['Photo'] : "../../assets/images/default-profile.jpg";
                            echo "<tr>";
                            echo "<td>
                                    <a href='edit.php?empcode=" . $row['EmpCode'] . "' class='btn btn-sm btn-secondary me-1'><i class='fas fa-edit'></i></a>
                                    <button type='button' class='btn btn-sm btn-danger delete-btn' data-id='" . $row['EmpCode'] . "'><i class='fas fa-trash'></i></button>
                                  </td>";
                            echo "<td><center><img src='" . htmlspecialchars($photoPath) . "' class='profile-img' alt='Profile'></center></td>";
                            echo "<td>" . htmlspecialchars($row['EmpCode']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['EmpName']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['PositionName']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['DepartmentName']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['DivisionName']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['StartDate']) . "</td>";
                            echo "<td><span class='badge " . ($row['Status'] == 'Active' ? 'badge-active' : 'badge-inactive') . "'>" 
                                . htmlspecialchars($row['Status']) . "</span></td>";
                            echo "<td>" . htmlspecialchars($row['Contact']) . "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- DataTables JavaScript -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<!-- Add SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Toast notification
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });

    // Initialize DataTable
    let table = $('#staffTable').DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        order: [[2, 'desc']] // Sort by Employee Code column by default
    });

    // Delete button click handler
    $('.delete-btn').click(function(e) {
        e.preventDefault();
        const button = $(this);
        const row = button.closest('tr');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const id = button.data('id');
                $.ajax({
                    url: "../../action/StaffProfile/delete.php",
                    type: "POST",
                    data: { id: id },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            // Remove row from table
                            table.row(row).remove().draw();
                            Toast.fire({
                                icon: 'success',
                                title: 'Employee deleted successfully'
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message || 'Error deleting employee',
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Delete error:', error);
                        Swal.fire(
                            'Error!',
                            'Error deleting employee: ' + (error || 'Unknown error'),
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Show success message with Toast if URL has success parameter
    <?php if (isset($_GET['success'])): ?>
    Toast.fire({
        icon: 'success',
        title: 'Employee saved successfully!'
    });
    <?php endif; ?>
});
</script>