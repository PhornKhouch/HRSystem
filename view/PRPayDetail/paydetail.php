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

    .salary-amount {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--success-color);
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
        padding: 1.25rem 1rem;
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
        padding: 1.25rem 1rem;
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

    /* Modal Styles */
    .salary-detail-modal .swal2-popup {
        padding: 2rem !important;
        border-radius: 1rem !important;
        width: 800px !important;
        box-shadow: var(--shadow-lg) !important;
    }

    .salary-detail-modal .swal2-title {
        color: var(--primary-color) !important;
        font-size: 1.5rem !important;
        font-weight: 600 !important;
        border-bottom: 2px solid var(--border-color);
        padding-bottom: 1rem;
        margin-bottom: 1.5rem !important;
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
                Salary History Details
            </h5>
        </div>
        <div class="card-body">
            <div class="filter-section">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="employee" class="form-label">Select Employee:</label>
                        <select id="employee" class="form-select">
                            <option value="">Select Employee</option>
                            <?php
                                $sql = "SELECT EmpCode, EmpName FROM hrstaffprofile WHERE Status = 'Active' ORDER BY EmpName";
                                $result = mysqli_query($con, $sql);
                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='" . htmlspecialchars($row['EmpCode']) . "'>" . htmlspecialchars($row['EmpName']) . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No employees found</option>";
                                }
                            ?>
                        </select>
                    </div>
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
                    <div class="col-md-2 d-flex align-items-end">
                        <button id="viewDetails" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>View Details
                        </button>
                    </div>
                </div>
            </div>

            <!-- Employee Details Section -->
            <div id="employeeDetails" class="detail-card" style="display: none;">
                <div class="detail-header">
                    <h6 class="mb-0">Employee Information</h6>
                </div>
                <div class="detail-body">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="info-label">Employee Code:</p>
                            <p id="empCode"></p>
                        </div>
                        <div class="col-md-3">
                            <p class="info-label">Employee Name:</p>
                            <p id="empName"></p>
                        </div>
                        <div class="col-md-3">
                            <p class="info-label">Department:</p>
                            <p id="department"></p>
                        </div>
                        <div class="col-md-3">
                            <p class="info-label">Position:</p>
                            <p id="position"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Salary Details Section -->
            <div id="salaryDetails" class="detail-card" style="display: none;">
                <div class="detail-header">
                    <h6 class="mb-0">Salary Components</h6>
                </div>
                <div class="detail-body">
                    <table class="table table-bordered" id="salaryTable">
                        <thead>
                            <tr>
                                <th>Component</th>
                                <th>Description</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Salary details will be populated here -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-end">Total Net Pay:</th>
                                <th id="totalNetPay" class="salary-amount"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

          
        </div>
    </div>
</div>

<!-- Loading Spinner Template -->
<div id="loadingSpinner" class="text-center" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
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
    // Initialize DataTable for history
    var historyTable = $('#historyTable').DataTable({
        responsive: true,
        processing: true,
        pageLength: 12,
        order: [[0, 'desc']],
        columnDefs: [
            {
                targets: [1, 2, 3, 4],
                className: 'text-end',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return parseFloat(data).toFixed(2);
                    }
                    return data;
                }
            },
            {
                targets: 5,
                className: 'text-center'
            },
            {
                targets: 6,
                className: 'text-center',
                orderable: false
            }
        ]
    });

    function showLoading() {
        $('#loadingSpinner').show();
        $('#employeeDetails, #salaryDetails').hide();
        $('#errorAlert').hide();
    }

    function hideLoading() {
        $('#loadingSpinner').hide();
    }    function showError(message, title = 'Error') {
        Swal.fire({
            icon: 'error',
            title: title,
            text: message,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Close',
            customClass: {
                popup: 'salary-detail-modal'
            }
        });
    }

    $('#viewDetails').on('click', function() {
        fetchSalaryDetails();
    });

    // Add change event handlers for employee and month selects
    $('#employee, #month').on('change', function() {
        // Only fetch if an employee is selected
        if ($('#employee').val()) {
            fetchSalaryDetails();
        }
    });

    function fetchSalaryDetails() {    
        var empCode = $('#employee').val();
        var month = $('#month').val();

        if (!empCode) {
            showError('You must select an employee before viewing salary details', 'Employee Required');
            return;
        }

        showLoading();

        $.ajax({
            url: '../../action/PRPayDetail/fetch_salary.php',
            type: 'POST',
            data: {
                empCode: empCode,
                month: month
            },
            dataType: 'json',
            success: function(response) {
                hideLoading();
                  if (response.status === 'error') {
                    showError(response.message, 'Salary Data Error');
                    return;
                }

                // Display employee details
                $('#empCode').text(response.details.EmpCode);
                $('#empName').text(response.details.EmpName);
                $('#department').text(response.details.Department);
                $('#position').text(response.details.Position);

                // Populate salary table
                var salaryBody = $('#salaryTable tbody');
                salaryBody.empty();

                // Add salary components
                var components = [
                    { name: 'Basic Salary', desc: 'Monthly base salary', amount: response.details.Salary },
                    { name: 'Allowances', desc: 'Additional benefits', amount: response.details.Allowance },
                    { name: 'OT', desc: 'Overtime', amount: response.details.OT},
                    { name: 'Bonus', desc: 'Performance bonus', amount: response.details.Bonus },
                    { name: 'Deductions', desc: 'Total deductions', amount: response.details.Deduction },
                    { name: 'Tax', desc: 'Income tax', amount: response.details.LeavedTax },
                    { name: 'NSSF', desc: 'Social security contribution', amount: response.details.NSSF }
                ];

                // components.forEach(function(component) {
                //     salaryBody.append(`
                //         <tr>
                //             <td>${component.name}</td>
                //             <td>${component.desc}</td>
                //             <td class="text-end">${formatCurrency(component.amount)}</td>
                //         </tr>
                //     `);
                // });
                for(var item in components) {
                    salaryBody.append(`
                        <tr>
                            <td>${components[item].name}</td>
                            <td>${components[item].desc}</td>
                            <td class="text-end">${formatCurrency(components[item].amount)}</td>
                        </tr>
                    `);
                }

                // Update net pay
                $('#totalNetPay').text(formatCurrency(response.details.NetSalary));

                // Show the details sections
                $('#employeeDetails, #salaryDetails').show();
            },            error: function(xhr, status, error) {
                hideLoading();
                let errorMessage = 'Failed to fetch salary details.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage += '\nDetails: ' + xhr.responseJSON.message;
                } else if (error) {
                    errorMessage += '\nDetails: ' + error;
                }
                showError(errorMessage, 'Server Error');
                console.error('Error:', error);
            }
        });
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(amount || 0);
    }
});
</script>
