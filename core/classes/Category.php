<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Category {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getAllDescendants($ancestorId) {
        $sql = "SELECT c.name FROM categories c 
                JOIN category_closure cc ON c.id = cc.descendant_id 
                WHERE cc.ancestor_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $ancestorId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    // Add more methods as needed
}
?>
