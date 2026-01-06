<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $studentName = isset($_POST['studentName']) ? trim($_POST['studentName']) : '';
    $rollNumber = isset($_POST['rollNumber']) ? trim($_POST['rollNumber']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $courseName = isset($_POST['courseName']) ? trim($_POST['courseName']) : '';
    
    $errors = array();
    
    if (empty($studentName)) {
        $errors[] = "Student name is required";
    } elseif (strlen($studentName) < 3) {
        $errors[] = "Student name must be at least 3 characters";
    }
    
    if (empty($rollNumber)) {
        $errors[] = "Roll number is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($courseName)) {
        $errors[] = "Course name is required";
    }
    
    if (!empty($errors)) {
        $response['status'] = 'error';
        $response['message'] = 'Validation failed';
        $response['errors'] = $errors;
        echo json_encode($response);
        exit;
    }
    
    $studentData = array(
        'studentName' => htmlspecialchars($studentName),
        'rollNumber' => htmlspecialchars($rollNumber),
        'email' => htmlspecialchars($email),
        'courseName' => htmlspecialchars($courseName),
        'timestamp' => date('Y-m-d H:i:s')
    );
    
    $filename = 'students_data.txt';
    
    $dataString = sprintf(
        "=== Student Record ===\nName: %s\nRoll Number: %s\nEmail: %s\nCourse: %s\nRegistered On: %s\n" . str_repeat("=", 50) . "\n\n",
        $studentData['studentName'],
        $studentData['rollNumber'],
        $studentData['email'],
        $studentData['courseName'],
        $studentData['timestamp']
    );
    
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

echo json_encode($response);
?>