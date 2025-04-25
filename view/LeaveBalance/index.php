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
    <div class="card">
        <div class="card-header">
            <h5 class="card-header-title">
                <i class="fas fa-calendar-check"></i>
                Leave Balance Management
            </h5>
        </div>  
        <div class="card-body">
            <div class="action-bar">
                <div class="d-flex align-items-center gap-3">
                    <div class="custom-select-wrapper">
                        <select id="entitleYear" class="form-select custom-select">
                            <?php 
                                $currentYear = date("Y");
                                for ($year = $currentYear - 2; $year <= $currentYear + 1; $year++) {
                                    echo '<option value="' . $year . '" ' . ($year === $currentYear ? 'selected' : '') . '>' . $year . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <button id="generateEntitle" class="generate-btn">
                        <i class="fas fa-sync"></i>
                        <span>Generate Leave Entitle</span>
                    </button>
                </div>
            </div>
            <table class="table table-bordered" id="LeaveTable">
                <thead>
                    <tr>
                        <th>EmpCode</th>
                        <th>Leave Type</th>
                        <th>Balance</th>
                        <th>Entitle</th>
                        <th>Current Balance</th>
                        <th>Taken</th>
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
    var leaveTable = $('#LeaveTable').DataTable({
        responsive: true,
        processing: true,
        serverSide: true, // Changed to true to enable server-side processing
        pageLength: 25, // This is already set but making sure it's here
        ajax: {
            url: '../../action/LeaveBalance/fetch.php',
            type: 'POST',
            dataSrc: function(json) {
                if (json.error) {
                    console.error('Server Error:', json.error);
                    return [];
                }
                return json.data || [];
            },
            error: function (xhr, error, thrown) {
                console.error('DataTables error:', error);
                console.error('Server response:', xhr.responseText);
                Swal.fire(
                    'Error!',
                    'Failed to load leave balance data. Please try refreshing the page.',
                    'error'
                );
            }
        },
        columns: [
            { data: 'emp_code' },
            { data: 'leave_type' },
            { 
                data: 'balance',
                render: function(data) {
                    return data ? parseFloat(data).toFixed(1) : '0.0';
                }
            },
            { 
                data: 'entitle',
                render: function(data) {
                    return data ? parseFloat(data).toFixed(1) : '0.0';
                }
            },
            { 
                data: 'current_balance',
                render: function(data) {
                    return data ? parseFloat(data).toFixed(1) : '0.0';
                }
            },
            { 
                data: 'taken',
                render: function(data) {
                    return data ? parseFloat(data).toFixed(1) : '0.0';
                }
            }
        ],
        order: [[1, 'asc']], // Sort by EmpCode by default
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
            emptyTable: 'No leave balance records found',
            zeroRecords: 'No matching records found'
        },
        initComplete: function() {
            console.log('DataTable initialization complete');
        }
    });

    // Handle Generate Leave Entitle button click
    $('#generateEntitle').on('click', function() {
        const year = $('#entitleYear').val();
        
        Swal.fire({
            title: 'Generate Leave Entitlements',
            text: `Are you sure you want to generate leave entitlements for year ${year}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, generate',
            cancelButtonText: 'No, cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Generating...',
                    text: 'Please wait while generating leave entitlements',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send AJAX request
                $.ajax({
                    url: '../../action/LeaveBalance/generate_entitle.php',
                    type: 'POST',
                    data: { year: year },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                // Refresh the DataTable
                                leaveTable.ajax.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        console.error('Server response:', xhr.responseText);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to generate leave entitlements. Please try again.',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });

});
</script>