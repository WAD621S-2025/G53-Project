

<?php
// Repository class for product-related database operations
class ProductRepository {
    private PDO $pdo;

    // Constructor: accepts a PDO instance for DB access
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    // Returns all active products of a given type (CROP or LIVESTOCK)
    public function allByType(string $type): array {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE type = ? AND is_active = 1 ORDER BY created_at DESC');
        $stmt->execute([$type]);
        return $stmt->fetchAll();
    }

    /**
     * Returns all products for a type, including inactive ones.
     * Used for admin/product management views.
     */
    public function allByTypeWithInactive(string $type): array {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE type = ? ORDER BY created_at DESC');
        $stmt->execute([$type]);
        return $stmt->fetchAll();
    }

    // Finds a product by its ID (returns all columns)
    public function find(int $id) {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Creates a new product record and returns its new ID
    public function create(array $data): int {
        $sql = 'INSERT INTO products (type, name, description, unit, unit_price, quantity, avg_weight_kg, is_active)
                VALUES (:type, :name, :description, :unit, :unit_price, :quantity, :avg_weight_kg, :is_active)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':type' => $data['type'],
            ':name' => $data['name'],
            ':description' => $data['description'] ?? '',
            ':unit' => $data['unit'],
            ':unit_price' => $data['unit_price'],
            ':quantity' => $data['quantity'],
            ':avg_weight_kg' => $data['avg_weight_kg'] ?? null,
            ':is_active' => $data['is_active'] ?? 1,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    // Updates an existing product record by ID
    public function update(int $id, array $data): bool {
        $sql = 'UPDATE products SET type=:type, name=:name, description=:description, unit=:unit, unit_price=:unit_price, quantity=:quantity, avg_weight_kg=:avg_weight_kg, is_active=:is_active WHERE id=:id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':type' => $data['type'],
            ':name' => $data['name'],
            ':description' => $data['description'] ?? '',
            ':unit' => $data['unit'],
            ':unit_price' => $data['unit_price'],
            ':quantity' => $data['quantity'],
            ':avg_weight_kg' => $data['avg_weight_kg'] ?? null,
            ':is_active' => $data['is_active'] ?? 1,
        ]);
    }

    // Deletes a product by ID
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM products WHERE id = ?');
        return $stmt->execute([$id]);
    }

    // Decrements stock quantity for a product (used after purchase)
    public function decrementStock(int $id, int $qty): bool {
        $stmt = $this->pdo->prepare('UPDATE products SET quantity = quantity - :qty WHERE id = :id AND quantity >= :qty');
        $stmt->execute([':qty' => $qty, ':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
