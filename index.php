<?php
// Allow CORS and JSON response
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// File to simulate employee database
$dataFile = "employees.json";

// Create empty JSON file if it doesn’t exist
if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode([]));
}

// Load existing employee records
$employee_records = json_decode(file_get_contents($dataFile), true);

// Detect HTTP request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    // ✅ FUNCTION 1: Return all employee data
    echo json_encode([
        'status' => 'success',
        'data' => $employee_records
    ]);
} elseif ($method == 'POST') {
    // ✅ FUNCTION 2: Accept employee data (POST JSON)
    $input = json_decode(file_get_contents("php://input"), true);

    // Basic validation
    if (!isset($input['employeeId']) || !isset($input['fullName']) || !isset($input['email'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required fields: employeeId, fullName, email.'
        ]);
        exit;
    }

    // Add to records
    $employee_records[] = $input;

    // Save to file
    file_put_contents($dataFile, json_encode($employee_records, JSON_PRETTY_PRINT));

    // Respond
    echo json_encode([
        'status' => 'success',
        'message' => 'Employee data received successfully.',
        'employeeId' => $input['employeeId']
    ]);
} else {
    // Unsupported HTTP methods
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method Not Allowed.'
    ]);
}
?>
