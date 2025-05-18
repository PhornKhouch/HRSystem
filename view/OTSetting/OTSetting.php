<?php
include("../../Config/conect.php");
?>

    <table id="OTSettingTable" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th style="width: 150px"><button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addOTSettingModal">Add</button></th>
                <th>Code</th>
                <th>Description</th>
                <th>Rate</th>
            </tr>
        </thead>
        <tbody id="data">
            <?php
                $sql = "SELECT * FROM protrate";
                $result = $con->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
            ?>
                    <tr data-id="<?php echo $row['Code']; ?>">
                        <td>
                            <button class="btn btn-primary btn-sm edit-ot-setting-btn" 
                                    data-code="<?php echo $row['Code']; ?>"
                                    data-description="<?php echo $row['Des']; ?>"
                                    data-rate="<?php echo $row['Rate']; ?>">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm delete-ot-setting-btn" data-code="<?php echo $row['Code']; ?>">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                        <td><?php echo $row['Code']; ?></td>
                        <td><?php echo $row['Des']; ?></td>
                        <td><?php echo $row['Rate']; ?></td>
                    </tr>
            <?php
                    }
                }
            ?>
        </tbody>
    </table>

<!-- Add Modal -->
<div class="modal fade" id="addOTSettingModal" tabindex="-1" aria-labelledby="addOTSettingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="addOTSettingModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Add New OT Setting
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addOTSettingForm" class="needs-validation" novalidate>
                    <div class="mb-4">
                        <label for="OTCode" class="form-label fw-semibold">
                            <i class="fas fa-code me-2"></i>Code
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-hashtag text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="OTCode" required
                                   placeholder="Enter OT code">
                            <div class="invalid-feedback">Please provide a valid code.</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="OTDescription" class="form-label fw-semibold">
                            <i class="fas fa-align-left me-2"></i>Description
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-pen text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="OTDescription" required
                                   placeholder="Enter description">
                            <div class="invalid-feedback">Please provide a description.</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="OTRate" class="form-label fw-semibold">
                            <i class="fas fa-percentage me-2"></i>Rate
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-calculator text-muted"></i>
                            </span>
                            <input type="number" class="form-control border-start-0" id="OTRate" step="0.1" required
                                   placeholder="Enter rate value">
                            <div class="invalid-feedback">Please provide a valid rate.</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light fw-semibold" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary fw-semibold" id="saveOTSetting">
                    <i class="fas fa-save me-2"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        let otSettingTable;
        if (!$.fn.DataTable.isDataTable('#OTSettingTable')) {
            otSettingTable = $('#OTSettingTable').DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false
            });
        } else {
            otSettingTable = $('#OTSettingTable').DataTable();
        }

        // Add new OT setting
        $('#saveOTSetting').click(function() {
            if (!$('#addOTSettingForm')[0].checkValidity()) {
                $('#addOTSettingForm')[0].reportValidity();
                return;
            }

            $.ajax({
                url: "../../action/OTSetting/create.php",
                type: "POST",
                data: {
                    type: "OTSetting",
                    code: $('#OTCode').val(),
                    description: $('#OTDescription').val(),
                    rate: $('#OTRate').val()
                },
                success: function(response) {
                    // Add new row to DataTable
                    otSettingTable.row.add([
                        `<button class="btn btn-primary btn-sm edit-ot-setting-btn" 
                            data-code="${$('#OTCode').val()}" 
                            data-description="${$('#OTDescription').val()}"
                            data-rate="${$('#OTRate').val()}">
                            <i class="fas fa-edit"></i> Edit
                         </button>
                         <button class="btn btn-danger btn-sm delete-ot-setting-btn" data-code="${$('#OTCode').val()}">
                            <i class="fas fa-trash"></i> Delete
                         </button>`,
                        $('#OTCode').val(),
                        $('#OTDescription').val(),
                        $('#OTRate').val()
                    ]).draw(false);

                    // Hide modal and clean up
                    $('#addOTSettingModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    
                    // Clear form
                    $('#OTCode').val('');
                    $('#OTDescription').val('');
                    $('#OTRate').val('');

                    showToast('success', response);
                },
                error: function(xhr) {
                    showToast('error', xhr.responseText || 'Error adding OT setting');
                }
            });
        });

        // Edit button click handler
        $(document).on('click', '.edit-ot-setting-btn', function() {
            const code = $(this).data('code');
            const description = $(this).data('description');
            const rate = $(this).data('rate');

            $('#edit_ot_code').val(code);
            $('#edit_description').val(description);
            $('#edit_rate').val(rate);

            $('#editOTSettingModal').modal('show');
        });

        // Update OT setting
        $('#updateOTSetting').click(function() {
            if (!$('#editOTSettingForm')[0].checkValidity()) {
                $('#editOTSettingForm')[0].reportValidity();
                return;
            }

            const code = $('#edit_ot_code').val();
            const description = $('#edit_description').val();
            const rate = $('#edit_rate').val();

            $.ajax({
                url: "../../action/OTSetting/update.php",
                type: "POST",
                data: {
                    type: "OTSetting",
                    code: code,
                    description: description,
                    rate: rate
                },
                success: function(response) {
                    // Find the row and update its content directly
                    const $row = $(`tr[data-id="${code}"]`);
                    const actionButtons = `<button class="btn btn-primary btn-sm edit-ot-setting-btn" 
                                    data-code="${code}" 
                                    data-description="${description}"
                                    data-rate="${rate}">
                                    <i class="fas fa-edit"></i> Edit
                                 </button>
                                 <button class="btn btn-danger btn-sm delete-ot-setting-btn" data-code="${code}">
                                    <i class="fas fa-trash"></i> Delete
                                 </button>`;
                    
                    $row.find('td:eq(0)').html(actionButtons);
                    $row.find('td:eq(2)').text(description);
                    $row.find('td:eq(3)').text(rate);

                    // Hide modal and clean up
                    $('#editOTSettingModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();

                    showToast('success', 'OT setting updated successfully');
                },
                error: function(xhr) {
                    showToast('error', xhr.responseText || 'Error updating OT setting');
                }
            });
        });

        // Delete button click handler
        $(document).on('click', '.delete-ot-setting-btn', function() {
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
                        url: "../../action/OTSetting/delete.php",
                        type: "POST",
                        data: {
                            type: "OTSetting",
                            code: code
                        },
                        success: function(response) {
                            try {
                                const jsonResponse = JSON.parse(response);
                                if (jsonResponse.status === 'success') {
                                    otSettingTable.row(row).remove().draw(false);
                                    showToast('success', jsonResponse.message || 'OT setting deleted successfully');
                                } else {
                                    showToast('error', jsonResponse.message || 'Error deleting OT setting');
                                }
                            } catch (e) {
                                if (response.toLowerCase().includes('success')) {
                                    otSettingTable.row(row).remove().draw(false);
                                    showToast('success', 'OT setting deleted successfully');
                                } else {
                                    showToast('error', response || 'Error deleting OT setting');
                                }
                            }
                        },
                        error: function(xhr) {
                            showToast('error', xhr.responseText || 'Error deleting OT setting');
                        }
                    });
                }
            });
        });

        // Helper function for showing toasts
        function showToast(icon, title) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-right",
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                },
                willClose: () => {
                    $('.swal2-container').remove();
                },
                customClass: {
                    popup: 'colored-toast',
                    timerProgressBar: 'timer-progress'
                },
                iconColor: '#fff',
                background: icon === 'success' ? '#4CAF50' : icon === 'error' ? '#F44336' : '#2196F3'
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

.colored-toast {
    padding: 16px 24px !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    border-radius: 8px !important;
    font-size: 15px !important;
    font-weight: 500 !important;
    animation: slideInDown 0.3s ease-in-out !important;
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
    min-width: 300px !important;
    max-width: 500px !important;
    margin: 0 auto !important;
}

.colored-toast .swal2-icon {
    margin: 0 12px 0 0 !important;
    width: 28px !important;
    height: 28px !important;
    flex-shrink: 0 !important;
}

.colored-toast .swal2-title {
    margin: 0 !important;
    padding: 0 !important;
    color: white !important;
    text-align: left !important;
    flex-grow: 1 !important;
}

.timer-progress {
    background: rgba(255,255,255,0.3) !important;
    height: 3px !important;
}

@keyframes slideInDown {
    from {
        transform: translateY(-100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>