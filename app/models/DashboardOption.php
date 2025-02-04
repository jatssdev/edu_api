<?php
require_once __DIR__ . '/../config/Database.php';

class DashboardOption {
    private $conn;
    private $table = "dashboard_options";

    public $id;
    public $title;
    public $icon;
    public $priority;
    public $created_at;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Add a new dashboard option
    public function createOption() {
        $query = "INSERT INTO " . $this->table . " (title, icon, priority) VALUES (:title, :icon, :priority)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":icon", $this->icon);
        $stmt->bindParam(":priority", $this->priority);

        return $stmt->execute();
    }

    // Get all dashboard options sorted by priority
    public function getAllOptions() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY priority DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
