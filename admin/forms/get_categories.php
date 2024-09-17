<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Set the content type to JSON

include '../includes/db_config.php'; // Ensure the correct path to the configuration file

$response = ['status' => 'error', 'message' => 'Unknown error occurred', 'categories' => []];

// Get the selected database from the query string
$selected_db = isset($_GET['selected_db']) ? $_GET['selected_db'] : '';

if (empty($selected_db)) {
    $response['message'] = 'No database selected. Please select a database first.';
    echo json_encode($response);
    exit;
}

try {
    // Re-establish connection to the selected database
    $conn = new mysqli($host, $user, $password, $selected_db);

    // Check for connection errors
    if ($conn->connect_error) {
        throw new Exception('Connection failed: ' . $conn->connect_error);
    }

    // Fetch categories from the selected database
    $query = "SELECT id, name FROM categories";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response['categories'][] = $row;
        }
        $response['status'] = 'success';
        $response['message'] = 'Categories fetched successfully';
    } else {
        $response['message'] = 'No categories found or unable to fetch categories';
    }
} catch (Exception $e) {
    $response['message'] = 'Error fetching categories: ' . $e->getMessage();
}

// Close connection
$conn->close();

// Return the JSON response
echo json_encode($response);
?>
