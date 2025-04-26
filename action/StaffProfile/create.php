<?php
include("../../Config/conect.php");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Debug: Log received data
        error_log("Received POST data: " . print_r($_POST, true));
        error_log("Received FILES data: " . print_r($_FILES, true));

        // Validate required fields
        $requiredFields = [
            'empCode' => 'Employee Code',
            'empName' => 'Employee Name',
            'gender' => 'Gender',
            'dob' => 'Date of Birth',
            'position' => 'Position',
            'department' => 'Department',
            'startDate' => 'Start Date',
            'status' => 'Status',
            'salary' => 'Salary'
        ];

        $missingFields = [];
        foreach ($requiredFields as $field => $label) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                $missingFields[] = $label;
            }
        }

        if (!empty($missingFields)) {
            throw new Exception('Missing required fields: ' . implode(', ', $missingFields));
        }

        // Start transaction
        $con->begin_transaction();

        // Get form data
        $empCode = trim($_POST['empCode']);
        $empName = trim($_POST['empName']);
        $gender = trim($_POST['gender']);
        $dob = trim($_POST['dob']);
        $company = trim($_POST['company']);
        $position = trim($_POST['position']);
        $department = trim($_POST['department']);
        $division = isset($_POST['division']) ? trim($_POST['division']) : null;
        $startDate = trim($_POST['startDate']);
        $status = trim($_POST['status']);
        $salary = trim($_POST['salary']);
        $lineManager = isset($_POST['lineManager']) ? trim($_POST['lineManager']) : null;
        $hod = isset($_POST['hod']) ? trim($_POST['hod']) : null;
        $contact = isset($_POST['contact']) ? trim($_POST['contact']) : null;
        $email = isset($_POST['email']) ? trim($_POST['email']) : null;
        $address = isset($_POST['address']) ? trim($_POST['address']) : null;
        $telegram = isset($_POST['telegram']) ? trim($_POST['telegram']) : null;
        $payParameter = isset($_POST['payParameter']) ? trim($_POST['payParameter']) : null;

        // Validate email if provided
        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }

        // Handle photo upload
        $photoPath = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../Uploads/staff_photos/';
            if (!file_exists($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    throw new Exception('Failed to create upload directory');
                }
            }

            $fileExtension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            
            if (!in_array($fileExtension, $allowedExtensions)) {
                throw new Exception('Invalid file type. Only JPG, JPEG, and PNG files are allowed');
            }

            // Check file size (max 2MB)
            if ($_FILES['photo']['size'] > 7 * 1024 * 1024) {
                throw new Exception('File size exceeds 7MB limit');
            }

            $photoName = $empCode . '_' . time() . '.' . $fileExtension;
            $targetPath = $uploadDir . $photoName;

            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
                throw new Exception('Error uploading photo');
            }
            $photoPath = 'Uploads/staff_photos/' . $photoName;
        }

        // Check if employee code already exists
        $checkStmt = $con->prepare("SELECT EmpCode FROM hrstaffprofile WHERE EmpCode = ?");
        if (!$checkStmt) {
            throw new Exception('Database error: ' . $con->error);
        }

        $checkStmt->bind_param("s", $empCode);
        if (!$checkStmt->execute()) {
            throw new Exception('Database error: ' . $checkStmt->error);
        }

        $result = $checkStmt->get_result();
        if ($result->num_rows > 0) {
            throw new Exception('Employee code already exists');
        }
        $checkStmt->close();

        // Insert staff profile
        $sql = "INSERT INTO hrstaffprofile (
            EmpCode, EmpName, Gender, Dob, Position, Department, Division, 
            StartDate, Status, LineManager, Hod, Contact, Email, Address, Photo, Salary,
            Telegram, PayParameter, Company
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?
        )";

        $stmt = $con->prepare($sql);
        if (!$stmt) {
            throw new Exception('Database error: ' . $con->error);
        }

        $stmt->bind_param("sssssssssssssssdsss",
            $empCode, $empName, $gender, $dob, $position, $department, $division,
            $startDate, $status, $lineManager, $hod, $contact, $email, $address, $photoPath, $salary,
            $telegram, $payParameter, $company
        );

        if (!$stmt->execute()) {
            throw new Exception('Error creating staff profile: ' . $stmt->error);
        }

        $stmt->close();

        //insert into career history
        $CareerHistoryType = "NEW";
        $employeeID = $empCode;
        $positionTitle = $position;
        $department = $department;
        $startDate = $startDate;
        $endDate = null;
        $remark = null;
        $increase = null;

        $stmt = $con->prepare("INSERT INTO careerhistory (
            CareerHistoryType, EmployeeID, PositionTitle, Department,
            StartDate, EndDate, Remark, Increase,
            CreatedAt, UpdatedAt
        ) VALUES (
            ?, ?, ?, ?,
            ?, ?, ?, ?,
            CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
        )");
        if (!$stmt) {
            throw new Exception('Database error: ' . $con->error);
        }

        $stmt->bind_param("sssssssd",
            $CareerHistoryType, $employeeID, $positionTitle, $department,
            $startDate, $endDate, $remark, $increase
        );

        if (!$stmt->execute()) {
            throw new Exception('Error creating career history: ' . $stmt->error);
        }

        $stmt->close();

        // Handle family members
        if (isset($_POST['familyMembers'])) {
            $familyMembers = json_decode($_POST['familyMembers'], true);
            
            if ($familyMembers) {
                $familyStmt = $con->prepare("INSERT INTO hrfamily (
                    EmpCode, RelationName, RelationType, Gender, IsTax
                ) VALUES (?, ?, ?, ?, ?)");

                if (!$familyStmt) {
                    throw new Exception('Database error: ' . $con->error);
                }

                foreach ($familyMembers as $member) {
                    // Validate family member data
                    if (empty($member['relationName'])) {
                        throw new Exception('Family member name is required');
                    }
                    if (empty($member['relationType'])) {
                        throw new Exception('Family member relation type is required');
                    }
                    if (empty($member['gender'])) {
                        throw new Exception('Family member gender is required');
                    }

                    $familyStmt->bind_param("ssssi",
                        $empCode,
                        $member['relationName'],
                        $member['relationType'],
                        $member['gender'],
                        $member['isTax']
                    );

                    if (!$familyStmt->execute()) {
                        throw new Exception('Error adding family member: ' . $familyStmt->error);
                    }
                }

                $familyStmt->close();
            }
        }

        // Handle education records
        if (isset($_POST['education'])) {
            $educationRecords = json_decode($_POST['education'], true);
            
            if ($educationRecords) {
                $educationStmt = $con->prepare("INSERT INTO hreducation (
                    EmpCode, Institution, Degree, FieldOfStudy, StartDate, EndDate
                ) VALUES (?, ?, ?, ?, ?, ?)");

                if (!$educationStmt) {
                    throw new Exception('Database error: ' . $con->error);
                }

                foreach ($educationRecords as $education) {
                    if (empty($education['institution'])) {
                        throw new Exception('Institution name is required');
                    }
                    if (empty($education['degree'])) {
                        throw new Exception('Degree is required');
                    }
                    if (empty($education['fieldOfStudy'])) {
                        throw new Exception('Field of study is required');
                    }
                    if (empty($education['startDate'])) {
                        throw new Exception('Start date is required');
                    }

                    $educationStmt->bind_param("ssssss",
                        $empCode,
                        $education['institution'],
                        $education['degree'],
                        $education['fieldOfStudy'],
                        $education['startDate'],
                        $education['endDate']
                    );

                    if (!$educationStmt->execute()) {
                        throw new Exception('Error adding education record: ' . $educationStmt->error);
                    }
                }

                $educationStmt->close();
            }
        }

        // Handle documents
        if (isset($_POST['documents'])) {
            $documents = json_decode($_POST['documents'], true);
            
            if ($documents) {
                // Create documents upload directory
                $docUploadDir = '../../Uploads/staff_documents/';
                if (!file_exists($docUploadDir)) {
                    if (!mkdir($docUploadDir, 0777, true)) {
                        throw new Exception('Failed to create documents upload directory');
                    }
                }

                $docStmt = $con->prepare("INSERT INTO hrstaffdocument (
                    EmpCode, DocType, Description, Photo
                ) VALUES (?, ?, ?, ?)");

                if (!$docStmt) {
                    throw new Exception('Database error: ' . $con->error);
                }

                foreach ($documents as $index => $document) {
                    // Validate document data
                    if (empty($document['docType'])) {
                        throw new Exception('Document type is required');
                    }

                    // Handle document file
                    $fileKey = "document_" . $index;
                    if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
                        $fileExtension = strtolower(pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION));
                        $docName = $empCode . '_' . time() . '_' . $index . '.' . $fileExtension;
                        $targetPath = $docUploadDir . $docName;

                        if (!move_uploaded_file($_FILES[$fileKey]['tmp_name'], $targetPath)) {
                            throw new Exception('Error uploading document');
                        }

                        $docPath = 'Uploads/staff_documents/' . $docName;

                        $docStmt->bind_param("ssss",
                            $empCode,
                            $document['docType'],
                            $document['description'],
                            $docPath
                        );

                        if (!$docStmt->execute()) {
                            throw new Exception('Error adding document: ' . $docStmt->error);
                        }
                    }
                }

                $docStmt->close();
            }
        }

        // Commit transaction
        $con->commit();

        echo json_encode([
            'status' => 'success',
            'message' => 'Staff profile created successfully'
        ]);

    } catch (Exception $e) {
        // Rollback transaction on error
        if ($con->connect_errno === 0) {
            $con->rollback();
        }
        
        error_log("Error in create.php: " . $e->getMessage());
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
