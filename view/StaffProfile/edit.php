<!-- Add SweetAlert2 CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<?php
session_start();

// Initialize CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include("../../root/Header.php");
include("../../Config/conect.php");

// Get empcode from URL parameter and validate
$empcode = isset($_GET['empcode']) ? $_GET['empcode'] : '';

if (empty($empcode)) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No employee code provided'
        }).then(() => {
            window.location.href = 'index.php';
        });
    </script>";
    exit;
}

// Fetch staff profile data
try {
    // Fetch main staff profile
    $stmt = $con->prepare("SELECT * FROM hrstaffprofile WHERE EmpCode = ?");
    $stmt->bind_param("s", $empcode);
    $stmt->execute();
    $result = $stmt->get_result();
    $staffData = $result->fetch_assoc();

    if (!$staffData) {
        throw new Exception("Employee not found");
    }

    // Fetch family members
    $stmt = $con->prepare("SELECT * FROM hrfamily WHERE EmpCode = ?");
    $stmt->bind_param("s", $empcode);
    $stmt->execute();
    $result = $stmt->get_result();
    $familyData = [];
    while ($row = $result->fetch_assoc()) {
        $familyData[] = $row;
    }

    // Fetch education history
    $stmt = $con->prepare("SELECT * FROM hreducation WHERE EmpCode = ?");
    $stmt->bind_param("s", $empcode);
    $stmt->execute();
    $result = $stmt->get_result();
    $educationData = [];
    while ($row = $result->fetch_assoc()) {
        $educationData[] = $row;
    }

    // Fetch documents
    $stmt = $con->prepare("SELECT * FROM hrstaffdocument WHERE EmpCode = ?");
    $stmt->bind_param("s", $empcode);
    $stmt->execute();
    $result = $stmt->get_result();
    $documentData = [];
    while ($row = $result->fetch_assoc()) {
        $documentData[] = $row;
    }

} catch (Exception $e) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '" . addslashes($e->getMessage()) . "'
        }).then(() => {
            window.location.href = 'index.php';
        });
    </script>";
    exit;
}
?>


<!-- Add custom CSS -->
<link href="../../Style/staffprofile.css" rel="stylesheet">

