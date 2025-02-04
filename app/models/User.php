<?php
require_once __DIR__ . '/../config/Database.php';

class User {
    private $conn;
    private $table = "users";

    public $id;
    public $name;
    public $email;
    public $password;
    public $dob;
    public $gender;
    public $mobile_number;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Create a new user (existing functionality)
    public function createUser() {
        $query = "INSERT INTO " . $this->table . " (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->conn->prepare($query);
        
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);

        return $stmt->execute();
    }

    // Get user by email (existing functionality)
    public function getUserByEmail() {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update user profile (new functionality)
    public function updateProfile($id) {
        $query = "UPDATE " . $this->table . " 
                  SET dob = :dob, gender = :gender, mobile_number = :mobile_number
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":dob", $this->dob);
        $stmt->bindParam(":gender", $this->gender);
        $stmt->bindParam(":mobile_number", $this->mobile_number);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Get user details by ID (new functionality)
    public function getUserById($id) {
        $query = "SELECT id, name, email, dob, gender, mobile_number FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
