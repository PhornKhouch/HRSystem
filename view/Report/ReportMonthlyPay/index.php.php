<?php
    include("../../../root/Header.php");
    include("../../../Config/conect.php");
?>

<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
<!-- Add SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
<link href="../../../Style/career.css" rel="stylesheet">

<style>
    :root {
        --primary-color: #6366f1;      /* Modern indigo */
        --secondary-color: #4f46e5;    /* Deeper indigo */
        --success-color: #10b981;      /* Fresh emerald */
        --warning-color: #f59e0b;      /* Warm amber */
        --danger-color: #ef4444;       /* Vibrant red */
        --info-color: #3b82f6;         /* Bright blue */
        --border-color: #e2e8f0;       /* Cool gray */
        --bg-light: #f8fafc;           /* Slate 50 */
        --bg-dark: #1e293b;            /* Slate 800 */
        --text-primary: #0f172a;       /* Slate 900 */
        --text-secondary: #475569;     /* Slate 600 */
        --text-light: #94a3b8;         /* Slate 400 */
        --gradient-start: #818cf8;     /* Indigo 400 */
        --gradient-end: #6366f1;       /* Indigo 500 */
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    }

    .filter-section {
        background: var(--bg-light);
        padding: 1.5rem;
        border-radius: 1rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
    }

    .detail-card {
        background: white;
        border-radius: 1rem;
        box-shadow: var(--shadow-md);
        margin-bottom: 1.5rem;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .detail-card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }

    .detail-header {
        background: var(--bg-light);
        padding: 1.25rem;
        border-bottom: 1px solid var(--border-color);
        border-radius: 1rem 1rem 0 0;
    }

    .detail-header h6 {
        color: var(--text-primary);
        font-weight: 600;
        margin: 0;
        font-size: 1.1rem;
    }

    .detail-body {
        padding: 1.5rem;
    }

    .info-label {
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }



    /* Form Controls */
    .form-select, .form-control {
        border-radius: 0.5rem;
        border: 1px solid var(--border-color);
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        color: var(--text-primary);
        background-color: white;
        transition: all 0.2s ease;
    }

    .form-select:hover, .form-control:hover {
        border-color: var(--text-light);
    }

    .form-select:focus, .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        outline: none;
    }

    /* Button Styles */
    .btn-primary {
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        border: none;
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        color: white;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(99, 102, 241, 0.1);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--gradient-end), var(--gradient-start));
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(99, 102, 241, 0.2);
    }

    .btn-primary:active {
        transform: translateY(0);
        box-shadow: 0 1px 2px rgba(99, 102, 241, 0.2);
    }

    /* Table Styles */
    .table {
        border-radius: 0.5rem;
        overflow: hidden;
        border: 1px solid var(--border-color);
        background: white;
    }

    .table thead {
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
    }

    .table thead th {
        color:purple !important;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        padding: 1.25rem 1rem;
        border-bottom: none;
        vertical-align: middle;
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    }

    .table tbody td {
        padding: 0.5rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-primary);
        font-size: 0.95rem;
    }

    .table tbody tr:hover {
        background-color: var(--bg-light);
    }

    .table tfoot tr {
        background-color: var(--bg-light);
        font-weight: 600;
    }

    .table tfoot th {
        padding: 0.5rem 1rem;
        color: purple
    }

    /* Status Colors */
    .status-active {
        color: var(--success-color);
        background-color: rgba(16, 185, 129, 0.1);
        border-radius: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-weight: 500;
    }

    .status-pending {
        color: var(--warning-color);
        background-color: rgba(245, 158, 11, 0.1);
        border-radius: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-weight: 500;
    }



    /* Loading Spinner */
    #loadingSpinner .spinner-border {
        color: var(--primary-color);
        width: 3rem;
        height: 3rem;
    }
</style>

