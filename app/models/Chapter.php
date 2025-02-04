<?php
require_once __DIR__ . '/../config/Database.php';

class Chapter {
    private $conn;
    private $table = "chapters";

    public $id;
    public $chapter_name;
    public $book_id;
    public $page_number;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Add a new chapter
    public function createChapter() {
        $query = "INSERT INTO " . $this->table . " (chapter_name, book_id, page_number) 
                  VALUES (:chapter_name, :book_id, :page_number)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":chapter_name", $this->chapter_name);
        $stmt->bindParam(":book_id", $this->book_id, PDO::PARAM_INT);
        $stmt->bindParam(":page_number", $this->page_number, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Get all chapters for a given book
    public function getChaptersByBook($book_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE book_id = :book_id ORDER BY page_number ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":book_id", $book_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
