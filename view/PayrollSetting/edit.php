<?php
    include("../../root/Header.php");
    include("../../Config/conect.php");

    if (!isset($_GET['id'])) {
        header("Location: index.php");
        exit();
    }

    $id = intval($_GET['id']);
    $stmt = $con->prepare("SELECT * FROM prpaypolicy WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();// get result 
    $policy = $result->fetch_assoc();

    if (!$policy) {
        header("Location: index.php");
        exit();
    }

    // Set default values if fields don't exist
    $policy['fromDate'] = $policy['fromDate'] ?? date('Y-m-d');
    $policy['toDate'] = $policy['toDate'] ?? date('Y-m-d');
    $policy['hourperweek'] = $policy['hourperweek'] ?? 48;
    $policy['hourperday'] = $policy['hourperday'] ?? 8;
    $policy['monHours'] = $policy['monHours'] ?? 8;
    $policy['tueHours'] = $policy['tueHours'] ?? 8;
    $policy['wedHours'] = $policy['wedHours'] ?? 8;
    $policy['thuHours'] = $policy['thuHours'] ?? 8;
    $policy['friHours'] = $policy['friHours'] ?? 8;
    $policy['satHours'] = $policy['satHours'] ?? 8;
    $policy['sunHours'] = $policy['sunHours'] ?? 8;
    $policy['mon'] = $policy['mon'] ?? 0;
    $policy['tues'] = $policy['tues'] ?? 0;
    $policy['wed'] = $policy['wed'] ?? 0;
    $policy['thur'] = $policy['thur'] ?? 0;
    $policy['fri'] = $policy['fri'] ?? 0;
    $policy['sat'] = $policy['sat'] ?? 0;
    $policy['sun'] = $policy['sun'] ?? 0;
?>

<!-- Add SweetAlert2 CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<div class="container-fluid mt-3">
    <div class="card">
        <div class="card-header">
            <h5>Edit Pay Policy</h5>
        </div>
        <div class="card-body">
            <form id="payrollForm" action="../../action/PayrollSetting/update.php" method="POST">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($policy['id']); ?>">
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="code" class="form-label">Code</label>
                        <input type="text" class="form-control" id="code" name="code" required value="<?php echo htmlspecialchars($policy['code']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="description" name="description" required value="<?php echo htmlspecialchars($policy['description']); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="fromDate" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="fromDate" name="fromDate" required value="<?php echo htmlspecialchars($policy['fromDate']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="toDate" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="toDate" name="toDate" required value="<?php echo htmlspecialchars($policy['toDate']); ?>">
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h6>Working Day</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="form-check me-2">
                                        <input type="checkbox" class="form-check-input" id="mon" name="mon" value="1" <?php echo $policy['mon'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="mon">Monday</label>
                                    </div>
                                    <select class="form-select" name="mon_hours" style="width: 80px;">
                                        <?php for($i=1; $i<=24; $i++) { ?>
                                            <option value="<?php echo $i; ?>" <?php echo ($policy['monHours'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="form-check me-2">
                                        <input type="checkbox" class="form-check-input" id="tue" name="tue" value="1" <?php echo $policy['tues'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="tue">Tuesday</label>
                                    </div>
                                    <select class="form-select" name="tue_hours" style="width: 80px;">
                                        <?php for($i=1; $i<=24; $i++) { ?>
                                            <option value="<?php echo $i; ?>" <?php echo ($policy['tueHours'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="form-check me-2">
                                        <input type="checkbox" class="form-check-input" id="wed" name="wed" value="1" <?php echo $policy['wed'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="wed">Wednesday</label>
                                    </div>
                                    <select class="form-select" name="wed_hours" style="width: 80px;">
                                        <?php for($i=1; $i<=24; $i++) { ?>
                                            <option value="<?php echo $i; ?>" <?php echo ($policy['wedHours'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="form-check me-2">
                                        <input type="checkbox" class="form-check-input" id="thu" name="thu" value="1" <?php echo $policy['thur'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="thu">Thursday</label>
                                    </div>
                                    <select class="form-select" name="thu_hours" style="width: 80px;">
                                        <?php for($i=1; $i<=24; $i++) { ?>
                                            <option value="<?php echo $i; ?>" <?php echo ($policy['thuHours'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="form-check me-2">
                                        <input type="checkbox" class="form-check-input" id="fri" name="fri" value="1" <?php echo $policy['fri'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="fri">Friday</label>
                                    </div>
                                    <select class="form-select" name="fri_hours" style="width: 80px;">
                                        <?php for($i=1; $i<=24; $i++) { ?>
                                            <option value="<?php echo $i; ?>" <?php echo ($policy['friHours'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="form-check me-2">
                                        <input type="checkbox" class="form-check-input" id="sat" name="sat" value="1" <?php echo $policy['sat'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="sat">Saturday</label>
                                    </div>
                                    <select class="form-select" name="sat_hours" style="width: 80px;">
                                        <?php for($i=1; $i<=24; $i++) { ?>
                                            <option value="<?php echo $i; ?>" <?php echo ($policy['satHours'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="form-check me-2">
                                        <input type="checkbox" class="form-check-input" id="sun" name="sun" value="1" <?php echo $policy['sun'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="sun">Sunday</label>
                                    </div>
                                    <select class="form-select" name="sun_hours" style="width: 80px;">
                                        <?php for($i=1; $i<=24; $i++) { ?>
                                            <option value="<?php echo $i; ?>" <?php echo ($policy['sunHours'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h6>Working Hour</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="hourPerDay" class="form-label">Hour per Day</label>
                                <input type="number" class="form-control" id="hourPerDay" name="hour_per_day" min="0" max="24" step="0.5" value="<?php echo htmlspecialchars($policy['hourperday']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="workDay" class="form-label">Work Days</label>
                                <input type="number" class="form-control" id="workDay" name="work_day" min="0"  value="<?php echo htmlspecialchars($policy['workday']); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-start">
                    <button type="submit" class="btn btn-primary me-2">Update</button>
                    <a href="index.php" class="btn btn-secondary">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    margin-bottom: 1rem;
}
.form-check-label {
    min-width: 100px;
}
</style>

<script>
$(document).ready(function() {
    // Initialize Toast notification
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

    $('#payrollForm').on('submit', function(e) {
        e.preventDefault();
        
        // Log form data for debugging
        let formData = $(this).serialize();
        console.log('Form data:', formData);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response);
                if (response.status === 'success') {
                    Toast.fire({
                        icon: 'success',
                        title: 'Policy updated successfully'
                    }).then(function() {
                        window.location.href = 'index.php';
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message || 'An error occurred',
                        icon: 'error'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error details:', {xhr, status, error});
                let errorMessage = 'An error occurred';
                try {
                    let response = JSON.parse(xhr.responseText);
                    if (response && response.message) {
                        errorMessage = response.message;
                    }
                } catch (e) {
                    errorMessage = xhr.responseText || error;
                }
                
                Swal.fire({
                    title: 'Error!',
                    text: errorMessage,
                    icon: 'error'
                });
            }
        });
    });
});
</script>
