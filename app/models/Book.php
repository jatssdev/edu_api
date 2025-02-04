<?php
require_once __DIR__ . '/../config/Database.php';

class Book {
    private $conn;
    private $table = "books";

    public $id;
    public $title;
    public $book_url;
    public $standard_id;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Add a new book
    public function createBook() {
        $query = "INSERT INTO " . $this->table . " (title, book_url, standard_id) 
                  VALUES (:title, :book_url, :standard_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":book_url", $this->book_url);
        $stmt->bindParam(":standard_id", $this->standard_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Get all books with standard reference
    public function getAllBooks() {
        $query = "SELECT b.*, s.name_en AS standard_name 
                  FROM " . $this->table . " b 
                  LEFT JOIN standards s ON b.standard_id = s.id
                  ORDER BY b.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
