<?php
include("../../Config/conect.php");
?>

<table id="TaxSettingTable" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th style="width: 150px"><button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTaxSettingModal">Add</button></th>
            <th>Amount From</th>
            <th>Amount To</th>
            <th>Rate (%)</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody id="data">
        <?php
            $sql = "SELECT * FROM prtaxrate ORDER BY AmountFrom ASC";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
        ?>
                <tr data-id="<?php echo $row['id']; ?>">
                    <td>
                        <button class="btn btn-primary btn-sm edit-tax-setting-btn" 
                                data-id="<?php echo $row['id']; ?>"
                                data-amount-from="<?php echo $row['AmountFrom']; ?>"
                                data-amount-to="<?php echo $row['AmountTo']; ?>"
                                data-rate="<?php echo $row['rate']; ?>"
                                data-status="<?php echo $row['status']; ?>">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-sm delete-tax-setting-btn" data-id="<?php echo $row['id']; ?>">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                    <td><?php echo number_format($row['AmountFrom'], 2); ?></td>
                    <td><?php echo number_format($row['AmountTo'], 2); ?></td>
                    <td><?php echo $row['rate']; ?></td>
                    <td><?php echo $row['status'] == 1 ? 'Active' : 'Inactive'; ?></td>
                </tr>
        <?php
                }
            }
        ?>
    </tbody>
</table>

<!-- Add Modal -->
<div class="modal fade" id="addTaxSettingModal" tabindex="-1" aria-labelledby="addTaxSettingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="addTaxSettingModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Add New Tax Setting
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addTaxSettingForm" class="needs-validation" novalidate>
                    <div class="mb-4">
                        <label for="amountFrom" class="form-label fw-semibold">
                            <i class="fas fa-money-bill me-2"></i>Amount From
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-dollar-sign text-muted"></i>
                            </span>
                            <input type="number" class="form-control border-start-0" id="amountFrom" required
                                   placeholder="Enter starting amount" step="0.01" min="0">
                            <div class="invalid-feedback">Please provide a valid amount.</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="amountTo" class="form-label fw-semibold">
                            <i class="fas fa-money-bill-wave me-2"></i>Amount To
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-dollar-sign text-muted"></i>
                            </span>
                            <input type="number" class="form-control border-start-0" id="amountTo" required
                                   placeholder="Enter ending amount" step="0.01" min="0">
                            <div class="invalid-feedback">Please provide a valid amount.</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="taxRate" class="form-label fw-semibold">
                            <i class="fas fa-percentage me-2"></i>Tax Rate
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-calculator text-muted"></i>
                            </span>
                            <input type="number" class="form-control border-start-0" id="taxRate" step="0.01" required
                                   placeholder="Enter tax rate" min="0" max="100">
                            <div class="invalid-feedback">Please provide a valid rate between 0 and 100.</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="taxStatus" class="form-label fw-semibold">
                            <i class="fas fa-toggle-on me-2"></i>Status
                        </label>
                        <select class="form-select" id="taxStatus" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light fw-semibold" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary fw-semibold" id="saveTaxSetting">
                    <i class="fas fa-save me-2"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editTaxSettingModal" tabindex="-1" aria-labelledby="editTaxSettingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="editTaxSettingModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Tax Setting
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editTaxSettingForm" class="needs-validation" novalidate>
                    <input type="hidden" id="edit_tax_id">
                    <div class="mb-4">
                        <label for="edit_amount_from" class="form-label fw-semibold">
                            <i class="fas fa-money-bill me-2"></i>Amount From
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-dollar-sign text-muted"></i>
                            </span>
                            <input type="number" class="form-control border-start-0" id="edit_amount_from" required
                                   step="0.01" min="0">
                            <div class="invalid-feedback">Please provide a valid amount.</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="edit_amount_to" class="form-label fw-semibold">
                            <i class="fas fa-money-bill-wave me-2"></i>Amount To
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-dollar-sign text-muted"></i>
                            </span>
                            <input type="number" class="form-control border-start-0" id="edit_amount_to" required
                                   step="0.01" min="0">
                            <div class="invalid-feedback">Please provide a valid amount.</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="edit_rate" class="form-label fw-semibold">
                            <i class="fas fa-percentage me-2"></i>Tax Rate
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-calculator text-muted"></i>
                            </span>
                            <input type="number" class="form-control border-start-0" id="edit_rate" step="0.01" required
                                   min="0" max="100">
                            <div class="invalid-feedback">Please provide a valid rate between 0 and 100.</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="edit_status" class="form-label fw-semibold">
                            <i class="fas fa-toggle-on me-2"></i>Status
                        </label>
                        <select class="form-select" id="edit_status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light fw-semibold" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary fw-semibold" id="updateTaxSetting">
                    <i class="fas fa-save me-2"></i>Update Changes
                </button>
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

