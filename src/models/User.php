<?php
require_once BASE_PATH . '/src/config/database.php';

class User {
    private $conn;
    private $table_name = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function register($name, $email, $password) {
        // Check if email exists
        $check_query = "SELECT id FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(':email', $email);
        $check_stmt->execute();
        
        if($check_stmt->rowCount() > 0) {
            return ["success" => false, "message" => "Email already exists."];
        }

        $query = "INSERT INTO " . $this->table_name . " (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->conn->prepare($query);

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        if($stmt->execute()) {
            return ["success" => true, "message" => "Registration successful."];
        }
        return ["success" => false, "message" => "Registration failed."];
    }

    public function login($email, $password) {
        $query = "SELECT id, name, password, role FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($password, $row['password'])) {
                // Remove password hash from session data
                unset($row['password']);
                return ["success" => true, "user" => $row];
            }
        }
        return ["success" => false, "message" => "Invalid email or password."];
    }

    public function getUserById($id) {
        $query = "SELECT id, name, email, role, created_at FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
