<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

$filename = 'students_data.txt';
$response = array();

if (file_exists($filename)) {
    $content = file_get_contents($filename);
    $count = substr_count($content, '=== Student Record ===');
    
    $response['status'] = 'success';
    $response['count'] = $count;
    $response['message'] = 'Student count retrieved successfully';
} else {
    $response['status'] = 'success';
    $response['count'] = 0;
    $response['message'] = 'No data file found';
}

echo json_encode($response);
?>