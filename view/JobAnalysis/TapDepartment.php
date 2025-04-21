<?php
include("../../Config/conect.php");
?>


    <table id="departmentTable" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th style="width: 150px"><button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addDeptModal">Add</button></th>
                <th>Department Code</th>
                <th>Department Name</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="data">
            <?php
            $sql = "SELECT * FROM hrdepartment";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
                    <tr data-id="<?php echo $row['Code']; ?>">
                        <td>
                            <button class="btn btn-primary btn-sm edit-dept-btn" 
                                    data-code="<?php echo $row['Code']; ?>"
                                    data-name="<?php echo $row['Description']; ?>"
                                    data-status="<?php echo $row['Status']; ?>">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm delete-dept-btn" data-code="<?php echo $row['Code']; ?>">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                        <td><?php echo $row['Code']; ?></td>
                        <td><?php echo $row['Description']; ?></td>
                        <td><?php echo $row['Status']; ?></td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>


<!-- Add Modal -->
<div class="modal fade" id="addDeptModal" tabindex="-1" aria-labelledby="addDeptModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDeptModalLabel">Add New Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addDeptForm">
                    <div class="mb-3">
                        <label for="code" class="form-label">Department Code</label>
                        <input type="text" class="form-control" id="DeptCode" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Department Name</label>
                        <input type="text" class="form-control" id="DeptName" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="DeptStatus" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveDept">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editDeptModal" tabindex="-1" aria-labelledby="editDeptModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDeptModalLabel">Edit Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editDeptForm">
                    <input type="hidden" id="edit_dept_code">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="edit_dept_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-control" id="edit_dept_status" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateDept">Update</button>
            </div>
        </div>
    </div>
</div>

<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        const table = $('#departmentTable').DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: false
        });

        // Add new department
        $('#saveDept').click(function() {
            if (!$('#addDeptForm')[0].checkValidity()) {
                $('#addDeptForm')[0].reportValidity();
                return;
            }

            $.ajax({
                url: "../../action/JobAnalysis/create.php",
                type: "POST",
                data: {
                    type: "Department",
                    code: $('#DeptCode').val(),
                    name: $('#DeptName').val(),
                    status: $('#DeptStatus').val()
                },
                success: function(response) {
                    if (response.includes("Data Inserted")) {
                            table.row.add([
                            `<button class="btn btn-primary btn-sm edit-dept-btn" data-code="${$('#DeptCode').val()}" data-name="${$('#DeptName').val()}" data-status="${$('#DeptStatus').val()}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm delete-dept-btn" data-code="${$('#DeptCode').val()}">
                                <i class="fas fa-trash"></i> Delete
                            </button>`,
                            $('#DeptCode').val(),
                            $('#DeptName').val(),
                            $('#DeptStatus').val()
                        ]).draw(false);

                        // Hide modal and clean up
                        const modal = bootstrap.Modal.getInstance($('#addDeptModal'));
                        modal.hide();
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');
                        
                        // Clear form
                        $('#DeptCode').val('');
                        $('#DeptName').val('');
                        $('#DeptStatus').val('Active');

                        showToast('success', response);
                    }
                    else{
                        showToast('danger', response); 
                    }
                },
                error: function(xhr) {
                    showToast('error', xhr.responseText || 'Error adding company');
                }
            });
        });

        // Edit button click handler
        $(document).on('click', '.edit-dept-btn', function() {
            const code = $(this).data('code');
            const name = $(this).data('name');
            const status = $(this).data('status');

            $('#edit_dept_code').val(code);
            $('#edit_dept_name').val(name);
            $('#edit_dept_status').val(status);

            $('#editDeptModal').modal('show');
        });

        // Update company
        $('#updateDept').click(function() {
            if (!$('#editDeptForm')[0].checkValidity()) {
                $('#editDeptForm')[0].reportValidity();
                return;
            }

            const code = $('#edit_dept_code').val();
            const name = $('#edit_dept_name').val();
            const status = $('#edit_dept_status').val();

            $.ajax({
                url: "../../action/JobAnalysis/update.php",
                type: "POST",
                data: {
                    "type": "Department",
                    "code": code,
                    "name": name,
                    "status": status
                },
                success: function(response) {
                    const row = table.row($(`tr[data-id="${code}"]`));
                    const rowData = row.data();
                    rowData[0] = `<button class="btn btn-primary btn-sm edit-btn" data-code="${code}" data-name="${name}" data-status="${status}">
                                    <i class="fas fa-edit"></i> Edit
                                 </button>
                                 <button class="btn btn-danger btn-sm delete-btn" data-code="${code}">
                                    <i class="fas fa-trash"></i> Delete
                                 </button>`;
                    rowData[2] = name;
                    rowData[3] = status;
                    row.data(rowData).draw(false);

                    $('#editDeptModal').modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');

                    showToast('success', response);
                },
                error: function(xhr) {
                    showToast('error', xhr.responseText || 'Error updating company');
                }
            });
        });

        // Delete button click handler
        $(document).on('click', '.delete-dept-btn', function() {
            const row = $(this).closest('tr');
            const code = $(this).data('code');

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
                    $.ajax({
                        url: "../../action/JobAnalysis/delete.php",
                        type: "POST",
                        data: {
                            "type": "Department",
                            "code": code
                        },
                        success: function(response) {
                            table.row(row).remove().draw(false);
                            showToast('success', response);
                        },
                        error: function(xhr) {
                            showToast('error', xhr.responseText || 'Error deleting company');
                        }
                    });
                }
            });
        });

        // Helper function for showing toasts
        function showToast(icon, title) {
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
            Toast.fire({ icon, title });
        }
    });
</script>

<style>
.dataTables_wrapper .dataTables_length select {
    width: 60px;
}
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    margin-right: 0.25rem;
}
.modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}
.modal-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}
</style>