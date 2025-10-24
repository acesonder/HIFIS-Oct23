-- HIFIS Quick Installation Script
-- Run this script to set up the complete database

-- Drop existing database if it exists (CAUTION: This will delete all data!)
-- Uncomment the line below if you want to start fresh
-- DROP DATABASE IF EXISTS hifis_db;

-- Create database
CREATE DATABASE IF NOT EXISTS hifis_db;
USE hifis_db;

-- Show progress
SELECT 'Creating database tables...' as Status;

-- Import the schema
SOURCE schema.sql;

-- Verify installation
SELECT 'Installation complete!' as Status;
SELECT 'Checking tables...' as Status;

-- Show created tables
SHOW TABLES;

-- Show service count
SELECT COUNT(*) as 'Default Services Installed' FROM services;

SELECT 'HIFIS database is ready to use!' as Status;
