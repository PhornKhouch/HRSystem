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
        order: [[0, 'desc']], // Sort by month by default
        columnDefs: [
            {
                targets: [10], // Actions column
                orderable: false,
                searchable: false
            }
        ],
        ajax: {
            url: '../../action/PRApproveSalary/fetch.php',
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
        columnDefs: [{
            targets: '_all',
            orderable: true
        }],
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
            { data: 'in_month' },
            { data: 'total_salary' },
            { data: 'total_allowance' },
            { data: 'total_ot' },
            { data: 'total_bonus' },
            { data: 'total_deduction' },
            { data: 'total_gross' },
            { data: 'net_salary' },
            { 
                data: 'status',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return '<span class="badge bg-success">Approved</span>';
                    }
                    return data;
                }
            },
            { data: 'remark' }
        ]
    });

    // Initialize Pending Table
    const pendingTable = $('#pendingTable').DataTable({
        ...commonConfig,
        columnDefs: [{
            targets: [10],
            orderable: false,
            searchable: false
        }],
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
            { data: 'in_month' },
            { data: 'total_salary' },
            { data: 'total_allowance' },
            { data: 'total_ot' },
            { data: 'total_bonus' },
            { data: 'total_deduction' },
            { data: 'total_gross' },
            { data: 'net_salary' },
            { 
                data: 'status',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return '<span class="badge bg-warning">Pending</span>';
                    }
                    return data;
                }
            },
            { data: 'remark' },
            { 
                data: null,
                defaultContent: '',
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <div class="btn-group">
                            <button class="btn btn-success btn-sm approve-btn" data-id="${row.id}">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button class="btn btn-danger btn-sm reject-btn" data-id="${row.id}">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </div>
                    `;
                }
            }
        ]
    });

    // Handle Go button click
    $('#btnGo').on('click', function() {
        approvedTable.ajax.reload();
        pendingTable.ajax.reload();
    });

    // Handle year change
    $('#inYear').on('change', function() {
        approvedTable.ajax.reload();
        pendingTable.ajax.reload();
    });

    // Handle approve button click
    $('#pendingTable').on('click', '.approve-btn', function() {
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Approve Salary?',
            text: 'Are you sure you want to approve this salary?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show input for remarks
                Swal.fire({
                    title: 'Enter Remarks',
                    input: 'text',
                    inputPlaceholder: 'Enter remarks (optional)',
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    showLoaderOnConfirm: true,
                    preConfirm: (remark) => {
                        return new Promise((resolve, reject) => {
                            $.ajax({
                                url: '../../action/PRApproveSalary/approve.php',
                                type: 'POST',
                                data: {
                                    id: id,
                                    remark: remark || ''
                                },
                                success: function(response) {
                                    if (response.status === 'error') {
                                        reject(new Error(response.message));
                                    } else {
                                        resolve(response);
                                    }
                                },
                                error: function(xhr) {
                                    reject(new Error('Failed to process request'));
                                }
                            });
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        Swal.fire({
                            title: 'Success!',
                            text: result.value.message,
                            icon: 'success'
                        }).then(() => {
                            // Reload both tables
                            pendingTable.ajax.reload();
                            approvedTable.ajax.reload();
                        });
                    }
                }).catch(error => {
                    Swal.fire('Error!', error.message, 'error');
                });
            }
        });
    });
});
