<?php
// Prevent any output before headers
ob_start();

session_start();
include("../../Config/conect.php");

// Custom error handler to catch PHP errors and convert them to JSON
function handleError($errno, $errstr, $errfile, $errline) {
    ob_clean();
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $errstr,
        'file' => basename($errfile),
        'line' => $errline
    ]);
    exit;
}
set_error_handler('handleError');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Disable HTML error output

header('Content-Type: application/json');

// Verify CSRF token
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    ob_clean();
    http_response_code(403);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid CSRF token'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Debug: Log received data
        error_log("Received POST data: " . print_r($_POST, true));
        error_log("Received FILES data: " . print_r($_FILES, true));

        // Validate required fields
        $requiredFields = [
            'empcode' => 'Employee Code',
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

        // Get and sanitize form data
        $empCode = htmlspecialchars(trim($_POST['empcode']), ENT_QUOTES, 'UTF-8');
        $empName = htmlspecialchars(trim($_POST['empName']), ENT_QUOTES, 'UTF-8');
        $gender = htmlspecialchars(trim($_POST['gender']), ENT_QUOTES, 'UTF-8');
        $dob = htmlspecialchars(trim($_POST['dob']), ENT_QUOTES, 'UTF-8');
        $position = htmlspecialchars(trim($_POST['position']), ENT_QUOTES, 'UTF-8');
        $department = htmlspecialchars(trim($_POST['department']), ENT_QUOTES, 'UTF-8');
        $division = isset($_POST['division']) ? htmlspecialchars(trim($_POST['division']), ENT_QUOTES, 'UTF-8') : null;
        $startDate = htmlspecialchars(trim($_POST['startDate']), ENT_QUOTES, 'UTF-8');
        $isProb = isset($_POST['isProb']) ? (int)$_POST['isProb'] : 0;
        $status = htmlspecialchars(trim($_POST['status']), ENT_QUOTES, 'UTF-8');
        $salary = filter_var(trim($_POST['salary']), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $lineManager = isset($_POST['lineManager']) ? htmlspecialchars(trim($_POST['lineManager']), ENT_QUOTES, 'UTF-8') : null;
        $hod = isset($_POST['hod']) ? htmlspecialchars(trim($_POST['hod']), ENT_QUOTES, 'UTF-8') : null;
        $contact = isset($_POST['contact']) ? htmlspecialchars(trim($_POST['contact']), ENT_QUOTES, 'UTF-8') : null;
        $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : null;
        $address = isset($_POST['address']) ? htmlspecialchars(trim($_POST['address']), ENT_QUOTES, 'UTF-8') : null;
        $telegram = isset($_POST['telegram']) ? htmlspecialchars(trim($_POST['telegram']), ENT_QUOTES, 'UTF-8') : null;
        $payParameter = isset($_POST['payParamter']) ? htmlspecialchars(trim($_POST['payParamter']), ENT_QUOTES, 'UTF-8') : null;

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

            // Validate MIME type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $_FILES['photo']['tmp_name']);
            finfo_close($finfo);

            $allowedMimeTypes = ['image/jpeg', 'image/png'];
            if (!in_array($mimeType, $allowedMimeTypes)) {
                throw new Exception('Invalid file type. Only JPG and PNG files are allowed');
            }

            $fileExtension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            
            if (!in_array($fileExtension, $allowedExtensions)) {
                throw new Exception('Invalid file type. Only JPG, JPEG, and PNG files are allowed');
            }

            // Check file size (max 2MB)
            if ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
                throw new Exception('File size exceeds 2MB limit');
            }

            // Generate safe filename
            $photoName = $empCode . '_' . uniqid() . '_' . time() . '.' . $fileExtension;
            $targetPath = $uploadDir . $photoName;

            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
                throw new Exception('Error uploading photo');
            }
            $photoPath = 'Uploads/staff_photos/' . $photoName;

            // Delete old photo if exists
            if (isset($_POST['oldPhoto']) && !empty($_POST['oldPhoto'])) {
                $oldPath = '../../' . $_POST['oldPhoto'];
                if (file_exists($oldPath) && is_file($oldPath)) {
                    unlink($oldPath);
                }
            }
        }

        // Update staff profile
        $sql = "UPDATE hrstaffprofile SET 
            EmpName = ?, Gender = ?, DOB = ?, Position = ?, Department = ?, Division = ?,
            StartDate = ?, Status = ?, Salary = ?, LineManager = ?, HOD = ?, Contact = ?,
            Email = ?, Address = ?, Telegram = ?, PayParameter = ?";

        if ($photoPath) {
            $sql .= ", Photo = ?";
        }

        $sql .= " WHERE EmpCode = ?";

        $params = [
            $empName, $gender, $dob, $position, $department, $division,
            $startDate, $status, $salary, $lineManager, $hod, $contact,
            $email, $address, $telegram, $payParameter
        ];

        $types = "ssssssssdsssssss";

        if ($photoPath) {
            $params[] = $photoPath;
            $types .= "s";
        }

        $params[] = $empCode;
        $types .= "s";

        $stmt = $con->prepare($sql);
        if (!$stmt) {
            throw new Exception('Database error: ' . $con->error);
        }

        $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {
            throw new Exception('Error updating staff profile: ' . $stmt->error);
        }

        $stmt->close();

        // Handle family members
        if (isset($_POST['familyMembers'])) {
            $familyMembers = json_decode($_POST['familyMembers'], true);
            
            // Delete existing family members
            $deleteStmt = $con->prepare("DELETE FROM hrfamily WHERE EmpCode = ?");
            if (!$deleteStmt) {
                throw new Exception('Database error: ' . $con->error);
            }
            $deleteStmt->bind_param("s", $empCode);
            if (!$deleteStmt->execute()) {
                throw new Exception('Error deleting existing family members: ' . $deleteStmt->error);
            }
            $deleteStmt->close();

            if ($familyMembers) {
                $familyStmt = $con->prepare("INSERT INTO hrfamily (
                    EmpCode, RelationName, RelationType, Gender, IsTax
                ) VALUES (?, ?, ?, ?, ?)");

                if (!$familyStmt) {
                    throw new Exception('Database error: ' . $con->error);
                }

                foreach ($familyMembers as $member) {
                    if (empty($member['name'])) {
                        throw new Exception('Family member name is required');
                    }

                    $familyStmt->bind_param("ssssi",
                        $empCode,
                        $member['name'],
                        $member['relation'],
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
            
            // Delete existing education records
            $deleteStmt = $con->prepare("DELETE FROM hreducation WHERE EmpCode = ?");
            if (!$deleteStmt) {
                throw new Exception('Database error: ' . $con->error);
            }
            $deleteStmt->bind_param("s", $empCode);
            if (!$deleteStmt->execute()) {
                throw new Exception('Error deleting existing education records: ' . $deleteStmt->error);
            }
            $deleteStmt->close();

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
            
            // Get existing documents to handle deletion
            $stmt = $con->prepare("SELECT Photo FROM hrstaffdocument WHERE EmpCode = ?");
            $stmt->bind_param("s", $empCode);
            $stmt->execute();
            $result = $stmt->get_result();
            $existingDocs = [];
            while ($row = $result->fetch_assoc()) {
                $existingDocs[] = $row['Photo'];
            }
            $stmt->close();

            // Delete documents that are no longer needed
            $deleteStmt = $con->prepare("DELETE FROM hrstaffdocument WHERE EmpCode = ?");
            if (!$deleteStmt) {
                throw new Exception('Database error: ' . $con->error);
            }
            $deleteStmt->bind_param("s", $empCode);
            if (!$deleteStmt->execute()) {
                throw new Exception('Error deleting existing documents: ' . $deleteStmt->error);
            }
            $deleteStmt->close();

            // Delete files that are no longer referenced
            foreach ($existingDocs as $oldDoc) {
                $oldPath = '../../' . $oldDoc;
                if (file_exists($oldPath) && is_file($oldPath)) {
                    unlink($oldPath);
                }
            }

            if ($documents) {
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
                    if (empty($document['docType'])) {
                        throw new Exception('Document type is required');
                    }

                    $fileKey = "document_" . $index;
                    if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
                        // Validate MIME type
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mimeType = finfo_file($finfo, $_FILES[$fileKey]['tmp_name']);
                        finfo_close($finfo);

                        $allowedMimeTypes = [
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'image/jpeg',
                            'image/png'
                        ];

                        if (!in_array($mimeType, $allowedMimeTypes)) {
                            throw new Exception('Invalid document type. Only PDF, DOC, DOCX, JPG, and PNG files are allowed');
                        }

                        // Check file size (max 7MB)
                        if ($_FILES[$fileKey]['size'] > 7 * 1024 * 1024) {
                            throw new Exception('Document size exceeds 7MB limit');
                        }

                        $fileExtension = strtolower(pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION));
                        $docName = $empCode . '_' . uniqid() . '_' . time() . '_' . $index . '.' . $fileExtension;
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

        ob_clean();
        echo json_encode([
            'status' => 'success',
            'message' => 'Staff profile updated successfully'
        ]);

    } catch (Exception $e) {
        // Rollback transaction on error
        if ($con->connect_errno === 0) {
            $con->rollback();
        }
        
        error_log("Error in update.php: " . $e->getMessage());
        ob_clean();
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
} else {
    ob_clean();
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}

// End output buffering
ob_end_flush();
