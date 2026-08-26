<?php
require_once BASE_PATH . '/src/config/database.php';

class Product {
    private $conn;
    private $table_name = "products";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getTrendingProducts($limit = 4) {
        $query = "SELECT p.*, c.name as category_name, 
                  (SELECT image_url FROM product_images pi WHERE pi.product_id = p.id AND pi.is_primary = 1 LIMIT 1) as primary_image 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
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
                  LEFT JOIN categories c ON p.category_id = c.id";
        
        if ($category) {
            $query .= " WHERE c.slug = :category OR p.status = :category";
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
            $stmt->bindParam(':category', $category);
        }

        $stmt->execute();
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
            $img_query = "SELECT image_url, is_primary FROM product_images WHERE product_id = :product_id ORDER BY is_primary DESC, id ASC";
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
}
?>
