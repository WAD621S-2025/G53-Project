
-- Create tables
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('ADMIN','BUYER') NOT NULL DEFAULT 'BUYER',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type ENUM('CROP','LIVESTOCK') NOT NULL,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  unit VARCHAR(32) NOT NULL, -- e.g., kg, head, bags
  unit_price DECIMAL(10,2) NOT NULL,
  quantity INT NOT NULL DEFAULT 0,
  avg_weight_kg DECIMAL(10,2) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  buyer_id INT NOT NULL,
  total_amount DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed sample products
INSERT INTO products (type, name, description, unit, unit_price, quantity, avg_weight_kg, is_active) VALUES
('CROP','Maize','Dry yellow maize','kg', 12.50, 1000, NULL, 1),
('CROP','Mahangu','Pearl millet','kg', 15.00, 800, NULL, 1),
('LIVESTOCK','Goat','Kalahari red','head', 1500.00, 25, 18.00, 1),
('LIVESTOCK','Chicken','Free-range broiler','head', 85.00, 120, 1.80, 1);
