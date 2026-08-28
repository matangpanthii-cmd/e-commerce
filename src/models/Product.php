<?php
require_once BASE_PATH . '/src/config/database.php';

class Product {
    private $conn;
    private $table_name = "products";

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function getTrendingProducts($limit = 4) {
        $query = "SELECT p.*, c.name as category_name, 
                  (SELECT image_url FROM product_images pi WHERE pi.product_id = p.id AND pi.is_primary = 1 LIMIT 1) as primary_image 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.status != 'inactive'
                  ORDER BY p.created_at DESC LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllProducts($category = null, $sort = 'recommended') {
        $query = "SELECT p.*, c.name as category_name, c.slug as category_slug,
                  (SELECT image_url FROM product_images pi WHERE pi.product_id = p.id AND pi.is_primary = 1 LIMIT 1) as primary_image 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.status != 'inactive'";
        
        if ($category) {
            $query .= " AND (c.slug = :category_slug OR p.status = :category_status)";
        }

        switch($sort) {
            case 'price_asc':
                $query .= " ORDER BY p.price ASC";
                break;
            case 'price_desc':
                $query .= " ORDER BY p.price DESC";
                break;
            case 'newest':
                $query .= " ORDER BY p.created_at DESC";
                break;
            default:
                $query .= " ORDER BY p.id ASC";
                break;
        }

        $stmt = $this->conn->prepare($query);
        
        if ($category) {
            $stmt->bindParam(':category_slug', $category);
            $stmt->bindParam(':category_status', $category);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCategories() {
        $stmt = $this->conn->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductBySlug($slug) {
        $query = "SELECT p.*, c.name as category_name, c.slug as category_slug
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.slug = :slug LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if($product) {
            // Get images
            $img_query = "SELECT id, image_url, is_primary FROM product_images WHERE product_id = :product_id ORDER BY is_primary DESC, id ASC";
            $img_stmt = $this->conn->prepare($img_query);
            $img_stmt->bindParam(':product_id', $product['id']);
            $img_stmt->execute();
            $product['images'] = $img_stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get variants
            $var_query = "SELECT * FROM product_variants WHERE product_id = :product_id";
            $var_stmt = $this->conn->prepare($var_query);
            $var_stmt->bindParam(':product_id', $product['id']);
            $var_stmt->execute();
            $product['variants'] = $var_stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $product;
    }

    public function getProductById($id) {
        $query = "SELECT p.*, c.name as category_name FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $img_stmt = $this->conn->prepare("SELECT * FROM product_images WHERE product_id = :pid ORDER BY is_primary DESC, id ASC");
            $img_stmt->bindParam(':pid', $product['id'], PDO::PARAM_INT);
            $img_stmt->execute();
            $product['images'] = $img_stmt->fetchAll(PDO::FETCH_ASSOC);

            $var_stmt = $this->conn->prepare("SELECT * FROM product_variants WHERE product_id = :pid ORDER BY id ASC");
            $var_stmt->bindParam(':pid', $product['id'], PDO::PARAM_INT);
            $var_stmt->execute();
            $product['variants'] = $var_stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $product;
    }

    public function getPriceById($id) {
        $stmt = $this->conn->prepare("SELECT price FROM " . $this->table_name . " WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['price'] : 0;
    }

    // ---- Admin Methods ----

    public function getAllAdmin($search = '') {
        $query = "SELECT p.*, c.name as category_name,
                  (SELECT image_url FROM product_images pi WHERE pi.product_id = p.id AND pi.is_primary = 1 LIMIT 1) as primary_image
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id";
        if ($search) {
            $query .= " WHERE p.name LIKE :search";
        }
        $query .= " ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        if ($search) {
            $like = '%' . $search . '%';
            $stmt->bindParam(':search', $like);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createProduct($data) {
        $query = "INSERT INTO " . $this->table_name . " (category_id, name, slug, description, price, status)
                  VALUES (:category_id, :name, :slug, :description, :price, :status)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':status', $data['status']);
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function updateProduct($id, $data) {
        $query = "UPDATE " . $this->table_name . "
                  SET category_id = :category_id, name = :name, slug = :slug,
                      description = :description, price = :price, status = :status
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteProduct($id) {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function addImage($product_id, $image_url, $is_primary = false) {
        if ($is_primary) {
            $this->conn->prepare("UPDATE product_images SET is_primary = 0 WHERE product_id = :pid")
                ->execute([':pid' => $product_id]);
        }
        $stmt = $this->conn->prepare("INSERT INTO product_images (product_id, image_url, is_primary) VALUES (:pid, :url, :primary)");
        $stmt->bindParam(':pid', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':url', $image_url);
        $primary = $is_primary ? 1 : 0;
        $stmt->bindParam(':primary', $primary, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteImage($image_id) {
        // Get image url to delete file
        $stmt = $this->conn->prepare("SELECT image_url FROM product_images WHERE id = :id");
        $stmt->bindParam(':id', $image_id, PDO::PARAM_INT);
        $stmt->execute();
        $img = $stmt->fetch(PDO::FETCH_ASSOC);

        $del = $this->conn->prepare("DELETE FROM product_images WHERE id = :id");
        $del->bindParam(':id', $image_id, PDO::PARAM_INT);
        $del->execute();
        return $img ? $img['image_url'] : null;
    }

    public function addVariant($product_id, $color_name, $color_hex, $size, $stock) {
        $stmt = $this->conn->prepare("INSERT INTO product_variants (product_id, color_name, color_hex, size, stock) VALUES (:pid, :cn, :ch, :s, :st)");
        $stmt->bindParam(':pid', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':cn', $color_name);
        $stmt->bindParam(':ch', $color_hex);
        $stmt->bindParam(':s', $size);
        $stmt->bindParam(':st', $stock, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteVariant($variant_id) {
        $stmt = $this->conn->prepare("DELETE FROM product_variants WHERE id = :id");
        $stmt->bindParam(':id', $variant_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function countAll() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM " . $this->table_name);
        return $stmt->fetchColumn();
    }

    public function generateUniqueSlug($name, $exclude_id = null) {
        $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $name), '-'));
        $original = $slug;
        $i = 1;
        while (true) {
            $q = "SELECT id FROM " . $this->table_name . " WHERE slug = :slug";
            if ($exclude_id) $q .= " AND id != :exclude";
            $stmt = $this->conn->prepare($q);
            $stmt->bindParam(':slug', $slug);
            if ($exclude_id) $stmt->bindParam(':exclude', $exclude_id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() === 0) break;
            $slug = $original . '-' . $i++;
        }
        return $slug;
    }
}
?>
