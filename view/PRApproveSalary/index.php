<?php
    include("../../root/Header.php");
    include("../../Config/conect.php");
?>

<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
<!-- Add SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
<link href="../../Style/leave.css" rel="stylesheet">

<div class="container-fluid mt-3">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-money-check-alt text-primary"></i> Salary Approval Management
            </h4>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label for="inYear" class="form-label">In Year:</label>
                    <select id="inYear" class="form-select">
                        <?php 
                            $currentYear = date("Y");
                            for ($year = $currentYear; $year <= $currentYear + 1; $year++) {
                                echo '<option value="' . $year . '" ' . ($year === $currentYear ? 'selected' : '') . '>' . $year . '</option>';
                            }
                        ?>
                    </select>
                </div>
            
                <div class="col-md-2">
                    <button id="btnGo" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header bg-white p-0">
            <ul class="nav nav-tabs" id="leaveTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active px-4 py-3" id="list-tab" data-bs-toggle="tab" data-bs-target="#list" type="button" role="tab">
                        <i class="fas fa-check-circle text-success me-1"></i> Approved List
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-3" id="waiting-tab" data-bs-toggle="tab" data-bs-target="#waiting" type="button" role="tab">
                        <i class="fas fa-clock text-warning me-1"></i> Pending List
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="leaveTabContent">
                <!-- List Tab -->
                <div class="tab-pane fade show active" id="list" role="tabpanel">
                    <?php include('TabApproveList.php'); ?>
                </div>

                <!-- Waiting List Tab -->
                <div class="tab-pane fade" id="waiting" role="tabpanel">
                    <?php include('TabPendinglist.php'); ?>
                </div>
            </div>
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

<style>
/* Custom tab styling */
.nav-tabs {
    border-bottom: 0;
    gap: 0.5rem;
    padding: 1rem 1rem 0;
}
.nav-tabs .nav-link {
    border: none;
    border-radius: 0.5rem;
    margin-bottom: 0;
    color: #6c757d;
    padding: 0.75rem 1.5rem;
    transition: all 0.2s;
}
.nav-tabs .nav-link:hover {
    color: #495057;
    background-color: #f8f9fa;
}
.nav-tabs .nav-link.active {
    color: #fff;
    background-color: #0d6efd;
    border: none;
}

/* Status badge styling */
.badge {
    padding: 0.5rem 0.75rem;
    font-weight: 500;
    font-size: 0.875rem;
    border-radius: 0.375rem;
}
.badge.bg-warning {
    background-color: #fff3cd !important;
    color: #856404;
}
.badge.bg-success {
    background-color: #d4edda !important;
    color: #155724;
}

/* Table styling */
.table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0,0,0,.02);
}

/* Button styling */
.btn-group .btn {
    margin: 0 2px;
}
.btn-group .btn i {
    margin-right: 0;
}

/* Card styling */
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
}
</style>

<script>
 $(document).ready(function() {
            
            // Check for success/error messages
            const urlParams = new URLSearchParams(window.location.search);
            const successMsg = urlParams.get('success');
            const errorMsg = urlParams.get('error');
            
            if (successMsg) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: decodeURIComponent(successMsg),
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            } else if (errorMsg) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: decodeURIComponent(errorMsg),
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            }
        });
</script>
