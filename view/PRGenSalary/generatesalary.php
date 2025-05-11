<?php
    include("../../root/Header.php");
    include("../../Config/conect.php");
?>

<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
<!-- Add SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
<link href="../../Style/career.css" rel="stylesheet">

<style>
    .filter-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .generate-btn {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
    }
    .generate-btn:hover {
        background-color: #218838;
    }
</style>


<div class="container-fluid mt-3">
    <div class="card">
        <div class="card-header">
            <h5 class="card-header-title">
                <i class="fas fa-money-bill-wave"></i>
                Generate Salary
            </h5>
        </div>
        <div class="card-body">
            <div class="filter-section">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="month" class="form-label">Month:</label>
                        <select id="month" class="form-select">
                            <?php
                                $currentMonth = date('Y-m');
                                for($i = 0; $i < 12; $i++) {
                                    $month = date('Y-m', strtotime($currentMonth . " -$i months"));
                                    echo "<option value='$month'>" . date('F Y', strtotime($month)) . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3 d-flex align-items-end">
                        <button id="Go" class="generate-btn w-100">
                            <i class="fas fa-sync me-2"></i>Go
                        </button>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button id="generateSalary" class="generate-btn w-100">
                            <i class="fas fa-sync me-2"></i>Generate
                        </button>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped" id="salaryTable">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="select-all" class="form-check-input">
                        </th>
                        <th>Employee Code</th>
                        <th>Employee Name</th>
                        <th>Start Date</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Division</th>
                        <th>Company</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    
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

<script>
$(document).ready(function() {
   

    // Initialize DataTable
    var salaryTable = $('#salaryTable').DataTable({
        responsive: true,
        processing: true,
        serverSide: false, // Changed to false for simpler implementation
        pageLength: 25,
        ajax: {
            url: '../../action/PRGenSalary/fetch.php',
            type: 'POST',
            data: function(d) {
                return {
                    month: $('#month').val()
                };
            },
            dataSrc: function(json) {
                if (typeof json === 'string') {
                    try {
                        json = JSON.parse(json);
                    } catch (e) {
                        console.error('Invalid JSON:', e);
                        return [];
                    }
                }
                
                if (json.error) {
                    Swal.fire({
                        title: 'Error!',
                        text: json.error,
                        icon: 'error'
                    });
                    return [];
                }
                
                return json.data || [];
            },
            error: function(xhr, error, thrown) {
                console.error('DataTables error:', error);
                console.error('Server response:', xhr.responseText);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to load salary data. Please try refreshing the page.',
                    icon: 'error'
                });
            }
        },
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
            emptyTable: 'No salary records found',
            zeroRecords: 'No matching records found'
        },
        columns: [
            { 
                data: null,
                orderable: false,
                className: 'text-center',
                render: function (data, type, row) {
                    return '<input type="checkbox" class="form-check-input staff-select" value="' + row.EmpCode + '">';
                }
            },
            { data: 'EmpCode', title: 'Employee Code' },
            { data: 'EmpName', title: 'Employee Name' },
            { 
                data: 'StartDate',
                title: 'Start Date',
                render: function(data) {
                    return data ? new Date(data).toLocaleDateString() : '';
                }
            },
            { data: 'Department', title: 'Department' },
            { data: 'Position', title: 'Position' },
            { data: 'Division', title: 'Division' },
            { data: 'Company', title: 'Company' },
            { 
                data: null,
                title: 'Status',
                render: function (data, type, row) {
                    return `<div class="salary-status" data-empcode="${row.EmpCode}" data-month="${$('#month').val()}">
                        <i class="fas fa-spinner fa-spin"></i> Checking...
                    </div>`;
                }
            }
        ],
        order: [[1, 'asc']],
        drawCallback: function() {
            var api = this.api();
            
            // Check salary generation status for each employee
            $('.salary-status').each(function() {
                const statusDiv = $(this);
                const empCode = statusDiv.data('empcode');
                const month = statusDiv.data('month');
                
                $.ajax({
                    url: '../../action/PRGenSalary/check_salary_status.php',
                    type: 'POST',
                    data: { empCode: empCode, month: month },
                    success: function(response) {
                        try {
                            const data = typeof response === 'string' ? JSON.parse(response) : response;
                            if (data.exists) {
                                statusDiv.html('<span class="badge bg-success">Completed</span>');
                            } else {
                                statusDiv.html('<span class="badge bg-warning">Pending</span>');
                            }
                        } catch (e) {
                            statusDiv.html('<span class="badge bg-danger">Error</span>');
                        }
                    },
                    error: function() {
                        statusDiv.html('<span class="badge bg-danger">Error</span>');
                    }
                });
            });
        }
    });

    // Handle Go button click
    $('#Go').on('click', function() {
        const month = $('#month').val();
        if (!month) {
            Swal.fire({
                title: 'Error!',
                text: 'Please select a month first',
                icon: 'error'
            });
            return;
        }
        salaryTable.ajax.reload();
    });

    // Handle select all checkbox
    $('#select-all').on('change', function() {
        $('.staff-select').prop('checked', $(this).prop('checked'));
    });

    // Handle Generate Salary button click
    $('#generateSalary').on('click', function() {
        const month = $('#month').val();
        const selectedStaff = [];

        $('.staff-select:checked').each(function() {
            selectedStaff.push($(this).val());
        });

        if (!month) {
            Swal.fire({
                title: 'Error!',
                text: 'Please select a month first',
                icon: 'error'
            });
            return;
        }

        if (selectedStaff.length === 0) {
            Swal.fire({
                title: 'Error!',
                text: 'Please select at least one staff member',
                icon: 'error'
            });
            return;
        }

        Swal.fire({
            title: 'Generate Salary',
            text: `Are you sure you want to generate salary for ${month}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, generate',
            cancelButtonText: 'No, cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Generating...',
                    text: 'Please wait while generating salary',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '../../action/PRGenSalary/generate.php',
                    type: 'POST',
                    data: {
                        month: month,
                        selectedStaff: selectedStaff
                    },
                    success: function(response) {
                        try {
                            const data = typeof response === 'string' ? JSON.parse(response) : response;
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: 'Success!',
                                    text: data.message,
                                    icon: 'success'
                                }).then(() => {
                                    salaryTable.ajax.reload();
                                });
                            } else {
                                let errorDetails = '';
                                if (data.warnings && data.warnings.length > 0) {
                                    errorDetails = '<br><br>Details:<br>' + data.warnings.join('<br>');
                                }
                                Swal.fire({
                                    title: 'Error!',
                                    html: data.message + errorDetails,
                                    icon: 'error'
                                });
                            }
                        } catch (e) {
                            console.error('JSON Parse Error:', e);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Invalid response from server',
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Failed to generate salary.';
                        try {
                            const response = JSON.parse(xhr.responseText);
                            errorMessage = response.message || errorMessage;
                        } catch (e) {
                            errorMessage += ' Error: ' + error;
                        }
                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error',
                            html: xhr.responseText ? `<div class="text-start">Details:<br>${xhr.responseText}</div>` : undefined
                        });
                        console.error('Salary Generation Error:', {
                            status: status,
                            error: error,
                            response: xhr.responseText
                        });
                    }
                });
            }
        });
    });

    // Handle filter changes
    $('.form-select').on('change', function() {
        salaryTable.ajax.reload();
    });
});
</script>