<?php
require_once __DIR__ . '/../config/Database.php';

class Standard {
    private $conn;
    private $table = "standards";

    public $id;
    public $name_en;
    public $name_gu;
    public $dashboard_option_id;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Add a new standard with a dashboard reference
    public function createStandard() {
        $query = "INSERT INTO " . $this->table . " (name_en, name_gu, dashboard_option_id) 
                  VALUES (:name_en, :name_gu, :dashboard_option_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":name_en", $this->name_en);
        $stmt->bindParam(":name_gu", $this->name_gu);

        // Handle NULL for dashboard_option_id properly
        if (!is_null($this->dashboard_option_id) && $this->dashboard_option_id > 0) {
            $stmt->bindParam(":dashboard_option_id", $this->dashboard_option_id, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(":dashboard_option_id", null, PDO::PARAM_NULL);
        }

        return $stmt->execute();
    }

    // Get all standards with dashboard references
    public function getAllStandards() {
        $query = "SELECT s.*, d.title AS dashboard_title 
                  FROM " . $this->table . " s 
                  LEFT JOIN dashboard_options d ON s.dashboard_option_id = d.id
                  ORDER BY s.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single standard with dashboard reference
    public function getStandardById() {
        $query = "SELECT s.*, d.title AS dashboard_title 
                  FROM " . $this->table . " s 
                  LEFT JOIN dashboard_options d ON s.dashboard_option_id = d.id
                  WHERE s.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getStandardsByDashboardId($dashboard_option_id) {
        $query = "SELECT s.*, d.title AS dashboard_title 
                  FROM " . $this->table . " s 
                  LEFT JOIN dashboard_options d ON s.dashboard_option_id = d.id
                  WHERE s.dashboard_option_id = :dashboard_option_id
                  ORDER BY s.id DESC";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":dashboard_option_id", $dashboard_option_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
