<?php
session_start();
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
        --primary-color: #6366f1;
        /* Modern indigo */
        --secondary-color: #4f46e5;
        /* Deeper indigo */
        --success-color: #10b981;
        /* Fresh emerald */
        --warning-color: #f59e0b;
        /* Warm amber */
        --danger-color: #ef4444;
        /* Vibrant red */
        --info-color: #3b82f6;
        /* Bright blue */
        --border-color: #e2e8f0;
        /* Cool gray */
        --bg-light: #f8fafc;
        /* Slate 50 */
        --bg-dark: #1e293b;
        /* Slate 800 */
        --text-primary: #0f172a;
        /* Slate 900 */
        --text-secondary: #475569;
        /* Slate 600 */
        --text-light: #94a3b8;
        /* Slate 400 */
        --gradient-start: #818cf8;
        /* Indigo 400 */
        --gradient-end: #6366f1;
        /* Indigo 500 */
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
    .form-select,
    .form-control {
        border-radius: 0.5rem;
        border: 1px solid var(--border-color);
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        color: var(--text-primary);
        background-color: white;
        transition: all 0.2s ease;
    }

    .form-select:hover,
    .form-control:hover {
        border-color: var(--text-light);
    }

    .form-select:focus,
    .form-control:focus {
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
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
        margin-bottom: 1rem;
    }

    .table {
        border: 1px solid var(--border-color);
        background: white;
        margin-bottom: 0;
        width: 100%;
    }

    .detail-body {
        padding: 1.5rem;
    }

    /* DataTables Styling */
    .dataTables_wrapper {
        position: relative;
    }

    .dataTables_scroll {
        margin-bottom: 0;
    }

    .dataTables_scrollBody {
        min-height: 200px;
    }

    .dataTables_scrollFoot {
        position: sticky;
        bottom: 0;
        z-index: 2;
        background: white;
        box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
    }

    /* Ensure buttons stay above scroll */
    .dt-buttons {
        position: sticky;
        left: 0;
        z-index: 1;
    }

    /* Fix pagination alignment */
    .dataTables_paginate {
        margin-top: 1rem !important;
    }

    .table thead {
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
    }

    .table thead th {
        color: purple !important;
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
                <i class="fas fa-users"></i>
                Employee Family Report
            </h5>
        </div>
        <div class="card-body">
            <!-- Employee Family Report Section -->
            <div class="detail-card mt-4">
                <div class="detail-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Employee Family Details</h6>
                </div>
                <div class="detail-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label for="employeeCode" class="form-label">Employee Code:</label>
                            <input type="text" id="employeeCode" class="form-control" placeholder="Enter employee code">
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
                        <div class="col-md-3">
                            <label for="relationshipType" class="form-label">Relationship:</label>
                            <select id="relationshipType" class="form-select">
                                <option value="all">All Types</option>
                                <option value="Spouse">Spouse</option>
                                <option value="Child">Child</option>
                                <option value="Parent">Parent</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="taxStatus" class="form-label">Tax Status:</label>
                            <select id="taxStatus" class="form-select">
                                <option value="all">All Status</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" id="viewFamilyReport" class="btn btn-primary"><i class="fas fa-search me-2"></i>View Family Report</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="familyTable" class="table table-bordered table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Employee Code</th>
                                    <th>Employee Name</th>
                                    <th>Family Member</th>
                                    <th>Relationship</th>
                                    <th>Gender</th>
                                    <th>Tax Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
    let familyTable;

    // Format date
    function formatDate(date) {
        return date ? moment(date).format('DD-MM-YYYY') : '';
    }

    // Initialize DataTable
    function initializeDataTable() {
        if (familyTable) {
            familyTable.destroy();
        }

        familyTable = $('#familyTable').DataTable({
            scrollX: true,
            scrollY: '50vh',
            scrollCollapse: true,
            autoWidth: false,
            fixedHeader: {
                header: true,
                footer: true
            },
            processing: true,
            serverSide: false,
            pageLength: 25,
            order: [[0, 'asc']],
            ajax: {
                url: '../../../action/Report/fetch_employee_family.php',
                type: 'POST',
                data: function(d) {
                    return {
                        ...d,
                        employeeCode: $('#employeeCode').val(),
                        department: $('#department').val(),
                        relationshipType: $('#relationshipType').val(),
                        taxStatus: $('#taxStatus').val()
                    };
                },
                error: function(xhr, error, thrown) {
                    let errorMessage = 'Error loading data';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                    } catch (e) {
                        console.error('Error parsing error response:', e);
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Data Loading Error',
                        text: errorMessage
                    });
                }
            },
            columns: [
                { data: 'EmpCode', title: 'Employee Code' },
                { data: 'EmpName', title: 'Employee Name' },
                { data: 'FamilyMemberName', title: 'Family Member' },
                { data: 'RelationType', title: 'Relationship' },
                { data: 'Gender', title: 'Gender' },
                { 
                    data: 'IsTax', 
                    title: 'Tax Status',
                    render: function(data) {
                        return `<span class="${data === 'Yes' ? 'status-active' : 'status-pending'}">${data}</span>`;
                    }
                },
                { 
                    data: 'Actions',
                    title: 'Actions',
                    orderable: false,
                    className: 'text-center'
                }
            ],
            dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel me-2"></i>Export to Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    },
                    footer: true,
                    filename: function() {
                        return 'Employee_Family_Report_' + moment().format('YYYY-MM-DD');
                    },
                    title: function() {
                        let title = 'Employee Family Report';
                        if ($('#department').val() !== 'all') {
                            title += ' - ' + $('#department option:selected').text();
                        }
                        if ($('#relationshipType').val() !== 'all') {
                            title += ' - ' + $('#relationshipType').val();
                        }
                        return title;
                    }
                }
            ],
            footerCallback: function(row, data, start, end, display) {
                var api = this.api();
                // Update total records in footer
                var totalRecords = api.page.info().recordsTotal;
                $(api.table().footer()).find('th').html('Total Records: ' + totalRecords);
            },
            drawCallback: function(settings) {
                if (settings.json && settings.json.data && settings.json.data.length === 0) {
                    Swal.fire({
                        icon: 'info',
                        title: 'No Data Found',
                        text: 'No family records found for the selected filters.'
                    });
                }
            }
        });
    }

    // Handle view report button click
    $('#viewFamilyReport').on('click', function() {
        initializeDataTable();
    });

    // Handle view details button click
    $('#familyTable').on('click', '.view-details', function() {
        const empCode = $(this).data('id');
        // Add your view details logic here
        Swal.fire({
            title: 'Family Details',
            text: 'Viewing family details for employee: ' + empCode,
            icon: 'info'
        });
    });
});
</script>