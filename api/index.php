<?php
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/StandardController.php';
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/BookController.php';
require_once __DIR__ . '/../app/controllers/ChapterController.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle CORS Preflight Request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$router = new Router();
// Read input data (Supports both JSON & Form-Data)
$json = file_get_contents("php://input");
$data = json_decode($json, true) ?? $_POST;

// Global Error Handler for Missing Data
if (empty($data) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid or missing request payload"]);
    exit;
}
// ✅ Authentication Routes
$router->addRoute("POST", "/register", function() use ($data) {
    $auth = new AuthController();
    echo $auth->register($data);
});

$router->addRoute("POST", "/login", function() use ($data) {
    $auth = new AuthController();
    echo $auth->login($data);
});

// ✅ User Profile APIs
$router->addRoute("POST", "/user/update-profile", function() use ($data) {
    if (!isset($_GET['id'])) {
        echo json_encode(["status" => "error", "message" => "User ID is required"]);
        return;
    }
    $auth = new AuthController();
    echo $auth->updateProfile($_GET['id'], $data);
});

$router->addRoute("GET", "/user/profile", function() {
    if (!isset($_GET['id'])) {
        echo json_encode(["status" => "error", "message" => "User ID is required"]);
        return;
    }
    $auth = new AuthController();
    echo $auth->getUserProfile($_GET['id']);
});
// ✅ Standard APIs with Dashboard Reference
$router->addRoute("POST", "/standard/add", function() use ($data) {
    $std = new StandardController();
    echo $std->addStandard($data);
});

$router->addRoute("GET", "/standard/all", function() {
    $std = new StandardController();
    echo $std->getAllStandards();
});

$router->addRoute("GET", "/standard/single", function() {
    if (!isset($_GET['id'])) {
        echo json_encode(["status" => "error", "message" => "ID is required"]);
        return;
    }
    $std = new StandardController();
    echo $std->getStandard($_GET['id']);
});
$router->addRoute("GET", "/standard/by-dashboard", function() {
    if (!isset($_GET['dashboard_option_id'])) {
        echo json_encode(["status" => "error", "message" => "dashboard_option_id is required"]);
        return;
    }
    $std = new StandardController();
    echo $std->getStandardsByDashboard($_GET['dashboard_option_id']);
});

// ✅ Dashboard Options Routes
$router->addRoute("POST", "/dashboard/add", function() use ($data) {
    $dashboard = new DashboardController();
    echo $dashboard->addOption($data);
});

$router->addRoute("GET", "/dashboard/all", function() {
    $dashboard = new DashboardController();
    echo $dashboard->getAllOptions();
});

// ✅ Book APIs
$router->addRoute("POST", "/book/add", function() use ($data) {
    $book = new BookController();
    echo $book->addBook($data);
});

$router->addRoute("GET", "/book/all", function() {
    $book = new BookController();
    echo $book->getAllBooks();
});

// ✅ Chapter APIs
$router->addRoute("POST", "/chapter/add", function() use ($data) {
    $chapter = new ChapterController();
    echo $chapter->addChapter($data);
});

$router->addRoute("GET", "/chapter/all", function() {
    if (!isset($_GET['book_id'])) {
        echo json_encode(["status" => "error", "message" => "book_id is required"]);
        return;
    }
    $chapter = new ChapterController();
    echo $chapter->getChaptersByBook($_GET['book_id']);
});

// ✅ Dispatch API Requests
try {
    $router->dispatch($_SERVER["REQUEST_METHOD"], $_SERVER["REQUEST_URI"]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Internal Server Error", "details" => $e->getMessage()]);
}
?>
