<?php
include("../../Config/conect.php");
?>

    <table id="TelegramConfigTable" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th style="width: 150px"><button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTelegramConfigModal">Add</button></th>
                <th>Chat Name</th>
                <th>Chat ID</th>
                <th>Bot Token</th>
                <th>description</th>
                <th>status</th>
            </tr>
        </thead>
        <tbody id="data">
            <?php
                $sql = "SELECT * FROM sytelegram_config";
                $result = $con->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
            ?>
                    <tr data-id="<?php echo $row['id']; ?>">
                        <td>
                            <button class="btn btn-primary btn-sm edit-telegram-btn" 
                                    data-id="<?php echo $row['id']; ?>"
                                    data-chat-name="<?php echo $row['chat_name']; ?>"
                                    data-chat-id="<?php echo $row['chat_id']; ?>"
                                    data-bot-token="<?php echo $row['bot_token']; ?>"
                                    data-description="<?php echo $row['description']; ?>"
                                    data-status="<?php echo $row['status']; ?>">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm delete-telegram-btn" data-id="<?php echo $row['id']; ?>">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                        <td><?php echo $row['chat_name']; ?></td>
                        <td><?php echo $row['chat_id']; ?></td>
                        <td><?php echo substr($row['bot_token'], 0, 10) . '...'; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td>
                            <center>
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" <?php echo $row['status'] == 1 ? 'checked' : ''; ?> disabled>
                                </div>
                            </center>
                        </td>
                    </tr>
            <?php
                    }
                }
            ?>
        </tbody>
    </table>

<!-- Add Modal -->
<div class="modal fade" id="addTelegramConfigModal" tabindex="-1" aria-labelledby="addTelegramConfigModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTelegramConfigModalLabel">Add New Telegram Configuration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addTelegramConfigForm">
                    <div class="mb-3">
                        <label for="chat_name" class="form-label">Chat Name</label>
                        <input type="text" class="form-control" id="chat_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="chat_id" class="form-label">Chat ID</label>
                        <input type="text" class="form-control" id="chat_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="bot_token" class="form-label">Bot Token</label>
                        <input type="text" class="form-control" id="bot_token" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">description</label>
                        <textarea class="form-control" id="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3 form-switch-custom">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="status" checked>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
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

