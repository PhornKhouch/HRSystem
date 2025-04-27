<?php
session_start();

// Initialize CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

    include("../../root/Header.php");
    include("../../Config/conect.php");
?>

<!-- Add SweetAlert2 CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<!-- Add custom CSS -->
<link href="../../Style/staffprofile.css" rel="stylesheet">
<link href="../../style/career.css" rel="stylesheet">

<div class="container-fluid mt-3">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Staff Profile</h5>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        <div class="card-body">
            <form id="staffForm" action="../../action/StaffProfile/create.php" method="POST" enctype="multipart/form-data">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#personalInfo">
                            <i class="fas fa-user"></i> Personal Information
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#jobInfo">
                            <i class="fas fa-briefcase"></i> Job Information
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#contactInfo">
                            <i class="fas fa-address-book"></i> Contact Information
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#familyInfo">
                            <i class="fas fa-users"></i> Family
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#educationInfo">
                            <i class="fas fa-graduation-cap"></i> Education
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#documentInfo">
                            <i class="fas fa-file-alt"></i> Staff Document
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Personal Information Tab -->
                    <div class="tab-pane fade show active" id="personalInfo">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="empCode" class="form-label  ">Employee Code</label>
                                <input type="text" class="form-control" id="empCode" name="empCode"  >
                            </div>
                            <div class="col-md-6">
                                <label for="empName" class="form-label  ">Employee Name</label>
                                <input type="text" class="form-control" id="empName" name="empName"  >
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="gender" class="form-label  ">Gender</label>
                                <select class="form-select" id="gender" name="gender"  >
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="dob" class="form-label  ">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob"  >
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="photo" class="form-label">Profile Photo</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                            </div>
                            <div class="col-md-6">
                            <label for="salary" class="form-label  ">Salary</label>
                            <input type="number" class="form-control" id="salary" name="salary"  >
                            </div>
                            <div class="col-md-6">
                                <div class="text-center mt-2">
                                    <div class="img-preview border rounded p-2">
                                        <img id="photoPreview" src="../../assets/images/images.jpg" alt="Profile Preview" class="img-fluid rounded">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Job Information Tab -->
                    <div class="tab-pane fade" id="jobInfo">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="company" class="form-label  ">Company</label>
                                <!-- <input type="text" class="form-control" id="company" name="company"  > -->
                                <select class="form-select" id="company" name="company"  >
                                    <option value="">Select Company</option>
                                    <?php
                                    $stmt = $con->prepare("SELECT Code, Description FROM hrcompany where Status = 'Active'");
                                    if ($stmt) {
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        while($row = $result->fetch_assoc()) 
                                        {
                                            ?>
                                                 <option value="<?php echo $row['Code']; ?>"><?php echo $row['Description']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="department" class="form-label  ">Department</label>
                                <!-- <input type="text" class="form-control" id="department" name="department"  > -->
                                <select class="form-select" id="department" name="department"  >
                                    <option value="">Select Department</option>
                                    <?php
                                    $stmt = $con->prepare("SELECT Code, Description FROM hrdepartment where Status = 'Active'");
                                    if ($stmt) {
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        while($row = $result->fetch_assoc()) 
                                        {
                                            ?>
                                                 <option value="<?php echo $row['Code']; ?>"><?php echo $row['Description']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="position" class="form-label  ">Position</label>
                                <!-- <input type="text" class="form-control" id="position" name="position"  > -->
                                <select class="form-select" id="position" name="position"  >
                                    <option value="">Select Position</option>
                                    <?php
                                    $stmt = $con->prepare("SELECT Code, Description FROM hrposition where Status = 'Active'");
                                    if ($stmt) {
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        while($row = $result->fetch_assoc()) 
                                        {
                                            ?>
                                                 <option value="<?php echo $row['Code']; ?>"><?php echo $row['Description']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="division" class="form-label">Division</label>
                                <!-- <input type="text" class="form-control" id="division" name="division"> -->
                                <select class="form-select" id="division" name="division">
                                    <option value="">Select Division</option>
                                    <?php
                                    $stmt = $con->prepare("SELECT Code, Description FROM hrdivision where Status = 'Active'");
                                    if ($stmt) {
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        while($row = $result->fetch_assoc()) 
                                        {
                                            ?>
                                                 <option value="<?php echo $row['Code']; ?>"><?php echo $row['Description']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="level" class="form-label">Level</label>
                                <!-- <input type="text" class="form-control" id="level" name="level"> -->
                                <select class="form-select" id="level" name="level">
                                    <option value="">Select Level</option>
                                    <?php
                                    $stmt = $con->prepare("SELECT Code, Description FROM hrlevel where Status = 'Active'");
                                    if ($stmt) {
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        while($row = $result->fetch_assoc()) 
                                        {
                                            ?>
                                                 <option value="<?php echo $row['Code']; ?>"><?php echo $row['Description']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>

                            </div>
                            <div class="col-md-4">
                                <label for="startDate" class="form-label  ">Start Date</label>
                                <input type="date" class="form-control" id="startDate" name="startDate"  >
                            </div>
                           
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="status" class="form-label  ">Status</label>
                                <select class="form-select" id="status" name="status"  >
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="lineManager" class="form-label">Line Manager</label>
                                <!-- <input type="text" class="form-control" id="lineManager" name="lineManager"> -->
                                <select name="lineManager" id="lineManager" class="form-select">
                                    <option value="">Select Line Manager</option>
                                    <?php
                                    $stmt = $con->prepare("SELECT empcode, empname FROM hrstaffprofile");
                                    if ($stmt) {
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        while($row = $result->fetch_assoc()) 
                                        {
                                            ?>
                                                 <option value="<?php echo $row['empcode']; ?>"><?php echo $row['empname']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="hod" class="form-label">Head of Department</label>
                                <!-- <input type="text" class="form-control" id="hod" name="hod"> -->
                                <select name="hod" id="hod" class="form-select">
                                    <option value="">Select Head of Department</option>
                                    <?php
                                    $stmt = $con->prepare("SELECT empcode, empname FROM hrstaffprofile");
                                    if ($stmt) {
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        while($row = $result->fetch_assoc()) 
                                        {
                                            ?>
                                                 <option value="<?php echo $row['empcode']; ?>"><?php echo $row['empname']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="telegram" class="form-label">Telegram</label>
                                <input type="text" class="form-control" id="telegram" name="telegram">
                            </div>
                            <div class="col-md-4">
                                <label for="payParamter" class="form-label">Pay Parameter</label>
                                <!-- <input type="text" class="form-control" id="payParamter" name="payParamter"> -->
                                <select name="payParamter" id="payParamter" class="form-control">
                                    <option value="">Select Pay Parameter</option>
                                    <?php
                                    $stmt = $con->prepare("SELECT id, description FROM prpaypolicy");
                                    if ($stmt) {
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        while($row = $result->fetch_assoc()) 
                                        {
                                            ?>
                                                 <option value="<?php echo $row['id']; ?>"><?php echo $row['description']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Tab -->
                    <div class="tab-pane fade" id="contactInfo">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="contact" class="form-label">Contact Number</label>
                                <input type="tel" class="form-control" id="contact" name="contact">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Family Information Tab -->
                    <div class="tab-pane fade" id="familyInfo">
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Family Members</h6>
                                <button type="button" class="btn btn-success btn-sm" id="addFamilyMember">
                                    <i class="fas fa-plus"></i> Add Member
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="familyMembersTable">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Relation Type</th>
                                                <th>Gender</th>
                                                <th>Is Tax</th>
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

                    <!-- Education Information Tab -->
                    <div class="tab-pane fade" id="educationInfo">
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Education Details</h6>
                                <button type="button" class="btn btn-success btn-sm" id="addEducation">
                                    <i class="fas fa-plus"></i> Add Education
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="educationTable">
                                        <thead>
                                            <tr>
                                                <th>Institution</th>
                                                <th>Degree</th>
                                                <th>Field of Study</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
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

                    <!-- Document Information Tab -->
                    <div class="tab-pane fade" id="documentInfo">
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Staff Documents</h6>
                                <button type="button" class="btn btn-success btn-sm" id="addDocument">
                                    <i class="fas fa-plus"></i> Add Document
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="documentsTable">
                                        <thead>
                                            <tr>
                                                <th>Document Type</th>
                                                <th>Description</th>
                                                <th>File</th>
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

                <div class="d-flex justify-content-start mt-3">
                    <button type="submit" class="btn btn-primary me-2">Save</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Family Member Modal -->
<div class="modal fade" id="familyMemberModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Family Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="familyMemberForm">
                    <input type="hidden" id="familyMemberIndex" value="">
                    <div class="mb-3">
                        <label for="relationName" class="form-label  ">Name</label>
                        <input type="text" class="form-control" id="relationName"  >
                    </div>
                    <div class="mb-3">
                        <label for="relationType" class="form-label  ">Relation Type</label>
                        <select class="form-select" id="relationType"  >
                            <option value="">Select Relation</option>
                            <option value="Spouse">Spouse</option>
                            <option value="Child">Child</option>
                            <option value="Parent">Parent</option>
                            <option value="Sibling">Sibling</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="relationGender" class="form-label  ">Gender</label>
                        <select class="form-select" id="relationGender"  >
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="isTax">
                            <label class="form-check-label" for="isTax">Include in Tax Calculation</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveFamilyMember">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Education Modal -->
<div class="modal fade" id="educationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Education Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="educationIndex">
                <div class="mb-3">
                    <label for="institution" class="form-label  ">Institution</label>
                    <input type="text" class="form-control" id="institution"  >
                </div>
                <div class="mb-3">
                    <label for="degree" class="form-label  ">Degree</label>
                    <select class="form-select" id="degree"  >
                        <option value="">Select Degree</option>
                        <option value="High School">High School</option>
                        <option value="Diploma">Diploma</option>
                        <option value="Bachelor">Bachelor's Degree</option>
                        <option value="Master">Master's Degree</option>
                        <option value="Doctorate">Doctorate</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fieldOfStudy" class="form-label  ">Field of Study</label>
                    <input type="text" class="form-control" id="fieldOfStudy"  >
                </div>
                <div class="mb-3">
                    <label for="startDate" class="form-label  ">Start Date</label>
                    <input type="date" class="form-control" id="startDate"  >
                </div>
                <div class="mb-3">
                    <label for="endDate" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="endDate">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveEducation">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Document Modal -->
<div class="modal fade" id="documentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="documentForm">
                    <input type="hidden" id="documentIndex" value="">
                    <div class="mb-3">
                        <label for="docType" class="form-label  ">Document Type</label>
                        <select class="form-select" id="docType"  >
                            <option value="">Select Document Type</option>
                            <option value="Contract">Contract</option>
                            <option value="CV">CV</option>
                            <option value="Certificate">Certificate</option>
                            <option value="IDCard">ID card</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="docFile" class="form-label  ">Document File</label>
                        <input type="file" class="form-control" id="docFile"  >
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveDocument">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Document View Modal -->
<div class="modal fade" id="documentViewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Document Type:</label>
                        <p id="viewDocType" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Description:</label>
                        <p id="viewDescription" class="form-control-plaintext"></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label">File Name:</label>
                        <p id="viewFileName" class="form-control-plaintext"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a id="downloadDocument" href="#" class="btn btn-primary" download>
                    <i class="fas fa-download"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize Toast notification
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    // Form validation before submission
    function validateForm() {
        const  Fields = {
            'empCode': 'Employee Code',
            'empName': 'Employee Name',
            'gender': 'Gender',
            'dob': 'Date of Birth',
            'position': 'Position',
            'department': 'Department',
            'startDate': 'Start Date',
            'status': 'Status'
        };

        let isValid = true;
        let firstError = null;

        // Check   fields
        for (let [fieldId, fieldName] of Object.entries( Fields)) {
            const field = $('#' + fieldId);
            const value = field.val();
            
            if (!value || value.trim() === '') {
                isValid = false;
                field.addClass('is-invalid');
                if (!firstError) firstError = field;
                
                // Add error message below the field
                if (!field.next('.invalid-feedback').length) {
                    field.after(`<div class="invalid-feedback">${fieldName} is  </div>`);
                }
            } else {
                field.removeClass('is-invalid');
                field.next('.invalid-feedback').remove();
            }
        }

        // Scroll to first error if any
        if (firstError) {
            firstError.focus();
            $('html, body').animate({
                scrollTop: firstError.offset().top - 100
            }, 500);
        }

        return isValid;
    }

    // Remove validation styling on input
    $('input, select').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });
    
    // Photo preview with validation
    $('#photo').change(function() {
        const file = this.files[0];
        const preview = $('#photoPreview');
        
        if (file) {
            // Check if file is an image
            if (!file.type.startsWith('image/')) {
                Toast.fire({
                    icon: 'error',
                    title: 'Please select an image file'
                });
                this.value = '';
                preview.attr('src', '../../assets/images/default-avatar.png');
                return;
            }

            // Check file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                Toast.fire({
                    icon: 'error',
                    title: 'Image size should be less than 2MB'
                });
                this.value = '';
                preview.attr('src', '../../assets/images/default-avatar.png');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        } else {
            preview.attr('src', '../../assets/images/default-avatar.png');
        }
    });




    //#region Family Members Management
    let familyMembers = [];

    // Initialize family member modal
    const familyMemberModal = new bootstrap.Modal(document.getElementById('familyMemberModal'));

    // Add family member button click
    $('#addFamilyMember').click(function() {
        $('#familyMemberIndex').val('');
        $('#familyMemberForm')[0].reset();
        familyMemberModal.show();
    });

    // Save family member
    $('#saveFamilyMember').click(function() {
        const form = $('#familyMemberForm');
        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }

        const index = $('#familyMemberIndex').val();
        const member = {
            relationName: $('#relationName').val(),
            relationType: $('#relationType').val(),
            gender: $('#relationGender').val(),
            isTax: $('#isTax').is(':checked') ? 1 : 0
        };

        if (index === '') {
            // Add new member
            familyMembers.push(member);
        } else {
            // Update existing member
            familyMembers[parseInt(index)] = member;
        }

        updateFamilyMembersTable();
        familyMemberModal.hide();
        
        Toast.fire({
            icon: 'success',
            title: index === '' ? 'Family member added' : 'Family member updated'
        });
    });

    // Update family members table
    function updateFamilyMembersTable() {
        const tbody = $('#familyMembersTable tbody');
        tbody.empty();

        familyMembers.forEach((member, index) => {
            tbody.append(`
                <tr>
                    <td>${member.relationName}</td>
                    <td>${member.relationType}</td>
                    <td>${member.gender}</td>
                    <td>${member.isTax ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>'}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-secondary edit-family" data-index="${index}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger delete-family" data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    // Edit family member
    $(document).on('click', '.edit-family', function() {
        const index = $(this).data('index');
        const member = familyMembers[index];

        $('#familyMemberIndex').val(index);
        $('#relationName').val(member.relationName);
        $('#relationType').val(member.relationType);
        $('#relationGender').val(member.gender);
        $('#isTax').prop('checked', member.isTax === 1);

        familyMemberModal.show();
    });

    // Delete family member
    $(document).on('click', '.delete-family', function() {
        const index = $(this).data('index');
        
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
                familyMembers.splice(index, 1);//index remove
                updateFamilyMembersTable();
                Toast.fire({
                    icon: 'success',
                    title: 'Family member removed'
                });
            }
        });
    });

    //#endregion

    //#region Document Management
    let documents = [];

    // Initialize document modal
    const documentModal = new bootstrap.Modal(document.getElementById('documentModal'));

    // Add document button click
    $('#addDocument').click(function() {
        $('#documentIndex').val('');
        $('#documentForm')[0].reset();
        documentModal.show();
    });

    // Save document
    $('#saveDocument').click(function() {
        const form = $('#documentForm');
        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }

        const index = $('#documentIndex').val();
        const document = {
            docType: $('#docType').val(),
            description: $('#description').val(),
            file: $('#docFile')[0].files[0]
        };

        if (index === '') {
            // Add new document
            documents.push(document);
        } else {
            // Update existing document
            documents[parseInt(index)] = document;
        }

        updateDocumentsTable();
        documentModal.hide();
        
        Toast.fire({
            icon: 'success',
            title: index === '' ? 'Document added' : 'Document updated'
        });
    });

    // Update documents table
    function updateDocumentsTable() {
        const tbody = $('#documentsTable tbody');
        tbody.empty();

        documents.forEach((document, index) => {
            tbody.append(`
                <tr>
                    <td>${document.docType}</td>
                    <td>${document.description}</td>
                    <td>${document.file.name}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info view-document" data-index="${index}">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary edit-document" data-index="${index}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger delete-document" data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    // Initialize document view modal
    const documentViewModal = new bootstrap.Modal(document.getElementById('documentViewModal'));

    // View document
    $(document).on('click', '.view-document', function() {
        const index = $(this).data('index');
        const document = documents[index];
        
        $('#viewDocType').text(document.docType);
        $('#viewDescription').text(document.description || 'No description');
        $('#viewFileName').text(document.file.name);
        
        // Create object URL for download
        const objectUrl = URL.createObjectURL(document.file);
        
        // Set up download link
        $('#downloadDocument').attr('href', objectUrl);
        $('#downloadDocument').attr('download', document.file.name);
        
        documentViewModal.show();
        
        // Clean up object URL when modal is hidden
        $('#documentViewModal').one('hidden.bs.modal', function() {
            URL.revokeObjectURL(objectUrl);
        });
    });

    // Edit document
    $(document).on('click', '.edit-document', function() {
        const index = $(this).data('index');
        const document = documents[index];

        $('#documentIndex').val(index);
        $('#docType').val(document.docType);
        $('#description').val(document.description);
        $('#docFile').val('');

        documentModal.show();
    });

    // Delete document
    $(document).on('click', '.delete-document', function() {
        const index = $(this).data('index');
        
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
                documents.splice(index, 1);
                updateDocumentsTable();
                Toast.fire({
                    icon: 'success',
                    title: 'Document removed'
                });
            }
        });
    });

    //#endregion

    // Education Management
    let educationList = [];
    const educationModal = new bootstrap.Modal(document.getElementById('educationModal'));

    // Add education
    $('#addEducation').click(function() {
        $('#educationIndex').val('');
        $('#institution').val('');
        $('#degree').val('');
        $('#fieldOfStudy').val('');
        $('#startDate').val('');
        $('#endDate').val('');
        educationModal.show();
    });

    // Save education
    $('#saveEducation').click(function() {
        const index = $('#educationIndex').val();
        const education = {
            institution: $('#institution').val(),
            degree: $('#degree').val(),
            fieldOfStudy: $('#fieldOfStudy').val(),
            startDate: $('#startDate').val(),
            endDate: $('#endDate').val() || null
        };

        if (!education.institution || !education.degree || !education.fieldOfStudy || !education.startDate) {
            Toast.fire({
                icon: 'error',
                title: 'Please fill in all   fields'
            });
            return;
        }

        if (index === '') {
            educationList.push(education);
        } else {
            educationList[parseInt(index)] = education;
        }

        updateEducationTable();
        educationModal.hide();
        Toast.fire({
            icon: 'success',
            title: 'Education details saved'
        });
    });

    // Update education table
    function updateEducationTable() {
        const tbody = $('#educationTable tbody');
        tbody.empty();

        educationList.forEach((education, index) => {
            tbody.append(`
                <tr>
                    <td>${education.institution}</td>
                    <td>${education.degree}</td>
                    <td>${education.fieldOfStudy}</td>
                    <td>${education.startDate}</td>
                    <td>${education.endDate || '-'}</td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm edit-education" data-index="${index}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm delete-education" data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    // Edit education
    $(document).on('click', '.edit-education', function() {
        const index = $(this).data('index');
        const education = educationList[index];

        $('#educationIndex').val(index);
        $('#institution').val(education.institution);
        $('#degree').val(education.degree);
        $('#fieldOfStudy').val(education.fieldOfStudy);
        $('#startDate').val(education.startDate);
        $('#endDate').val(education.endDate || '');

        educationModal.show();
    });

    // Delete education
    $(document).on('click', '.delete-education', function() {
        const index = $(this).data('index');
        
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
                educationList.splice(index, 1);
                updateEducationTable();
                Toast.fire({
                    icon: 'success',
                    title: 'Education record removed'
                });
            }
        });
    });

    // Form submission
    $('#staffForm').submit(function(e) {
        e.preventDefault();
        
        // Validate form
        if (!validateForm()) {
            Toast.fire({
                icon: 'error',
                title: 'Please fill in all   fields'
            });
            return;
        }
        
        let formData = new FormData(this);
        
        // Add family members data
        formData.append('familyMembers', JSON.stringify(familyMembers));

        // Add education data
        formData.append('education', JSON.stringify(educationList));

        // Add documents data and files
        formData.append('documents', JSON.stringify(documents));
        documents.forEach((doc, index) => {
            if (doc.file) {
                formData.append('document_' + index, doc.file);
            }
        });

        // Debug: Log form data
        console.log('Form Data:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        $.ajax({
            url: '../../action/StaffProfile/create.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                console.log('Success Response:', response);
                if (response.status === 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = 'index.php';
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message || 'Error creating staff profile',
                        icon: 'error'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error Status:', status);
                console.error('Error:', error);
                console.error('Response Text:', xhr.responseText);
                
                let errorMessage = 'Error creating staff profile';
                
                try {
                    const response = JSON.parse(xhr.responseText);
                    errorMessage = response.message || errorMessage;
                } catch (e) {
                    errorMessage += ': ' + (error || 'Unknown error');
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