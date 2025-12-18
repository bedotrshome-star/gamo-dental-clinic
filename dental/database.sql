CREATE DATABASE IF NOT EXISTS gamo_dental;
USE gamo_dental;

CREATE TABLE IF NOT EXISTS admin (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin user
-- Password is 'admin123' encrypted with MD5
INSERT INTO admin (username, password) VALUES ('admin', MD5('admin123'));

CREATE TABLE IF NOT EXISTS employees (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('Employer', 'Doctor', 'Nurse', 'Cashier', 'Pharmacist') NOT NULL DEFAULT 'Employer',
    must_change_password TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

