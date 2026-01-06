<?php
// Enable error reporting for debugging
// error_reporting(0);
// ini_set('display_errors', 0);

// Set content type to JSON
header('Content-Type: application/json');

// Allow CORS (for local development)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Initialize response array
$response = array();

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get POST data
    $studentName = isset($_POST['studentName']) ? trim($_POST['studentName']) : '';
    $rollNumber = isset($_POST['rollNumber']) ? trim($_POST['rollNumber']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $courseName = isset($_POST['courseName']) ? trim($_POST['courseName']) : '';
    
    // Server-side validation
    $errors = array();
    
    // Validate Student Name
    if (empty($studentName)) {
        $errors[] = "Student name is required";
    } elseif (strlen($studentName) < 3) {
        $errors[] = "Student name must be at least 3 characters";
    }
    
    // Validate Roll Number
    if (empty($rollNumber)) {
        $errors[] = "Roll number is required";
    }
    
    // Validate Email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // Validate Course Name
    if (empty($courseName)) {
        $errors[] = "Course name is required";
    }
    
    // If there are validation errors
    if (!empty($errors)) {
        $response['status'] = 'error';
        $response['message'] = 'Validation failed';
        $response['errors'] = $errors;
        echo json_encode($response);
        exit;
    }
    
    // All validations passed - prepare data for storage
    $studentData = array(
        'studentName' => htmlspecialchars($studentName),
        'rollNumber' => htmlspecialchars($rollNumber),
        'email' => htmlspecialchars($email),
        'courseName' => htmlspecialchars($courseName),
        'timestamp' => date('Y-m-d H:i:s')
    );
    
    // File path for storing data
    $filename = 'students_data.txt';
    
    // Prepare data string for file storage
    $dataString = sprintf(
        "=== Student Record ===\nName: %s\nRoll Number: %s\nEmail: %s\nCourse: %s\nRegistered On: %s\n" . str_repeat("=", 50) . "\n\n",
        $studentData['studentName'],
        $studentData['rollNumber'],
        $studentData['email'],
        $studentData['courseName'],
        $studentData['timestamp']
    );
    
    // Try to write data to file
    if (file_put_contents($filename, $dataString, FILE_APPEND | LOCK_EX)) {
        $response['status'] = 'success';
        $response['message'] = 'Student registered successfully';
        $response['data'] = $studentData;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to save student data';
    }
    
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method. Only POST is allowed.';
}

// Return JSON response
echo json_encode($response);
?>