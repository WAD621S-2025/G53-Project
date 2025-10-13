
<?php
class OrderService {
    private PDO $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function createOrder(int $buyerId, array $items): int {
        $this->pdo->beginTransaction();
        try {
            $total = 0;
            foreach ($items as $it) {
                $total += $it['unit_price'] * $it['quantity'];
            }
            $stmt = $this->pdo->prepare('INSERT INTO orders (buyer_id, total_amount) VALUES (?, ?)');
            $stmt->execute([$buyerId, $total]);
            $orderId = (int)$this->pdo->lastInsertId();

            $insItem = $this->pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)');
            $decStock = $this->pdo->prepare('UPDATE products SET quantity = quantity - ? WHERE id = ? AND quantity >= ?');

            foreach ($items as $it) {
                $insItem->execute([$orderId, $it['product_id'], $it['quantity'], $it['unit_price']]);
                $decStock->execute([$it['quantity'], $it['product_id'], $it['quantity']]);
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

    public function getOrderWithItems(int $orderId) {
        $stmt = $this->pdo->prepare('SELECT o.*, u.name as buyer_name, u.email as buyer_email FROM orders o JOIN users u ON u.id=o.buyer_id WHERE o.id=?');
        $stmt->execute([$orderId]);
        $order = $stmt->fetch();
        if (!$order) return null;
        $it = $this->pdo->prepare('SELECT oi.*, p.name FROM order_items oi JOIN products p ON p.id=oi.product_id WHERE oi.order_id=?');
        $it->execute([$orderId]);
        $order['items'] = $it->fetchAll();
        return $order;
    }

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
