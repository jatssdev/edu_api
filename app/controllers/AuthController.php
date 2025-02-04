<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/Config.php';
require __DIR__ . '/../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController {
    public function register($data) {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];

        if ($user->createUser()) {
            return json_encode(["status" => "success", "message" => "User registered successfully"]);
        }
        return json_encode(["status" => "error", "message" => "Registration failed"]);
    }

  
 
        // User login (return user details)
        public function login($data) {
            try {
                if (!isset($data['email']) || !isset($data['password'])) {
                    throw new Exception("Email and password are required");
                }
    
                $user = new User();
                $user->email = trim($data['email']);
                $userData = $user->getUserByEmail();
    
                if (!$userData || !password_verify($data['password'], $userData['password'])) {
                    throw new Exception("Invalid email or password");
                }
    
                // Remove password before sending response
                unset($userData['password']);
    
                return json_encode([
                    "status" => "success",
                    "message" => "Login successful",
                    "user" => $userData
                ]);
            } catch (Exception $e) {
                return json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
        }
    
    
}
?>