<div class="container-fluid mt-3">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Staff Profile</h5>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        <div class="card-body">
            <form id="staffForm" action="../../action/StaffProfile/update.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="empcode" value="<?php echo htmlspecialchars($empcode); ?>">
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
                                <label for="empCode" class="form-label">Employee Code</label>
                                <input type="text" class="form-control" id="empCode" name="empCode" value="<?php echo htmlspecialchars($staffData['EmpCode']); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="empName" class="form-label">Employee Name</label>
                                <input type="text" class="form-control" id="empName" name="empName" value="<?php echo htmlspecialchars($staffData['EmpName']); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male" <?php echo $staffData['Gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo $staffData['Gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($staffData['Dob']); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="photo" class="form-label">Profile Photo</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/jpeg,image/png">
                                <?php if (!empty($staffData['Photo'])): ?>
                                    <input type="hidden" name="oldPhoto" value="<?php echo htmlspecialchars($staffData['Photo']); ?>">
                                    <div class="mt-2">
                                        <img src="../../<?php echo htmlspecialchars($staffData['Photo']); ?>" alt="Current Profile Photo" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                <?php endif; ?>
                                <div class="form-text">Maximum file size: 2MB. Allowed formats: JPG, PNG</div>
                            </div>
                            <div class="col-md-6">
                                <label for="salary" class="form-label">Salary</label>
                                <input type="number" class="form-control" id="salary" name="salary" value="<?php echo htmlspecialchars($staffData['Salary']); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Job Information Tab -->
                    <div class="tab-pane fade" id="jobInfo">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="company" class="form-label">Company</label>
                                <select class="form-select" id="company" name="company">
                                    <option value="">Select Company</option>
                                    <?php
                                    $stmt = $con->prepare("SELECT Code, Description FROM hrcompany where Status = 'Active'");
                                    if ($stmt) {
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        while($row = $result->fetch_assoc()) 
                                        {
                                            ?>
                                                 <option value="<?php echo $row['Code']; ?>" <?php echo $staffData['Company'] == $row['Code'] ? 'selected' : ''; ?>><?php echo $row['Description']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="department" class="form-label">Department</label>
                                <select class="form-select" id="department" name="department">
                                    <option value="">Select Department</option>
                                    <?php
                                    $stmt = $con->prepare("SELECT Code, Description FROM hrdepartment where Status = 'Active'");
                                    if ($stmt) {
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        while($row = $result->fetch_assoc()) 
                                        {
                                            ?>
                                                 <option value="<?php echo $row['Code']; ?>" <?php echo $staffData['Department'] == $row['Code'] ? 'selected' : ''; ?>><?php echo $row['Description']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="position" class="form-label">Position</label>
                                <select class="form-select" id="position" name="position">
                                    <option value="">Select Position</option>
                                    <?php
                                    $stmt = $con->prepare("SELECT Code, Description FROM hrposition where Status = 'Active'");
                                    if ($stmt) {
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        while($row = $result->fetch_assoc()) 
                                        {
                                            ?>
                                                 <option value="<?php echo $row['Code']; ?>" <?php echo $staffData['Position'] == $row['Code'] ? 'selected' : ''; ?>><?php echo $row['Description']; ?></option>
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
                                                 <option value="<?php echo $row['Code']; ?>" <?php echo $staffData['Division'] == $row['Code'] ? 'selected' : ''; ?>><?php echo $row['Description']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="level" class="form-label">Level</label>
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
                                                 <option value="<?php echo $row['Code']; ?>" <?php echo $staffData['Level'] == $row['Code'] ? 'selected' : ''; ?>><?php echo $row['Description']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>

                            </div>
                            <div class="col-md-4">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" name="startDate" value="<?php echo htmlspecialchars($staffData['StartDate']); ?>">
                            </div>
                           
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="Active" <?php echo $staffData['Status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="Inactive" <?php echo $staffData['Status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="lineManager" class="form-label">Line Manager</label>
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
                                                 <option value="<?php echo $row['empcode']; ?>" <?php echo $staffData['LineManager'] == $row['empcode'] ? 'selected' : ''; ?>><?php echo $row['empname']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="hod" class="form-label">Head of Department</label>
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
                                                 <option value="<?php echo $row['empcode']; ?>" <?php echo $staffData['Hod'] == $row['empcode'] ? 'selected' : ''; ?>><?php echo $row['empname']; ?></option>
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
                                <input type="text" class="form-control" id="telegram" name="telegram" value="<?php echo htmlspecialchars($staffData['Telegram']); ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="payParamter" class="form-label">Pay Parameter</label>
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
                                                 <option value="<?php echo $row['id']; ?>" <?php echo $staffData['PayParameter'] == $row['id'] ? 'selected' : ''; ?>><?php echo $row['description']; ?></option>
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
                                <input type="tel" class="form-control" id="contact" name="contact" value="<?php echo htmlspecialchars($staffData['Contact']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($staffData['Email']); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($staffData['Address']); ?></textarea>
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
                                            <?php foreach($familyData as $family) { ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($family['RelationName']); ?></td>
                                                    <td><?php echo htmlspecialchars($family['RelationType']); ?></td>
                                                    <td><?php echo htmlspecialchars($family['Gender']); ?></td>
                                                    <td><?php echo htmlspecialchars($family['IsTax']) == 1 ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>'; ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-secondary edit-family" data-index="<?php echo array_search($family, $familyData); ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger delete-family" data-index="<?php echo array_search($family, $familyData); ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
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
                                            <?php foreach($educationData as $education) { ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($education['Institution']); ?></td>
                                                    <td><?php echo htmlspecialchars($education['Degree']); ?></td>
                                                    <td><?php echo htmlspecialchars($education['FieldOfStudy']); ?></td>
                                                    <td><?php echo htmlspecialchars($education['StartDate']); ?></td>
                                                    <td><?php echo htmlspecialchars($education['EndDate']); ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-sm edit-education" data-index="<?php echo array_search($education, $educationData); ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm delete-education" data-index="<?php echo array_search($education, $educationData); ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
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
                                            <?php foreach($documentData as $document) { ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($document['DocType']); ?></td>
                                                    <td><?php echo htmlspecialchars($document['Description']); ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-info view-document" data-index="<?php echo array_search($document, $documentData); ?>">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-secondary edit-document" data-index="<?php echo array_search($document, $documentData); ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger delete-document" data-index="<?php echo array_search($document, $documentData); ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
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
<div class="modal fade" id="familyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Family Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="familyForm">
                    <input type="hidden" id="familyIndex">
                    <div class="mb-3">
                        <label for="familyName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="familyName" name="familyName" placeholder="Enter family member name" required>
                    </div>
                    <div class="mb-3">
                        <label for="relation" class="form-label">Relation Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="relation" name="relation" required>
                            <option value="">Select Relation</option>
                            <option value="Spouse">Spouse</option>
                            <option value="Child">Child</option>
                            <option value="Parent">Parent</option>
                            <option value="Sibling">Sibling</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="familyGender" class="form-label">Gender <span class="text-danger">*</span></label>
                        <select class="form-select" id="familyGender" name="familyGender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="isTax" name="isTax">
                            <label class="form-check-label" for="isTax">Tax Deduction</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                <h5 class="modal-title">Add Education</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="educationForm">
                    <input type="hidden" id="educationIndex">
                    <div class="mb-3">
                        <label for="institution" class="form-label">Institution <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="institution" name="institution" placeholder="Enter institution name" required>
                    </div>
                    <div class="mb-3">
                        <label for="degree" class="form-label">Degree <span class="text-danger">*</span></label>
                        <select class="form-select" id="degree" name="degree" required>
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
                        <label for="fieldOfStudy" class="form-label">Field of Study <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="fieldOfStudy" name="fieldOfStudy" placeholder="Enter field of study" required>
                    </div>
                    <div class="mb-3">
                        <label for="startDate" class="form-label">Start Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="startDate" name="startDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="endDate" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="endDate" name="endDate">
                        <div class="form-text">Leave empty if currently studying</div>
                    </div>
                </form>
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
                        <label for="docType" class="form-label">Document Type</label>
                        <select class="form-select" id="docType">
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
                        <label for="docFile" class="form-label">Document File</label>
                        <input type="file" class="form-control" id="docFile" accept="application/pdf, image/*">
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
                preview.attr('src', '../../assets/images/images.jpg');
                return;
            }

            // Check file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                Toast.fire({
                    icon: 'error',
                    title: 'Image size should be less than 2MB'
                });
                this.value = '';
                preview.attr('src', '../../assets/images/images.jpg');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        } else {
            preview.attr('src', '../../assets/images/images.jpg');
        }
    });




    //#region Family Members Management
    let familyMembers = <?php echo json_encode($familyData); ?>;

    // Initialize family member modal
    const familyMemberModal = new bootstrap.Modal(document.getElementById('familyModal'));

    // Add family member button click
    $('#addFamilyMember').click(function() {
        $('#familyIndex').val('');
        $('#familyForm')[0].reset();
        familyMemberModal.show();
    });

    // Save family member
    $('#saveFamilyMember').click(function() {
        // Check form validity
        if (!$('#familyForm')[0].checkValidity()) {
            $('#familyForm')[0].reportValidity();
            return;
        }

        // Get form values and trim whitespace
        const name = $('#familyName').val().trim();
        const relation = $('#relation').val();
        const gender = $('#familyGender').val();
        const isTax = $('#isTax').prop('checked') ? 1 : 0;

        // Additional validation
        if (!name) {
            Toast.fire({
                icon: 'error',
                title: 'Family member name is required'
            });
            return;
        }

        const familyMember = {
            name: name,
            relation: relation,
            gender: gender,
            isTax: isTax
        };

        const index = $('#familyIndex').val();
        
        if (index === '') {
            // Add new family member
            familyMembers.push(familyMember);
        } else {
            // Update existing family member
            familyMembers[parseInt(index)] = familyMember;
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
            const row = `
                <tr>
                    <td>${member.name}</td>
                    <td>${member.relation}</td>
                    <td>${member.gender}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary edit-family" data-index="${index}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger delete-family" data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
            tbody.append(row);
        });
    }

    // Edit family member
    $(document).on('click', '.edit-family', function() {
        const index = $(this).data('index');
        const member = familyMembers[index];

        $('#familyIndex').val(index);
        $('#familyName').val(member.name);
        $('#relation').val(member.relation);
        $('#familyGender').val(member.gender);
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
                familyMembers.splice(index, 1);
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
    let documents = <?php echo json_encode($documentData); ?>;

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
    let educationList = <?php echo json_encode($educationData); ?>;
    const educationModal = new bootstrap.Modal(document.getElementById('educationModal'));

    // Add education
    $('#addEducation').click(function() {
        // Reset form
        $('#educationModal form')[0].reset();
        $('#educationIndex').val('');
        
        // Set modal title
        $('#educationModal .modal-title').text('Add Education');
        
        // Show modal
        educationModal.show();
    });

    // Save education
    $('#saveEducation').click(function() {
        // Check HTML5 form validation
        if (!$('#educationForm')[0].checkValidity()) {
            $('#educationForm')[0].reportValidity();
            return;
        }

        // Get form values and remove any extra whitespace
        const institution = $('#institution').val().trim();
        const degree = $('#degree').val();  // Don't trim select values
        const fieldOfStudy = $('#fieldOfStudy').val().trim();
        const startDate = $('#startDate').val();  // Don't trim date values
        const endDate = $('#endDate').val();  // Don't trim date values

        // Create education object
        const education = {
            institution: institution,
            degree: degree,
            fieldOfStudy: fieldOfStudy,
            startDate: startDate,
            endDate: endDate || null
        };

        const index = $('#educationIndex').val();
        if (index === '') {
            educationList.push(education);
        } else {
            educationList[parseInt(index)] = education;
        }

        updateEducationTable();
        educationModal.hide();
        
        Toast.fire({
            icon: 'success',
            title: index === '' ? 'Education added successfully' : 'Education updated successfully'
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

        // Set modal title
        $('#educationModal .modal-title').text('Edit Education');
        
        // Set form values
        $('#educationIndex').val(index);
        $('#institution').val(education.institution);
        $('#degree').val(education.degree);
        $('#fieldOfStudy').val(education.fieldOfStudy);
        $('#startDate').val(education.startDate);
        $('#endDate').val(education.endDate || '');

        // Show modal
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
                title: 'Please fill in all required fields'
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

        // Show loading state
        Swal.fire({
            title: 'Updating...',
            text: 'Please wait while we update the staff profile',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: '../../action/StaffProfile/update.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                Swal.close();
                
                if (response.status === 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message || 'Staff profile updated successfully',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = 'index.php';
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message || 'Error updating staff profile',
                        icon: 'error'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                let errorMessage = 'Error updating staff profile';
                try {
                    // Try to parse the error response as JSON
                    const response = JSON.parse(xhr.responseText);
                    errorMessage = response.message || errorMessage;
                } catch (e) {
                    // If parsing fails, use the raw error text
                    if (xhr.responseText && xhr.responseText.trim()) {
                        // Remove any HTML tags from the error message
                        errorMessage = xhr.responseText.replace(/<[^>]+>/g, '');
                    } else {
                        errorMessage += ': ' + (error || 'Unknown error');
                    }
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