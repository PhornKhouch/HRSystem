<?php
include("../../Config/conect.php");

?>

    <table id="TelegramConfigTable" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th style="width: 150px"><button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTelegramConfigModal">Add</button></th>
                <th>Token</th>
                <th>Chat ID</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="data">
            <?php
                $sql = "SELECT * FROM telegram_config";
                $result = $con->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
            ?>
                    <tr data-id="<?php echo $row['id']; ?>">
                        <td>
                            <button class="btn btn-primary btn-sm edit-telegram-config-btn" 
                                    data-id="<?php echo $row['id']; ?>"
                                    data-token="<?php echo $row['token']; ?>"
                                    data-chat-id="<?php echo $row['chat_id']; ?>"
                                    data-status="<?php echo $row['status']; ?>">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm delete-telegram-config-btn" data-id="<?php echo $row['id']; ?>">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                        <td><?php echo $row['token']; ?></td>
                        <td><?php echo $row['chat_id']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                    </tr>
            <?php
                    }
                }
            ?>
        </tbody>
    </table>
    <!-- Add Telegram Config Modal -->
    <div class="modal fade" id="addTelegramConfigModal" tabindex="-1" aria-labelledby="addTelegramConfigModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTelegramConfigModalLabel">Add New Telegram Config</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addTelegramConfigForm">
                        <div class="mb-3">
                            <label for="token" class="form-label">Token</label>
                            <input type="text" class="form-control" id="token" required>
                        </div>
                        <div class="mb-3">
                            <label for="chat_id" class="form-label">Chat ID</label>
                            <input type="text" class="form-control" id="chat_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveTelegramConfig">Save</button>
                </div>
            </div>
        </div>
    </div>

