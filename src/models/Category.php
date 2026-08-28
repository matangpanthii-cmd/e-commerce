<?php
require_once BASE_PATH . '/src/config/database.php';

class Category {
    private $conn;
    private $table_name = "categories";

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT c.*, COUNT(p.id) as product_count
            FROM " . $this->table_name . " c
            LEFT JOIN products p ON p.category_id = c.id
            GROUP BY c.id ORDER BY c.name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $slug) {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (name, slug) VALUES (:name, :slug)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':slug', $slug);
        return $stmt->execute();
    }

    public function update($id, $name, $slug) {
        $stmt = $this->conn->prepare("UPDATE " . $this->table_name . " SET name = :name, slug = :slug WHERE id = :id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function generateSlug($name) {
        return strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $name), '-'));
    }
}
?>
