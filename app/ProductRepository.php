
<?php
class ProductRepository {
    private PDO $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function allByType(string $type): array {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE type = ? AND is_active = 1 ORDER BY created_at DESC');
        $stmt->execute([$type]);
        return $stmt->fetchAll();
    }

    /**
     * Return all products for a type, including inactive ones.
     * This keeps the original allByType behavior unchanged and provides
     * a method for pages that want to surface inactive items.
     *
     * @param string $type
     * @return array
     */
    public function allByTypeWithInactive(string $type): array {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE type = ? ORDER BY created_at DESC');
        $stmt->execute([$type]);
        return $stmt->fetchAll();
    }

    public function find(int $id) {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

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

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM products WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function decrementStock(int $id, int $qty): bool {
        $stmt = $this->pdo->prepare('UPDATE products SET quantity = quantity - :qty WHERE id = :id AND quantity >= :qty');
        $stmt->execute([':qty' => $qty, ':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
