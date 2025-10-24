-- HIFIS Database Schema
-- Homeless Individuals and Families Information System

CREATE DATABASE IF NOT EXISTS hifis_db;
USE hifis_db;

-- Users table (for service providers)
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('admin', 'case_manager', 'staff') DEFAULT 'staff',
    organization VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE
);

-- Clients table
CREATE TABLE IF NOT EXISTS clients (
    client_id INT AUTO_INCREMENT PRIMARY KEY,
    unique_identifier VARCHAR(50) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE,
    gender ENUM('Male', 'Female', 'Other', 'Prefer not to say'),
    phone VARCHAR(20),
    email VARCHAR(100),
    veteran_status BOOLEAN DEFAULT FALSE,
    chronic_homelessness BOOLEAN DEFAULT FALSE,
    entry_date DATE NOT NULL,
    exit_date DATE NULL,
    status ENUM('active', 'inactive', 'exited') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (created_by) REFERENCES users(user_id)
);

-- Client History
CREATE TABLE IF NOT EXISTS client_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    history_type ENUM('intake', 'assessment', 'housing', 'service', 'exit'),
    description TEXT,
    recorded_date DATE NOT NULL,
    recorded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE,
    FOREIGN KEY (recorded_by) REFERENCES users(user_id)
);

-- Services table
CREATE TABLE IF NOT EXISTS services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    service_type ENUM('shelter', 'food', 'medical', 'counseling', 'employment', 'education', 'other'),
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Client Services (tracking services provided to clients)
CREATE TABLE IF NOT EXISTS client_services (
    client_service_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    service_id INT NOT NULL,
    service_date DATE NOT NULL,
    notes TEXT,
    provided_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(service_id),
    FOREIGN KEY (provided_by) REFERENCES users(user_id)
);

-- Shelter Resources
CREATE TABLE IF NOT EXISTS shelter_resources (
    resource_id INT AUTO_INCREMENT PRIMARY KEY,
    resource_name VARCHAR(100) NOT NULL,
    resource_type ENUM('bed', 'room', 'unit') DEFAULT 'bed',
    total_capacity INT NOT NULL,
    occupied INT DEFAULT 0,
    available INT AS (total_capacity - occupied) STORED,
    location VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Assessments table (for coordinated access)
CREATE TABLE IF NOT EXISTS assessments (
    assessment_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    assessment_date DATE NOT NULL,
    vulnerability_score INT,
    housing_priority ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    assessment_notes TEXT,
    assessed_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE,
    FOREIGN KEY (assessed_by) REFERENCES users(user_id)
);

-- Referrals (for coordinated access)
CREATE TABLE IF NOT EXISTS referrals (
    referral_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    service_id INT NOT NULL,
    referral_date DATE NOT NULL,
    status ENUM('pending', 'accepted', 'declined', 'completed') DEFAULT 'pending',
    priority ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    notes TEXT,
    referred_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(service_id),
    FOREIGN KEY (referred_by) REFERENCES users(user_id)
);

-- Activity Log (for audit trail)
CREATE TABLE IF NOT EXISTS activity_log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(50),
    record_id INT,
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, full_name, email, role, organization) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin@hifis.local', 'admin', 'HIFIS Central');

-- Insert sample services
INSERT INTO services (service_name, service_type, description) VALUES
('Emergency Shelter', 'shelter', 'Temporary emergency shelter accommodation'),
('Food Bank', 'food', 'Food assistance program'),
('Medical Clinic', 'medical', 'Basic medical services'),
('Mental Health Counseling', 'counseling', 'Mental health support services'),
('Job Training', 'employment', 'Employment readiness and job training'),
('Adult Education', 'education', 'GED and basic education programs');

-- Insert sample shelter resources
INSERT INTO shelter_resources (resource_name, resource_type, total_capacity, occupied, location) VALUES
('Main Shelter - Male Wing', 'bed', 50, 35, '123 Main Street'),
('Main Shelter - Female Wing', 'bed', 30, 20, '123 Main Street'),
('Family Units', 'unit', 10, 6, '125 Main Street'),
('Emergency Beds', 'bed', 20, 15, '123 Main Street');