<div class="container-fluid mt-3">
    <div class="card">
        <div class="card-header">
            <h5 class="card-header-title">
                <i class="fas fa-history"></i>
                Employee InOut Report
            </h5>
        </div>
        <div class="card-body">
            <div class="filter-section">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="startDate" class="form-label">Start Date:</label>
                        <input type="date" id="startDate" class="form-control" value="<?php echo date('Y-m-01'); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="endDate" class="form-label">End Date:</label>
                        <input type="date" id="endDate" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status:</label>
                        <select id="status" class="form-select">
                            <option value="all">All</option>
                            <option value="NEW">New Join</option>
                            <option value="resign">Resigned</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="department" class="form-label">Department:</label>
                        <select id="department" class="form-select">
                            <option value="all">All Departments</option>
                            <?php
                                $sql = "SELECT Code, Description FROM hrdepartment WHERE Status = 'Active' ORDER BY Description";
                                $result = mysqli_query($con, $sql);
                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='" . htmlspecialchars($row['Code']) . "'>" . htmlspecialchars($row['Description']) . "</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button id="viewReport" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Generate Report
                        </button>
                    </div>
                </div>
            </div>

            <!-- Report Table Section -->
            <div class="detail-card mt-4">
                <div class="detail-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Employee Movement Report</h6>
                </div>
                <div class="detail-body">
                    <table id="employeeTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Employee Code</th>
                                <th>Employee Name</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated by DataTable -->
                        </tbody>
                    </table>
                </div>
            </div>



          
        </div>
    </div>
</div>

<!-- Loading Spinner Template -->
<!-- <div id="loadingSpinner" class="text-center" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div> -->

<!-- Moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

<!-- DataTables JavaScript -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<!-- Add SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>


<script>
$(document).ready(function() {
    // Initialize DataTable
    var employeeTable = $('#employeeTable').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        pageLength: 25,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel me-2"></i>Export to Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5] // Exclude actions column
                },
                filename: function() {
                    return 'Employee_Movement_Report_' + moment().format('YYYY-MM-DD');
                },
                title: 'Employee Movement Report',
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('row c[r^="F"]', sheet).each(function() {
                        if($(this).text()) {
                            $(this).attr('s', '2');
                        }
                    });
                }
            }
        ],
        ajax: {
            url: '../../../action/Report/fetch_employee_movement.php',
            type: 'POST',
            data: function(d) {
                d.startDate = $('#startDate').val();
                d.endDate = $('#endDate').val();
                d.status = $('#status').val();
                d.department = $('#department').val();
            }
        },
        columns: [
            { data: 'EmployeeID' },
            { data: 'EmpName' },
            { data: 'Department' },
            { data: 'Position' },
            { 
                data: 'Status',
                render: function(data, type, row) {
                    let statusClass, statusText;
                    switch(data.toLowerCase()) {
                        case 'new':
                        case 'join':
                            statusClass = 'success';
                            statusText = 'New Join';
                            break;
                        case 'resign':
                            statusClass = 'danger';
                            statusText = 'Resigned';
                            break;
                        default:
                            statusClass = 'secondary';
                            statusText = data;
                    }
                    return `<span class="badge bg-${statusClass}">${statusText}</span>`;
                }
            },
            { 
                data: 'Date',
                render: function(data, type, row) {
                    if (row.Status.toLowerCase() === 'new' && !data) {
                        return '-';
                    }
                    return data ? moment(data).format('DD MMM YYYY') : '-';
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `<button class="btn btn-info btn-sm view-details" data-id="${row.EmployeeID}">
                                <i class="fas fa-info-circle"></i>
                            </button>`;
                }
            }
        ],
        order: [[5, 'desc']]
    });

    $('#viewReport').on('click', function() {
        employeeTable.ajax.reload();
    });

    // Handle view details button click
    $('#employeeTable').on('click', '.view-details', function() {
        var empCode = $(this).data('id');
        showEmployeeHistory(empCode);
    });

    function showEmployeeHistory(empCode) {
        $.ajax({
            url: '../../../action/Report/fetch_employee_history.php',
            type: 'POST',
            data: { 
                empCode: empCode,
                startDate: $('#startDate').val(),
                endDate: $('#endDate').val(),
                department: $('#department').val(),
                status: $('#status').val()
            },
            success: function(response) {
                if (response.status === 'success') {
                    showHistoryModal(response.data);
                } else {
                    showError(response.message);
                }
            },
            error: function() {
                showError('Failed to fetch employee history');
            }
        });
    }

    function showHistoryModal(data) {
        let historyHtml = '';
        data.history.forEach(function(item) {
            historyHtml += `
                <tr>
                    <td>${moment(item.EffectiveDate).format('DD MMM YYYY')}</td>
                    <td>${item.Department}</td>
                    <td>${item.Position}</td>
                    <td>${item.Status}</td>
                    <td>${item.Remarks || '-'}</td>
                </tr>`;
        });

        Swal.fire({
            title: `Career History - ${data.employee.EmpName}`,
            html: `
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${historyHtml}
                    </tbody>
                </table>`,
            width: '800px',
            confirmButtonText: 'Close'
        });
    }

    function showError(message, title = 'Error') {
        Swal.fire({
            icon: 'error',
            title: title,
            text: message,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Close'
        });
    }


});
</script>
