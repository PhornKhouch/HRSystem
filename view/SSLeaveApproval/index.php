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
                <i class="fas fa-calendar-check text-primary"></i> Leave Approval Management
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
                <div class="col-md-3">
                    <label for="status" class="form-label">Status:</label>
                    <select id="status" class="form-select">
                        <option value="ALL">ALL</option>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
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
                    <div class="table-responsive">
                        <table id="approvedTable" class="table table-bordered table-striped w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Employee Code</th>
                                    <th>Employee Name</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Level</th>
                                    <th>Units</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Leave Type</th>
                                    <th>No. Days</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <!-- Waiting List Tab -->
                <div class="tab-pane fade" id="waiting" role="tabpanel">
                    <div class="table-responsive">
                        <table id="pendingTable" class="table table-bordered table-striped w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Employee Code</th>
                                    <th>Employee Name</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Level</th>
                                    <th>Units</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Leave Type</th>
                                    <th>No. Days</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
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
}
.nav-tabs .nav-link {
    border: none;
    border-radius: 0;
    margin-bottom: 0;
    color: #6c757d;
}
.nav-tabs .nav-link:hover {
    color: #495057;
    background-color: #f8f9fa;
}
.nav-tabs .nav-link.active {
    color: #0d6efd;
    background-color: transparent;
    border-bottom: 2px solid #0d6efd;
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
    // Common DataTable configuration
    const commonConfig = {
        responsive: true,
        processing: true,
        serverSide: true,
        pageLength: 10,
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
            emptyTable: 'No records found',
            zeroRecords: 'No matching records found'
        },
        order: [[6, 'desc']], // Sort by From Date by default
        columnDefs: [
            {
                targets: [5], // Units column
                orderable: false
            },
            {
                targets: [10], // Actions column
                orderable: false,
                searchable: false
            }
        ],
        ajax: {
            url: '../../action/SSLeaveApproval/fetch.php',
            type: 'POST',
            data: function(d) {
                return {
                    ...d,
                    year: $('#inYear').val(),
                    status: $('#status').val()
                };
            },
            error: function(xhr, error, thrown) {
                console.error('DataTables error:', error, thrown);
                console.log('XHR response:', xhr.responseText);
                Swal.fire('Error!', 'Failed to load data. Please try again.', 'error');
            }
        }
    };

    // Initialize Approved Table
    const approvedTable = $('#approvedTable').DataTable({
        ...commonConfig,
        ajax: {
            ...commonConfig.ajax,
            data: function(d) {
                return {
                    ...d,
                    status: 'Approved',
                    year: $('#inYear').val()
                };
            }
        },
        columns: [
            { data: 'emp_code', name: 'lr.EmpCode' },
            { data: 'emp_name', name: 'sp.EmpName' },
            { data: 'department', name: 'sp.Department' },
            { data: 'position', name: 'sp.Position' },
            { data: 'level', name: 'sp.Level' },
            { data: 'units' },
            { data: 'from_date', name: 'lr.FromDate' },
            { data: 'to_date', name: 'lr.ToDate' },
            { data: 'leave_type', name: 'lr.LeaveType' },
            { data: 'no_days', name: 'lr.LeaveDay' },
            { 
                data: null,
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-sm btn-info view-btn" data-id="${row.id}" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                    `;
                }
            }
        ]
    });

    // Initialize Pending Table
    const pendingTable = $('#pendingTable').DataTable({
        ...commonConfig,
        ajax: {
            ...commonConfig.ajax,
            data: function(d) {
                return {
                    ...d,
                    status: 'Pending',
                    year: $('#inYear').val()
                };
            }
        },
        columns: [
            { data: 'emp_code', name: 'lr.EmpCode' },
            { data: 'emp_name', name: 'sp.EmpName' },
            { data: 'department', name: 'sp.Department' },
            { data: 'position', name: 'sp.Position' },
            { data: 'level', name: 'sp.Level' },
            { data: 'units' },
            { data: 'from_date', name: 'lr.FromDate' },
            { data: 'to_date', name: 'lr.ToDate' },
            { data: 'leave_type', name: 'lr.LeaveType' },
            { data: 'no_days', name: 'lr.LeaveDay' },
            { 
                data: null,
                render: function(data, type, row) {
                    return `
                        <div class="btn-group">
                            <button class="btn btn-sm btn-success approve-btn" data-id="${row.id}" title="Approve">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-sm btn-danger reject-btn" data-id="${row.id}" title="Reject">
                                <i class="fas fa-times"></i>
                            </button>
                            <button class="btn btn-sm btn-info view-btn" data-id="${row.id}" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ]
    });

    // Handle Go button click
    $('#btnGo').click(function() {
        approvedTable.ajax.reload();
        pendingTable.ajax.reload();
    });

    // Handle tab change to refresh the active table
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        const targetId = $(e.target).attr('href');
        if (targetId === '#list') {
            approvedTable.ajax.reload();
        } else {
            pendingTable.ajax.reload();
        }
    });

    // Handle Approve button click
    $(document).on('click', '.approve-btn', function() {
        const leaveId = $(this).data('id');
        Swal.fire({
            title: 'Approve Leave?',
            text: 'Are you sure you want to approve this leave request?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, approve',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../../action/SSLeaveApproval/approve.php',
                    type: 'POST',
                    data: { id: leaveId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire('Success!', 'Leave request approved.', 'success');
                            approvedTable.ajax.reload();
                            pendingTable.ajax.reload();
                        } else {
                            Swal.fire('Error!', response.message || 'Failed to approve leave request.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Failed to approve leave request.', 'error');
                    }
                });
            }
        });
    });

    // Handle Reject button click
    $(document).on('click', '.reject-btn', function() {
        const leaveId = $(this).data('id');
        Swal.fire({
            title: 'Reject Leave?',
            text: 'Are you sure you want to reject this leave request?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, reject',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../../action/SSLeaveApproval/reject.php',
                    type: 'POST',
                    data: { id: leaveId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire('Success!', 'Leave request rejected.', 'success');
                            pendingTable.ajax.reload();
                        } else {
                            Swal.fire('Error!', response.message || 'Failed to reject leave request.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Failed to reject leave request.', 'error');
                    }
                });
            }
        });
    });

    // Handle View button click
    $(document).on('click', '.view-btn', function() {
        const leaveId = $(this).data('id');
        $.ajax({
            url: '../../action/SSLeaveApproval/view.php',
            type: 'GET',
            data: { id: leaveId },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: 'Leave Request Details',
                        html: `
                            <div class="text-start">
                                <p><strong>Employee:</strong> ${response.data.emp_name}</p>
                                <p><strong>Leave Type:</strong> ${response.data.leave_type}</p>
                                <p><strong>From:</strong> ${response.data.from_date}</p>
                                <p><strong>To:</strong> ${response.data.to_date}</p>
                                <p><strong>Days:</strong> ${response.data.no_days}</p>
                                <p><strong>Reason:</strong> ${response.data.reason}</p>
                            </div>
                        `,
                        width: '500px'
                    });
                } else {
                    Swal.fire('Error!', response.message || 'Failed to load leave details.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error!', 'Failed to load leave details.', 'error');
            }
        });
    });
});
</script>