/* Modern Form Styles */
.modal-content {
    border-radius: 15px;
}

.modal-header {
    padding: 1.5rem 1.75rem;
}

.modal-header .modal-title {
    font-size: 1.25rem;
    font-weight: 600;
}

.form-label {
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
    color: #444;
}

.input-group {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
    border-radius: 8px;
}

.input-group-text {
    border-radius: 8px 0 0 8px;
    border: 1px solid #dee2e6;
    padding: 0.6rem 1rem;
}

.form-control {
    border-radius: 0 8px 8px 0;
    padding: 0.6rem 1rem;
    border: 1px solid #dee2e6;
    font-size: 0.95rem;
}

.form-control:focus {
    box-shadow: none;
    border-color: #86b7fe;
}

.input-group:focus-within {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.input-group:focus-within .input-group-text,
.input-group:focus-within .form-control {
    border-color: #86b7fe;
}

.btn {
    padding: 0.6rem 1.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: #0d6efd;
    border: none;
}

.btn-primary:hover {
    background-color: #0b5ed7;
    transform: translateY(-1px);
}

.btn-light {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}

.btn-light:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.invalid-feedback {
    font-size: 0.85rem;
    margin-top: 0.5rem;
}

/* Animation for modal */
.modal.fade .modal-dialog {
    transition: transform 0.3s ease-out;
}

.modal.fade .modal-content {
    transform: scale(0.95);
    transition: transform 0.3s ease-out;
}

.modal.show .modal-content {
    transform: scale(1);
}
</style>

<script>
$(document).ready(function() {
    // Initialize DataTable for Tax Settings
    let taxSettingTable;
    if (!$.fn.DataTable.isDataTable('#TaxSettingTable')) {
        taxSettingTable = $('#TaxSettingTable').DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            order: [[1, 'asc']] // Sort by Amount From by default
        });
    } else {
        taxSettingTable = $('#TaxSettingTable').DataTable();
    }

    // Add new Tax setting
    $('#saveTaxSetting').click(function() {
        if (!$('#addTaxSettingForm')[0].checkValidity()) {
            $('#addTaxSettingForm')[0].reportValidity();
            return;
        }

        const amountFrom = parseFloat($('#amountFrom').val());
        const amountTo = parseFloat($('#amountTo').val());
        const rate = parseFloat($('#taxRate').val());

        if (amountFrom >= amountTo) {
            showToast('error', 'Amount From must be less than Amount To');
            return;
        }

        if (rate < 0 || rate > 100) {
            showToast('error', 'Tax rate must be between 0 and 100');
            return;
        }

        $.ajax({
            url: "../../action/TaxSetting/create.php",
            type: "POST",
            data: {
                type: "TaxSetting",
                amountFrom: amountFrom,
                amountTo: amountTo,
                rate: rate,
                status: $('#taxStatus').val()
            },
            success: function(response) {
                try {
                    const jsonResponse = typeof response === 'string' ? JSON.parse(response) : response;
                    if (jsonResponse.status === 'success') {
                        // Add new row to DataTable
                        taxSettingTable.row.add([
                            `<div class="btn-group">
                                <button class="btn btn-primary btn-sm edit-tax-setting-btn" 
                                    data-id="${jsonResponse.id}" 
                                    data-amount-from="${$('#amountFrom').val()}"
                                    data-amount-to="${$('#amountTo').val()}"
                                    data-rate="${$('#taxRate').val()}"
                                    data-status="1">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm delete-tax-setting-btn" data-id="${jsonResponse.id}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>`,
                            $('#amountFrom').val(),
                            $('#amountTo').val(),
                            $('#taxRate').val(),
                            `<div class="form-check form-switch">
                                <input class="form-check-input status-toggle" type="checkbox" 
                                    data-id="${jsonResponse.id}" checked>
                            </div>`
                        ]).draw(false);

                        // Hide modal and clean up
                        $('#addTaxSettingModal').modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        
                        // Clear form
                        $('#amountFrom').val('');
                        $('#amountTo').val('');
                        $('#taxRate').val('');

                        showToast('success', jsonResponse.message || 'Tax setting added successfully');
                    } else {
                        showToast('error', jsonResponse.message || 'Error adding tax setting');
                    }
                } catch (e) {
                    showToast('error', 'Error processing server response');
                }
            },
            error: function(xhr) {
                showToast('error', xhr.responseText || 'Error adding tax setting');
            }
        });
    });

    // Edit button click handler for Tax Settings
    $(document).on('click', '.edit-tax-setting-btn', function() {
        const id = $(this).data('id');
        const amountFrom = $(this).data('amount-from');
        const amountTo = $(this).data('amount-to');
        const rate = $(this).data('rate');
        const status = $(this).data('status');

        $('#edit_tax_id').val(id);
        $('#edit_amount_from').val(amountFrom);
        $('#edit_amount_to').val(amountTo);
        $('#edit_rate').val(rate);
        $('#edit_status').val(status);

        $('#editTaxSettingModal').modal('show');
    });

    // Update Tax setting
    $('#updateTaxSetting').click(function() {
        if (!$('#editTaxSettingForm')[0].checkValidity()) {
            $('#editTaxSettingForm')[0].reportValidity();
            return;
        }

        const id = $('#edit_tax_id').val();
        const amountFrom = parseFloat($('#edit_amount_from').val());
        const amountTo = parseFloat($('#edit_amount_to').val());
        const rate = parseFloat($('#edit_rate').val());
        const status = $('#edit_status').val();

        if (amountFrom >= amountTo) {
            showToast('error', 'Amount From must be less than Amount To');
            return;
        }

        if (rate < 0 || rate > 100) {
            showToast('error', 'Tax rate must be between 0 and 100');
            return;
        }

        $.ajax({
            url: "../../action/TaxSetting/update.php",
            type: "POST",
            data: {
                type: "TaxSetting",
                id: id,
                amountFrom: amountFrom,
                amountTo: amountTo,
                rate: rate,
                status: status
            },
            success: function(response) {
                try {
                    const jsonResponse = typeof response === 'string' ? JSON.parse(response) : response;
                    if (jsonResponse.status === 'success') {
                        // Update row in DataTable
                        const row = $(`button[data-id="${id}"]`).closest('tr');
                        taxSettingTable.row(row).data([
                            `<button class="btn btn-primary btn-sm edit-tax-setting-btn" 
                                data-id="${id}" 
                                data-amount-from="${amountFrom}"
                                data-amount-to="${amountTo}"
                                data-rate="${rate}"
                                data-status="${status}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm delete-tax-setting-btn" data-id="${id}">
                                <i class="fas fa-trash"></i> Delete
                            </button>`,
                            amountFrom,
                            amountTo,
                            rate,
                            status == 1 ? 'Active' : 'Inactive'
                        ]).draw(false);

                        // Hide modal and clean up
                        $('#editTaxSettingModal').modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();

                        showToast('success', jsonResponse.message || 'Tax setting updated successfully');
                    } else {
                        showToast('error', jsonResponse.message || 'Error updating tax setting');
                    }
                } catch (e) {
                    showToast('error', 'Error processing server response');
                }
            },
            error: function(xhr) {
                showToast('error', xhr.responseText || 'Error updating tax setting');
            }
        });
    });

    // Delete Tax Setting
    $(document).on('click', '.delete-tax-setting-btn', function() {
        const row = $(this).closest('tr');
        const id = $(this).data('id');

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
                    url: "../../action/TaxSetting/delete.php",
                    type: "POST",
                    data: {
                        type: "TaxSetting",
                        id: id
                    },
                    success: function(response) {
                        try {
                            const jsonResponse = typeof response === 'string' ? JSON.parse(response) : response;
                            if (jsonResponse.status === 'success') {
                                taxSettingTable.row(row).remove().draw(false);
                                showToast('success', jsonResponse.message || 'Tax setting deleted successfully');
                            } else {
                                showToast('error', jsonResponse.message || 'Error deleting tax setting');
                            }
                        } catch (e) {
                            showToast('error', 'Error processing server response');
                        }
                    },
                    error: function(xhr) {
                        showToast('error', xhr.responseText || 'Error deleting tax setting');
                    }
                });
            }
        });
    });

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Input animation
    $('.form-control').on('focus', function() {
        $(this).closest('.input-group').addClass('focused');
    }).on('blur', function() {
        $(this).closest('.input-group').removeClass('focused');
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