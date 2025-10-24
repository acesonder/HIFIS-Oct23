-- HIFIS Database Schema
-- Homeless Individuals and Families Information System

CREATE DATABASE IF NOT EXISTS hifis_db;
USE hifis_db;

-- Clients table - stores information about homeless individuals and families
CREATE TABLE IF NOT EXISTS clients (
    client_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    date_of_birth DATE,
    gender ENUM('Male', 'Female', 'Other', 'Prefer not to say') DEFAULT 'Prefer not to say',
    phone VARCHAR(20),
    email VARCHAR(100),
    emergency_contact_name VARCHAR(100),
    emergency_contact_phone VARCHAR(20),
    veteran_status ENUM('Yes', 'No', 'Unknown') DEFAULT 'Unknown',
    disability_status ENUM('Yes', 'No', 'Unknown') DEFAULT 'Unknown',
    household_type ENUM('Individual', 'Family', 'Youth') DEFAULT 'Individual',
    household_size INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_name (last_name, first_name),
    INDEX idx_created (created_at)
);

-- Services table - types of services provided
CREATE TABLE IF NOT EXISTS services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    service_description TEXT,
    service_category ENUM('Emergency Shelter', 'Transitional Housing', 'Permanent Housing', 'Food Services', 'Health Services', 'Employment Services', 'Other') DEFAULT 'Other',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_service (service_name)
);

-- Client Services - tracks which services clients receive
CREATE TABLE IF NOT EXISTS client_services (
    cs_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    service_id INT NOT NULL,
    service_date DATE NOT NULL,
    service_status ENUM('Pending', 'Active', 'Completed', 'Cancelled') DEFAULT 'Active',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(service_id) ON DELETE CASCADE,
    INDEX idx_client (client_id),
    INDEX idx_service (service_id),
    INDEX idx_date (service_date)
);

-- Case Notes - documentation for case management
CREATE TABLE IF NOT EXISTS case_notes (
    note_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    note_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    note_content TEXT NOT NULL,
    note_type ENUM('General', 'Assessment', 'Follow-up', 'Emergency', 'Other') DEFAULT 'General',
    created_by VARCHAR(100),
    FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE,
    INDEX idx_client (client_id),
    INDEX idx_date (note_date)
);

-- Insert default services
INSERT INTO services (service_name, service_description, service_category) VALUES
('Emergency Shelter', 'Immediate shelter for homeless individuals', 'Emergency Shelter'),
('Transitional Housing', 'Temporary housing with supportive services', 'Transitional Housing'),
('Permanent Supportive Housing', 'Long-term housing with ongoing support', 'Permanent Housing'),
('Food Bank', 'Food assistance and meal programs', 'Food Services'),
('Medical Care', 'Health and medical services', 'Health Services'),
('Mental Health Services', 'Counseling and mental health support', 'Health Services'),
('Job Training', 'Employment skills and job placement', 'Employment Services'),
('Case Management', 'Coordinated access and case management', 'Other');
