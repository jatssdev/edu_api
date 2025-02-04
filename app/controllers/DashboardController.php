<?php
require_once __DIR__ . '/../models/DashboardOption.php';

class DashboardController {
    // Add a new dashboard option
    public function addOption($data) {
        try {
            if (!isset($data['title']) || !isset($data['icon'])) {
                throw new Exception("Title and icon are required");
            }
    
            $title = trim($data['title']);
            $icon = trim($data['icon']);
            $priority = isset($data['priority']) ? (int)$data['priority'] : 0; // Default to 0 if not provided
    
            if (empty($title) || empty($icon)) {
                throw new Exception("Title and icon cannot be empty");
            }
    
            $option = new DashboardOption();
            $option->title = $title;
            $option->icon = $icon;
            $option->priority = $priority;
    
            if ($option->createOption()) {
                return json_encode(["status" => "success", "message" => "Dashboard option added successfully"]);
            }
            throw new Exception("Failed to add option");
        } catch (Exception $e) {
            return json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }
    

    // Get all dashboard options
    public function getAllOptions() {
        try {
            $option = new DashboardOption();
            $result = $option->getAllOptions();
            return json_encode(["status" => "success", "data" => $result]);
        } catch (Exception $e) {
            return json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }
}
?>
