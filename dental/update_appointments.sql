USE gamo_dental;

CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    appointment_date DATE NOT NULL,
    treatment_type VARCHAR(100) NOT NULL,
    status ENUM('Pending', 'Assigned', 'With Nurse', 'At Pharmacy', 'At Cashier', 'Completed') DEFAULT 'Pending',
    assigned_doctor_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_doctor_id) REFERENCES employees(id) ON DELETE SET NULL
);