<!-- Edit Telegram Config Modal -->
<div class="modal fade" id="editTelegramConfigModal" tabindex="-1" aria-labelledby="editTelegramConfigModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTelegramConfigModalLabel">Edit Telegram Config</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editTelegramConfigForm">
                    <input type="hidden" id="edit_telegram_config_id">
                    <div class="mb-3">
                        <label for="edit_token" class="form-label">Token</label>
                        <input type="text" class="form-control" id="edit_token" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_chat_id" class="form-label">Chat ID</label>
                        <input type="text" class="form-control" id="edit_chat_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <input type="number" class="form-control" id="edit_status" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateTelegramConfig">Update</button>
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
        let holidayTable;
        if (!$.fn.DataTable.isDataTable('#TelegramConfigTable')) {
            holidayTable = $('#TelegramConfigTable').DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false
            });
        } else {
            holidayTable = $('#TelegramConfigTable').DataTable();
        }

        // Add new holiday
        $('#saveTelegramConfig').click(function() {
            if (!$('#addTelegramConfigForm')[0].checkValidity()) {
                $('#addTelegramConfigForm')[0].reportValidity();
                return;
            }

            $.ajax({
                url: "../../action/Telegramconfig/create.php",
                type: "POST",
                data: {
                    type: "TelegramConfig",
                    token: $('#token').val(),
                    chat_id: $('#chat_id').val(),
                    status: $('#status').val()
                },
                success: function(response) {
                    const newId = response.id || Date.now(); // Fallback to timestamp if no ID returned
                    
                    holidayTable.row.add([
                        `<button class="btn btn-primary btn-sm edit-telegram-config-btn" 
                            data-id="${newId}" 
                            data-token="${$('#token').val()}"
                            data-chat_id="${$('#chat_id').val()}"
                            data-status="${$('#status').val()}">
                            <i class="fas fa-edit"></i> Edit
                         </button>
                         <button class="btn btn-danger btn-sm delete-telegram-config-btn" data-id="${newId}">
                            <i class="fas fa-trash"></i> Delete
                         </button>`,
                        $('#token').val(),
                        $('#chat_id').val(),
                        $('#status').val()
                    ]).draw(false);

                    // Hide modal and clean up
                    $('#addTelegramConfigModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    
                    // Clear form
                    $('#token').val('');
                    $('#chat_id').val('');
                    $('#status').val('');

                    showToast('success', 'Telegram config added successfully');
                },
                error: function(xhr) {
                    showToast('error', xhr.responseText || 'Error adding telegram config');
                }
            });
        });

        // Delete holiday
        $(document).on('click', '.delete-telegram-config-btn', function() {
            const id = $(this).data('id');
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "../../action/Telegramconfig/delete.php",
                        type: "POST",
                        data: {
                            type: "TelegramConfig",
                            id: id
                        },
                        success: function(response) {
                            holidayTable.row('.selected').remove().draw(false);
                            showToast('success', 'Telegram config deleted successfully');
                        },
                        error: function(xhr) {
                            showToast('error', xhr.responseText || 'Error deleting telegram config');
                        }
                    });
                }
            });
        });

        // Edit button click handler
        $(document).on('click', '.edit-telegram-config-btn', function() {
            const id = $(this).data('id');
            const token = $(this).data('token');
            const chat_id = $(this).data('chat_id');
            const status = $(this).data('status');

            $('#edit_telegram_config_id').val(id);
            $('#edit_token').val(token);
            $('#edit_chat_id').val(chat_id);
            $('#edit_status').val(status);

            $('#editTelegramConfigModal').modal('show');
        });

        // Update holiday
        $('#updateTelegramConfig').click(function() {
            if (!$('#editTelegramConfigForm')[0].checkValidity()) {
                $('#editTelegramConfigForm')[0].reportValidity();
                return;
            }

            const id = $('#edit_telegram_config_id').val();
            const token = $('#edit_token').val();
            const chat_id = $('#edit_chat_id').val();
            const status = $('#edit_status').val();

            $.ajax({
                url: "../../action/Telegramconfig/update.php",
                type: "POST",
                data: {
                    type: "TelegramConfig",
                    id: id,
                    token: token,
                    chat_id: chat_id,
                    status: status
                },
                success: function(response) {
                    const row = holidayTable.row($(`tr[data-id="${id}"]`));
                    const rowData = [
                        `<button class="btn btn-primary btn-sm edit-telegram-config-btn" 
                            data-id="${id}" 
                            data-token="${token}"
                            data-chat_id="${chat_id}"
                            data-status="${status}">
                            <i class="fas fa-edit"></i> Edit
                         </button>
                         <button class="btn btn-danger btn-sm delete-telegram-config-btn" data-id="${id}">
                            <i class="fas fa-trash"></i> Delete
                         </button>`,
                        token,
                        chat_id,
                        status
                    ];
                    row.data(rowData).draw(false);

                    // Hide modal and clean up
                    $('#editTelegramConfigModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();

                    showToast('success', 'Telegram config updated successfully');
                },
                error: function(xhr) {
                    showToast('error', xhr.responseText || 'Error updating telegram config');
                }
            });
        });

        // Delete button click handler
        $(document).on('click', '.delete-telegram-config-btn', function() {
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
                        url: "../../action/Telegramconfig/delete.php",
                        type: "POST",
                        data: {
                            type: "telegram_config",
                            id: id
                        },
                        success: function(response) {
                            try {
                                const jsonResponse = JSON.parse(response);
                                if (jsonResponse.status === 'success') {
                                    holidayTable.row(row).remove().draw(false);
                                    showToast('success', jsonResponse.message || 'Telegram config deleted successfully');
                                } else {
                                    showToast('error', jsonResponse.message || 'Error deleting telegram config');
                                }
                            } catch (e) {
                                // If response is not JSON, treat it as plain text
                                if (response.toLowerCase().includes('success')) {
                                    holidayTable.row(row).remove().draw(false);
                                    showToast('success', 'Telegram config deleted successfully');
                                } else {
                                    showToast('error', response || 'Error deleting telegram config');
                                }
                            }
                        },
                        error: function(xhr) {
                            showToast('error', xhr.responseText || 'Error deleting telegram config');
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