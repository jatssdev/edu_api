<?php
class Router {
    private $routes = [];

    public function addRoute($method, $path, $handler) {
        $this->routes[] = ["method" => $method, "path" => $path, "handler" => $handler];
    }

    public function dispatch($requestMethod, $requestUri) {
        $path = parse_url($requestUri, PHP_URL_PATH);
        $path = str_replace("/edu/api", "", $path); // Remove base folder from URL

        foreach ($this->routes as $route) {
            if ($route["method"] === $requestMethod && $route["path"] === $path) {
                call_user_func($route["handler"]);
                return;
            }
        }

        header("HTTP/1.0 404 Not Found");
        echo json_encode(["status" => "error", "message" => "Endpoint not found"]);
    }
}
?>
