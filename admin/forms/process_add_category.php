<?php
// Include necessary files
include_once '../../includes/db_config.php';
include_once '../../includes/db_connection.php';

// Initialize response array
$response = [
    'status' => 'error',
    'message' => 'An unexpected error occurred.'
];

// Validate and sanitize input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_db = isset($_POST['selected_db']) ? trim($_POST['selected_db']) : '';
    $category_name = isset($_POST['category_name']) ? trim($_POST['category_name']) : '';
    $category_description = isset($_POST['category_description']) ? trim($_POST['category_description']) : '';
    $parent_categories = isset($_POST['parent_categories']) ? json_decode($_POST['parent_categories'], true) : [];

    if (empty($selected_db)) {
        $response['message'] = 'No database selected. Please select a database first.';
        echo json_encode($response);
        exit;
    }

    if (empty($category_name)) {
        $response['message'] = 'Category name is required.';
        echo json_encode($response);
        exit;
    }

    // Connect to the selected database
    $conn->select_db($selected_db);

    if ($conn->connect_error) {
        $response['message'] = 'Failed to connect to the selected database.';
        echo json_encode($response);
        exit;
    }

    // Check if the category already exists
    $check_query = "SELECT * FROM categories WHERE name = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param('s', $category_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response['message'] = 'The category already exists.';
        echo json_encode($response);
        exit;
    }

    // Insert the new category
    $insert_query = "INSERT INTO categories (name, description) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param('ss', $category_name, $category_description);

    if ($stmt->execute()) {
        $new_category_id = $stmt->insert_id;

        // Insert parent categories relationships, if any
        if (!empty($parent_categories)) {
            $parent_insert_query = "INSERT INTO category_relationships (parent_id, child_id) VALUES (?, ?)";
            $parent_stmt = $conn->prepare($parent_insert_query);

            foreach ($parent_categories as $parent_id) {
                $parent_stmt->bind_param('ii', $parent_id, $new_category_id);
                $parent_stmt->execute();
            }
            $parent_stmt->close();
        }

        $response['status'] = 'success';
        $response['message'] = 'Category added successfully.';
    } else {
        $response['message'] = 'Failed to add the category: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
exit;
?>
