<?php
require_once BASE_PATH . '/src/config/database.php';

class Order {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function createOrder($user_id, $total_amount, $shipping_name, $shipping_address, $shipping_phone) {
        $query = "INSERT INTO orders (user_id, total_amount, shipping_name, shipping_address, shipping_phone, status)
                  VALUES (:user_id, :total, :name, :address, :phone, 'pending')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':total', $total_amount);
        $stmt->bindParam(':name', $shipping_name);
        $stmt->bindParam(':address', $shipping_address);
        $stmt->bindParam(':phone', $shipping_phone);
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function createOrderItems($order_id, $items) {
        $query = "INSERT INTO order_items (order_id, product_id, variant_id, quantity, price)
                  VALUES (:order_id, :product_id, :variant_id, :quantity, :price)";
        $stmt = $this->conn->prepare($query);
        foreach ($items as $item) {
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
            $variant_id = $item['variant_id'] ?? null;
            $stmt->bindParam(':variant_id', $variant_id);
            $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':price', $item['price']);
            $stmt->execute();
        }
    }

    public function getOrdersByUser($user_id) {
        $query = "SELECT o.*, COUNT(oi.id) as item_count
                  FROM orders o
                  LEFT JOIN order_items oi ON o.id = oi.order_id
                  WHERE o.user_id = :user_id
                  GROUP BY o.id
                  ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ---- Admin Methods ----

    public function getAllOrders($status = '', $search = '') {
        $query = "SELECT o.*, u.name as customer_name, u.email as customer_email
                  FROM orders o
                  LEFT JOIN users u ON o.user_id = u.id
                  WHERE 1=1";
        if ($status) {
            $query .= " AND o.status = :status";
        }
        if ($search) {
            $query .= " AND (u.name LIKE :search OR u.email LIKE :search OR o.id = :sid)";
        }
        $query .= " ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($query);
        if ($status) $stmt->bindParam(':status', $status);
        if ($search) {
            $like = '%' . $search . '%';
            $stmt->bindParam(':search', $like);
            $stmt->bindParam(':sid', $search);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderById($id) {
        $stmt = $this->conn->prepare(
            "SELECT o.*, u.name as customer_name, u.email as customer_email
             FROM orders o LEFT JOIN users u ON o.user_id = u.id
             WHERE o.id = :id LIMIT 1"
        );
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            $items_stmt = $this->conn->prepare(
                "SELECT oi.*, p.name as product_name, p.slug as product_slug,
                         pv.color_name, pv.size,
                         (SELECT image_url FROM product_images pi WHERE pi.product_id = p.id AND pi.is_primary = 1 LIMIT 1) as product_image
                 FROM order_items oi
                 LEFT JOIN products p ON oi.product_id = p.id
                 LEFT JOIN product_variants pv ON oi.variant_id = pv.id
                 WHERE oi.order_id = :oid"
            );
            $items_stmt->bindParam(':oid', $id, PDO::PARAM_INT);
            $items_stmt->execute();
            $order['items'] = $items_stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $order;
    }

    public function updateOrderStatus($id, $status) {
        $allowed = ['pending', 'paid', 'shipped', 'completed', 'cancelled'];
        if (!in_array($status, $allowed)) return false;
        $stmt = $this->conn->prepare("UPDATE orders SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function countAll() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM orders");
        return $stmt->fetchColumn();
    }

    public function countByStatus($status) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM orders WHERE status = :status");
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getTodayRevenue() {
        $stmt = $this->conn->query(
            "SELECT COALESCE(SUM(total_amount), 0) FROM orders 
             WHERE status != 'cancelled' AND DATE(created_at) = CURDATE()"
        );
        return $stmt->fetchColumn();
    }

    public function getRecentOrders($limit = 5) {
        $stmt = $this->conn->prepare(
            "SELECT o.*, u.name as customer_name
             FROM orders o LEFT JOIN users u ON o.user_id = u.id
             ORDER BY o.created_at DESC LIMIT :limit"
        );
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
