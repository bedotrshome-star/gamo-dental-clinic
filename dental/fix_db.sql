-- RUN THIS SCRIPT IN YOUR PHPMYADMIN SQL TAB TO FIX THE ERROR

USE gamo_dental;

-- Add 'role' column if it doesn't exist
ALTER TABLE employees 
ADD COLUMN IF NOT EXISTS role ENUM('Employer', 'Doctor', 'Nurse', 'Cashier', 'Pharmacist') NOT NULL DEFAULT 'Employer';

-- Add 'must_change_password' column if it doesn't exist
ALTER TABLE employees 
ADD COLUMN IF NOT EXISTS must_change_password TINYINT(1) DEFAULT 1;
