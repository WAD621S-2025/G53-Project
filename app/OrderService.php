

<?php
// Service class for handling order creation, retrieval, and listing
class OrderService {
    // PDO database connection
    private PDO $pdo;

    // Constructor: inject PDO connection
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Creates a new order for a buyer with given items
    // Deducts stock, inserts order and order_items, returns new order ID
    public function createOrder(int $buyerId, array $items): int {
        $this->pdo->beginTransaction();
        try {
            $total = 0;
            // Calculate total order amount
            foreach ($items as $it) {
                $total += $it['unit_price'] * $it['quantity'];
            }
            // Insert order row
            $stmt = $this->pdo->prepare('INSERT INTO orders (buyer_id, total_amount) VALUES (?, ?)');
            $stmt->execute([$buyerId, $total]);
            $orderId = (int)$this->pdo->lastInsertId();

            // Prepare statements for order items and stock update
            $insItem = $this->pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)');
            $decStock = $this->pdo->prepare('UPDATE products SET quantity = quantity - ? WHERE id = ? AND quantity >= ?');

            // Insert each order item and decrement stock
            foreach ($items as $it) {
                $insItem->execute([$orderId, $it['product_id'], $it['quantity'], $it['unit_price']]);
                $decStock->execute([$it['quantity'], $it['product_id'], $it['quantity']]);
                // If no stock was updated, throw error
                if ($decStock->rowCount() === 0) {
                    throw new Exception('Insufficient stock for product ' . $it['product_id']);
                }
            }

            $this->pdo->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // Retrieves an order and its items by order ID
    public function getOrderWithItems(int $orderId) {
        $stmt = $this->pdo->prepare('SELECT o.*, u.name as buyer_name, u.email as buyer_email FROM orders o JOIN users u ON u.id=o.buyer_id WHERE o.id=?');
        $stmt->execute([$orderId]);
        $order = $stmt->fetch();
        if (!$order) return null;
        // Fetch all items for this order
        $it = $this->pdo->prepare('SELECT oi.*, p.name FROM order_items oi JOIN products p ON p.id=oi.product_id WHERE oi.order_id=?');
        $it->execute([$orderId]);
        $order['items'] = $it->fetchAll();
        return $order;
    }

    // Lists all orders, or orders for a specific buyer if buyerId is given
    public function listOrders(?int $buyerId = null): array {
        if ($buyerId) {
            $stmt = $this->pdo->prepare('SELECT * FROM orders WHERE buyer_id=? ORDER BY created_at DESC');
            $stmt->execute([$buyerId]);
            return $stmt->fetchAll();
        }
        $stmt = $this->pdo->query('SELECT o.*, u.name as buyer_name FROM orders o JOIN users u ON u.id=o.buyer_id ORDER BY o.created_at DESC');
        return $stmt->fetchAll();
    }
}