<!-- Edit Modal -->
<div class="modal fade" id="editTelegramConfigModal" tabindex="-1" aria-labelledby="editTelegramConfigModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTelegramConfigModalLabel">Edit Telegram Configuration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editTelegramConfigForm">
                    <input type="hidden" id="edit_id">
                    <div class="mb-3">
                        <label for="edit_chat_name" class="form-label">Chat Name</label>
                        <input type="text" class="form-control" id="edit_chat_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_chat_id" class="form-label">Chat ID</label>
                        <input type="text" class="form-control" id="edit_chat_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_bot_token" class="form-label">Bot Token</label>
                        <input type="text" class="form-control" id="edit_bot_token" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">description</label>
                        <textarea class="form-control" id="edit_description" rows="3"></textarea>
                    </div>
                    <div class="mb-3 form-switch-custom">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="edit_status">
                            <label class="form-check-label" for="edit_status">Active</label>
                        </div>
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
        // Initialize DataTable with column definitions
        if (!$.fn.DataTable.isDataTable('#TelegramConfigTable')) {
            telegramTable = $('#TelegramConfigTable').DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                columns: [
                    { data: 'actions', orderable: false },
                    { data: 'chat_name' },
                    { data: 'chat_id' },
                    { data: 'bot_token' },
                    { data: 'description' },
                    { data: 'status', orderable: false }
                ]
            });
        } else {
            telegramTable = $('#TelegramConfigTable').DataTable();
        }

        // Add new Telegram config
        $('#saveTelegramConfig').click(function() {
            if (!$('#addTelegramConfigForm')[0].checkValidity()) {
                $('#addTelegramConfigForm')[0].reportValidity();
                return;
            }

            var status = $('#status').is(':checked') ? 1 : 0;

            $.ajax({
                url: "../../action/Telegramconfig/create.php",
                type: "POST",
                data: {
                    type: "TelegramConfig",
                    chat_name: $('#chat_name').val(),
                    chat_id: $('#chat_id').val(),
                    bot_token: $('#bot_token').val(),
                    description: $('#description').val(),
                    status: status
                },
                success: function(response) {
                    const rowData = {
                        actions: `<button class="btn btn-primary btn-sm edit-telegram-btn" 
                            data-id="${response.id}"
                            data-chat-name="${$('#chat_name').val()}"
                            data-chat-id="${$('#chat_id').val()}"
                            data-bot-token="${$('#bot_token').val()}"
                            data-description="${$('#description').val()}"
                            data-status="${status}">
                            <i class="fas fa-edit"></i> Edit
                         </button>
                         <button class="btn btn-danger btn-sm delete-telegram-btn" data-id="${response.id}">
                            <i class="fas fa-trash"></i> Delete
                         </button>`,
                        chat_name: $('#chat_name').val(),
                        chat_id: $('#chat_id').val(),
                        bot_token: $('#bot_token').val().substring(0, 10) + '...',
                        description: $('#description').val(),
                        status: `<center>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" ${status ? 'checked' : ''} disabled>
                            </div>
                        </center>`
                    };

                    // Add new row to DataTable
                    telegramTable.row.add(rowData).draw(false);

                    // Hide modal and clean up
                    $('#addTelegramConfigModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    
                    // Clear form
                    $('#chat_name').val('');
                    $('#chat_id').val('');
                    $('#bot_token').val('');
                    $('#description').val('');
                    $('#status').prop('checked', true);

                    showToast('success', 'Telegram configuration added successfully');
                },
                error: function(xhr) {
                    showToast('error', xhr.responseText || 'Error adding Telegram configuration');
                }
            });
        });

        // Edit button click handler
        $(document).on('click', '.edit-telegram-btn', function() {
            const id = $(this).data('id');
            const chatName = $(this).data('chat-name');
            const chatId = $(this).data('chat-id');
            const botToken = $(this).data('bot-token');
            const description = $(this).data('description');
            const status = parseInt($(this).data('status'));

            $('#edit_id').val(id);
            $('#edit_chat_name').val(chatName);
            $('#edit_chat_id').val(chatId);
            $('#edit_bot_token').val(botToken);
            $('#edit_description').val(description);
            $('#edit_status').prop('checked', status === 1);

            $('#editTelegramConfigModal').modal('show');
        });

        // Update Telegram config
        $('#updateTelegramConfig').click(function() {
            if (!$('#editTelegramConfigForm')[0].checkValidity()) {
                $('#editTelegramConfigForm')[0].reportValidity();
                return;
            }

            const id = $('#edit_id').val();
            const status = $('#edit_status').is(':checked') ? 1 : 0;
            const chatName = $('#edit_chat_name').val();
            const chatId = $('#edit_chat_id').val();
            const botToken = $('#edit_bot_token').val();
            const description = $('#edit_description').val();

            $.ajax({
                url: "../../action/Telegramconfig/update.php",
                type: "POST",
                data: {
                    type: "TelegramConfig",
                    id: id,
                    chat_name: chatName,
                    chat_id: chatId,
                    bot_token: botToken,
                    description: description,
                    status: status
                },
                success: function(response) {
                    // Find the row index first
                    const rowIndex = telegramTable.row($(`tr[data-id="${id}"]`)).index();
                    
                    if (rowIndex !== undefined) {
                        const rowData = {
                            actions: `<button class="btn btn-primary btn-sm edit-telegram-btn" 
                                data-id="${id}"
                                data-chat-name="${chatName}"
                                data-chat-id="${chatId}"
                                data-bot-token="${botToken}"
                                data-description="${description}"
                                data-status="${status}">
                                <i class="fas fa-edit"></i> Edit
                             </button>
                             <button class="btn btn-danger btn-sm delete-telegram-btn" data-id="${id}">
                                <i class="fas fa-trash"></i> Delete
                             </button>`,
                            chat_name: chatName,
                            chat_id: chatId,
                            bot_token: botToken.substring(0, 10) + '...',
                            description: description,
                            status: `<center>
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" ${status ? 'checked' : ''} disabled>
                                </div>
                            </center>`
                        };

                        // Update the row data
                        telegramTable.row(rowIndex).data(rowData).draw(false);

                        // Hide modal and clean up
                        $('#editTelegramConfigModal').modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();

                        showToast('success', 'Telegram configuration updated successfully');
                    } else {
                        showToast('error', 'Could not find the row to update');
                    }
                },
                error: function(xhr) {
                    showToast('error', xhr.responseText || 'Error updating Telegram configuration');
                }
            });
        });

        // Delete button click handler
        $(document).on('click', '.delete-telegram-btn', function() {
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
                            type: "TelegramConfig",
                            id: id
                        },
                        success: function(response) {
                            try {
                                const jsonResponse = JSON.parse(response);
                                if (jsonResponse.status === 'success') {
                                    telegramTable.row(row).remove().draw(false);
                                    showToast('success', jsonResponse.message || 'Telegram configuration deleted successfully');
                                } else {
                                    showToast('error', jsonResponse.message || 'Error deleting Telegram configuration');
                                }
                            } catch (e) {
                                if (response.toLowerCase().includes('success')) {
                                    telegramTable.row(row).remove().draw(false);
                                    showToast('success', 'Telegram configuration deleted successfully');
                                } else {
                                    showToast('error', response || 'Error deleting Telegram configuration');
                                }
                            }
                        },
                        error: function(xhr) {
                            showToast('error', xhr.responseText || 'Error deleting Telegram configuration');
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

.form-switch-custom {
    padding: 0.5rem 0;
}

.form-switch-custom .form-check.form-switch {
    padding-left: 2.5em;
    margin: 0;
}

.form-switch-custom .form-check-input {
    width: 2.5em;
    height: 1.25em;
    margin-left: -2.5em;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%280, 0, 0, 0.25%29'/%3e%3c/svg%3e");
    background-position: left center;
    border-radius: 2em;
    transition: background-position .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}

.form-switch-custom .form-check-input:checked {
    background-position: right center;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.form-switch-custom .form-check-input:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    border-color: #86b7fe;
}

.form-switch-custom .form-check-label {
    cursor: pointer;
    padding-left: 0.5rem;
    user-select: none;
}
</style>