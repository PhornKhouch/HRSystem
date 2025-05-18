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

<div class="container-fluid mt-3">
    <div class="card">
        <div class="card-header">
            <h5 class="card-header-title">
                <i class="fas fa-users"></i>
                Employee Family Report
            </h5>
        </div>
        <div class="card-body">
            <form id="employeeFamilyForm" method="POST">
            <div class="filter-section">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="employeeCode" class="form-label">Employee Code:</label>
                        <input type="text" id="employeeCode" class="form-control" placeholder="Enter employee code">
                    </div>
                    <div class="col-md-3">
                        <label for="relationshipType" class="form-label">Relationship Type:</label>
                        <select id="relationshipType" class="form-select">
                            <option value="all">All</option>
                            <option value="Spouse">Spouse</option>
                            <option value="Child">Child</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="gender" class="form-label">Gender:</label>
                        <select id="gender" class="form-select">
                            <option value="all">All</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="taxStatus" class="form-label">Tax Status:</label>
                        <select id="taxStatus" class="form-select">
                            <option value="all">All</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Generate Report
                        </button>
                    </div>
                </div>
            </div>
            </form>
            <!-- Report Table Section -->
            <div class="detail-card mt-4">
                <div class="detail-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Employee Family Details</h6>
                </div>
                <div class="detail-body">
                    <table id="familyTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Employee Code</th>
                                <th>Employee Name</th>
                                <th>Family Member Name</th>
                                <th>Relationship</th>
                                <th>Gender</th>
                                <th>Tax Status</th>
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



