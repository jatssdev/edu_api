<?php
require_once __DIR__ . '/../models/Book.php';

class BookController {
    // Add a new book
    public function addBook($data) {
        try {
            if (!isset($data['title']) || !isset($data['book_url']) || !isset($data['standard_id'])) {
                throw new Exception("Title, book_url, and standard_id are required");
            }

            $title = trim($data['title']);
            $book_url = trim($data['book_url']);
            $standard_id = is_numeric($data['standard_id']) ? (int)$data['standard_id'] : null;

            if (empty($title) || empty($book_url) || empty($standard_id)) {
                throw new Exception("Invalid input values");
            }

            $book = new Book();
            $book->title = $title;
            $book->book_url = $book_url;
            $book->standard_id = $standard_id;

            if ($book->createBook()) {
                return json_encode(["status" => "success", "message" => "Book added successfully"]);
            }
            throw new Exception("Failed to add book");
        } catch (Exception $e) {
            return json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    // Get all books
    public function getAllBooks() {
        try {
            $book = new Book();
            $result = $book->getAllBooks();
            return json_encode(["status" => "success", "data" => $result]);
        } catch (Exception $e) {
            return json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }
}
?>
