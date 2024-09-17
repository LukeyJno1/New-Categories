<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../includes/db_config.php'; // Ensure the correct path to the configuration file

header('Content-Type: application/json'); // Set the content type to JSON

$response = ['status' => 'error', 'message' => 'Unknown error occurred', 'databases' => []];

try {
    // Establish a new MySQL connection
    $conn = new mysqli($host, $user, $password);

    // Check for connection errors
    if ($conn->connect_error) {
        throw new Exception('Connection failed: ' . $conn->connect_error);
    }

    // Fetch all databases
    $query = "SHOW DATABASES";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response['databases'][] = $row['Database'];
        }
        $response['status'] = 'success';
        $response['message'] = 'Databases fetched successfully';
    } else {
        $response['message'] = 'No databases found or unable to fetch databases';
    }
} catch (Exception $e) {
    $response['message'] = 'Error fetching databases: ' . $e->getMessage();
}

// Close connection
$conn->close();

// Return the JSON response
echo json_encode($response);
?>
