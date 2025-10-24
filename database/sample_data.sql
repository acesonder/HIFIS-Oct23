-- HIFIS Sample Data
-- This file contains sample data for testing purposes
-- Run this AFTER installing the schema

USE hifis_db;

-- Insert sample clients
INSERT INTO clients (first_name, last_name, date_of_birth, gender, phone, email, emergency_contact_name, emergency_contact_phone, veteran_status, disability_status, household_type, household_size) VALUES
('John', 'Smith', '1985-03-15', 'Male', '555-0101', 'john.smith@email.com', 'Mary Smith', '555-0102', 'No', 'No', 'Individual', 1),
('Sarah', 'Johnson', '1990-07-22', 'Female', '555-0201', 'sarah.j@email.com', 'Bob Johnson', '555-0202', 'No', 'Yes', 'Individual', 1),
('Michael', 'Williams', '1978-11-30', 'Male', '555-0301', NULL, 'Jennifer Williams', '555-0302', 'Yes', 'Yes', 'Individual', 1),
('Emily', 'Brown', '1995-05-18', 'Female', '555-0401', 'emily.brown@email.com', NULL, NULL, 'No', 'No', 'Family', 3),
('David', 'Martinez', '2003-09-10', 'Male', '555-0501', NULL, 'Anna Martinez', '555-0502', 'No', 'No', 'Youth', 1),
('Lisa', 'Anderson', '1982-12-25', 'Female', '555-0601', 'lisa.a@email.com', 'Tom Anderson', '555-0602', 'No', 'No', 'Family', 4),
('James', 'Taylor', '1970-04-08', 'Male', '555-0701', NULL, NULL, NULL, 'Yes', 'Yes', 'Individual', 1),
('Maria', 'Garcia', '1988-08-14', 'Female', '555-0801', 'maria.garcia@email.com', 'Carlos Garcia', '555-0802', 'No', 'No', 'Family', 2),
('Robert', 'Lee', '1992-02-28', 'Male', '555-0901', NULL, 'Susan Lee', '555-0902', 'No', 'No', 'Individual', 1),
('Jennifer', 'White', '1998-06-05', 'Female', '555-1001', 'jen.white@email.com', 'Mark White', '555-1002', 'No', 'No', 'Youth', 1);

-- Insert sample client services
INSERT INTO client_services (client_id, service_id, service_date, service_status, notes) VALUES
(1, 1, '2024-10-01', 'Active', 'Initial emergency shelter placement'),
(1, 5, '2024-10-05', 'Completed', 'Medical checkup completed'),
(2, 2, '2024-10-03', 'Active', 'Transitional housing program started'),
(2, 6, '2024-10-10', 'Active', 'Weekly counseling sessions'),
(3, 1, '2024-09-15', 'Completed', 'Emergency shelter - transitioned to permanent housing'),
(3, 3, '2024-10-01', 'Active', 'Permanent supportive housing secured'),
(4, 4, '2024-10-08', 'Active', 'Weekly food bank visits for family'),
(5, 7, '2024-10-12', 'Active', 'Job training program enrollment'),
(6, 2, '2024-09-20', 'Active', 'Family transitional housing'),
(7, 3, '2024-08-15', 'Active', 'Veteran permanent housing program'),
(8, 8, '2024-10-15', 'Active', 'Case management services'),
(9, 1, '2024-10-18', 'Active', 'Emergency shelter placement'),
(10, 7, '2024-10-10', 'Active', 'Youth employment program');

-- Insert sample case notes
INSERT INTO case_notes (client_id, note_content, note_type, created_by) VALUES
(1, 'Client presented at emergency shelter. Assessed immediate needs including food and clothing.', 'Assessment', 'Case Worker A'),
(1, 'Follow-up meeting scheduled for housing options discussion.', 'Follow-up', 'Case Worker A'),
(2, 'Client enrolled in mental health services. Shows good progress.', 'General', 'Case Worker B'),
(3, 'Veteran successfully transitioned to permanent housing with VA support.', 'General', 'Case Worker C'),
(4, 'Family of 3 receiving ongoing support. Children enrolled in school programs.', 'Assessment', 'Case Worker A'),
(5, 'Youth client participating well in job training. Good attendance record.', 'Follow-up', 'Case Worker D'),
(6, 'Large family unit. Coordinating multiple services for household needs.', 'General', 'Case Worker B'),
(7, 'Client dealing with PTSD. Connected with veteran support services.', 'Assessment', 'Case Worker C'),
(8, 'Regular case management check-ins. Family is stable in transitional housing.', 'Follow-up', 'Case Worker A'),
(10, 'Youth client showing interest in continuing education programs.', 'General', 'Case Worker D');

SELECT 'Sample data inserted successfully!' as Status;
SELECT COUNT(*) as 'Sample Clients' FROM clients;
SELECT COUNT(*) as 'Sample Services Assigned' FROM client_services;
SELECT COUNT(*) as 'Sample Case Notes' FROM case_notes;
