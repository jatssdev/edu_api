<?php
require_once __DIR__ . '/../models/Standard.php';

class StandardController {
    // Add Standard with Dashboard Reference
    public function addStandard($data) {
        try {
            if (!isset($data['name_en']) || !isset($data['name_gu'])) {
                throw new Exception("Both English and Gujarati names are required");
            }

            $name_en = trim($data['name_en']);
            $name_gu = trim($data['name_gu']);
            $dashboard_option_id = isset($data['dashboard_option_id']) ? (int)$data['dashboard_option_id'] : null;

            if (empty($name_en) || empty($name_gu)) {
                throw new Exception("Standard names cannot be empty");
            }

            $standard = new Standard();
            $standard->name_en = $name_en;
            $standard->name_gu = $name_gu;
            $standard->dashboard_option_id = $dashboard_option_id;

            if ($standard->createStandard()) {
                return json_encode(["status" => "success", "message" => "Standard added successfully"]);
            }
            throw new Exception("Failed to add standard");
        } catch (Exception $e) {
            return json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    // Get All Standards with Dashboard Reference
    public function getAllStandards() {
        try {
            $standard = new Standard();
            $result = $standard->getAllStandards();
            return json_encode(["status" => "success", "data" => $result]);
        } catch (Exception $e) {
            return json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    // Get Single Standard with Dashboard Reference
    public function getStandard($id) {
        try {
            if (!is_numeric($id)) {
                throw new Exception("Invalid standard ID");
            }

            $standard = new Standard();
            $standard->id = $id;
            $result = $standard->getStandardById();

            if ($result) {
                return json_encode(["status" => "success", "data" => $result]);
            }
            throw new Exception("Standard not found");
        } catch (Exception $e) {
            return json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }
    public function getStandardsByDashboard($dashboard_option_id) {
        try {
            if (!is_numeric($dashboard_option_id)) {
                throw new Exception("Invalid dashboard_option_id");
            }
    
            $standard = new Standard();
            $result = $standard->getStandardsByDashboardId($dashboard_option_id);
    
            if ($result) {
                return json_encode(["status" => "success", "data" => $result]);
            }
            throw new Exception("No standards found for this dashboard option");
        } catch (Exception $e) {
            return json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }
    
}
?>
