<?php
require_once __DIR__ . '/../models/Chapter.php';

class ChapterController {
    // Add a new chapter
    public function addChapter($data) {
        try {
            if (!isset($data['chapter_name']) || !isset($data['book_id']) || !isset($data['page_number'])) {
                throw new Exception("chapter_name, book_id, and page_number are required");
            }

            $chapter_name = trim($data['chapter_name']);
            $book_id = is_numeric($data['book_id']) ? (int)$data['book_id'] : null;
            $page_number = is_numeric($data['page_number']) ? (int)$data['page_number'] : null;

            if (empty($chapter_name) || empty($book_id) || empty($page_number)) {
                throw new Exception("Invalid input values");
            }

            $chapter = new Chapter();
            $chapter->chapter_name = $chapter_name;
            $chapter->book_id = $book_id;
            $chapter->page_number = $page_number;

            if ($chapter->createChapter()) {
                return json_encode(["status" => "success", "message" => "Chapter added successfully"]);
            }
            throw new Exception("Failed to add chapter");
        } catch (Exception $e) {
            return json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    // Get all chapters for a book
    public function getChaptersByBook($book_id) {
        try {
            if (!is_numeric($book_id)) {
                throw new Exception("Invalid book ID");
            }

            $chapter = new Chapter();
            $result = $chapter->getChaptersByBook($book_id);

            if ($result) {
                return json_encode(["status" => "success", "data" => $result]);
            }
            throw new Exception("No chapters found for this book");
        } catch (Exception $e) {
            return json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }
}
?>
