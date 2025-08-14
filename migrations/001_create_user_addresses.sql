CREATE TABLE IF NOT EXISTS user_addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('billing','shipping') NOT NULL,
    company_name VARCHAR(255) DEFAULT NULL,
    kvk_number VARCHAR(255) DEFAULT NULL,
    vat_number VARCHAR(255) DEFAULT NULL,
    phone_number VARCHAR(50) DEFAULT NULL,
    street VARCHAR(255) DEFAULT NULL,
    postal_code VARCHAR(20) DEFAULT NULL,
    city VARCHAR(100) DEFAULT NULL,
    country VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_type (user_id, type),
    CONSTRAINT fk_user_addresses_user FOREIGN KEY (user_id) REFERENCES site_users(id) ON DELETE CASCADE
);
