<?php
require_once __DIR__ . '/../system/database.php';

$ordersSql = "CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  email VARCHAR(255) NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'pending',
  total_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
  billing_address TEXT,
  shipping_address TEXT,
  payment_id VARCHAR(50),
  created_at DATETIME NOT NULL,
  paid_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$orderItemsSql = "CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NULL,
  name VARCHAR(255),
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  quantity INT NOT NULL DEFAULT 1,
  FOREIGN KEY (order_id) REFERENCES orders(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

try {
    $pdo->exec($ordersSql);
    $pdo->exec($orderItemsSql);
    echo "Migratie voltooid\n";
} catch (PDOException $e) {
    echo "Fout: " . $e->getMessage() . "\n";
}
