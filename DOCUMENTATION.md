# HIFIS - Homeless Individuals and Families Information System

A comprehensive web-based platform for managing homeless service delivery, built with MySQL, PHP, HTML, CSS, and JavaScript with Ajax functionality.

## Overview

HIFIS is a Canadian data collection and case management tool used by homelessness service providers to manage clients and share real-time data within a community. This system supports day-to-day operations, helps implement Coordinated Access, and contributes to a national understanding of homelessness.

## Features

### Core Functionality
- **Client Management**: Complete client intake, registration, and case management
- **Service Tracking**: Record and track all services provided to clients
- **Coordinated Access**: Assessment and prioritization system for housing allocation
- **Resource Management**: Real-time tracking of shelter beds and other resources
- **Reporting & Analytics**: Comprehensive reports and data analysis tools
- **User Management**: Multi-role user system (Admin, Case Manager, Staff)
- **Activity Logging**: Complete audit trail of all system actions
- **Data Privacy**: Protected client data with anonymization for national reporting

### Technical Features
- **Web-based**: Accessible from any web-enabled device
- **Real-time Updates**: Ajax-powered interface for live data updates
- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Secure Authentication**: Password-protected access with role-based permissions
- **Database-driven**: MySQL backend for reliable data storage

## Installation

### Prerequisites
- Web server (Apache, Nginx)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web browser (Chrome, Firefox, Safari, Edge)

### Setup Instructions

1. **Clone or download the repository**
   ```bash
   git clone https://github.com/acesonder/HIFIS-Oct23.git
   cd HIFIS-Oct23
   ```

2. **Set up the database**
   - Create a MySQL database named `hifis_db`
   - Import the database schema:
   ```bash
   mysql -u root -p hifis_db < database.sql
   ```

3. **Configure database connection**
   - Edit `includes/config.php`
   - Update the database credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'hifis_db');
   ```

4. **Set up web server**
   - Point your web server document root to the project directory
   - Ensure PHP is enabled
   - Make sure the `uploads` directory is writable

5. **Access the application**
   - Open your web browser
   - Navigate to `http://localhost/HIFIS-Oct23` (or your configured URL)
   - Log in with default credentials:
     - Username: `admin`
     - Password: `admin123`

## Default Login Credentials

**Administrator Account:**
- Username: `admin`
- Password: `admin123`

**Important:** Change the default password after first login!

## System Structure

### Directory Structure
```
HIFIS-Oct23/
├── api/                    # Ajax API endpoints
│   ├── add_service.php
│   ├── dashboard_stats.php
│   ├── search_clients.php
│   ├── shelter_capacity.php
│   └── update_client_status.php
├── css/                    # Stylesheets
│   └── style.css
├── includes/               # Core PHP files
│   ├── config.php         # Configuration
│   ├── db.php             # Database connection
│   ├── functions.php      # Utility functions
│   ├── header.php         # Page header
│   └── footer.php         # Page footer
├── js/                     # JavaScript files
│   └── main.js            # Main JavaScript with Ajax
├── assets/                 # Static assets
│   └── images/
├── uploads/                # User uploads
├── database.sql           # Database schema
├── index.php              # Entry point
├── login.php              # Login page
├── logout.php             # Logout handler
├── dashboard.php          # Main dashboard
├── clients.php            # Client management
├── client_details.php     # Client details view
├── add_service.php        # Add service form
├── services.php           # Services management
├── resources.php          # Shelter resources
├── assessments.php        # Client assessments
├── add_assessment.php     # Add assessment form
├── reports.php            # Reports and analytics
├── users.php              # User management (admin)
└── README.md              # This file
```

### Database Schema

**Main Tables:**
- `users` - System users (service providers)
- `clients` - Client information
- `client_history` - Client history records
- `services` - Available services
- `client_services` - Services provided to clients
- `shelter_resources` - Shelter capacity tracking
- `assessments` - Client vulnerability assessments
- `referrals` - Service referrals
- `activity_log` - System activity audit trail

## Usage Guide

### Managing Clients

1. **Add New Client**
   - Navigate to "Clients" menu
   - Click "Add New Client" button
   - Fill in client information
   - Submit the form

2. **View Client Details**
   - Go to Clients page
   - Click "View" on any client
   - See complete client information, history, and services

3. **Add Services**
   - From client details page, click "Add Service"
   - Select service type
   - Add notes and submit

### Tracking Resources

1. **View Shelter Capacity**
   - Navigate to "Resources" menu
   - See real-time bed availability
   - Data updates automatically every 30 seconds

### Assessments (Coordinated Access)

1. **Add Assessment**
   - From client details, click "Add Assessment"
   - Enter vulnerability score (0-100)
   - Set housing priority
   - Add notes

2. **View All Assessments**
   - Navigate to "Assessments" menu
   - See all client assessments with priority levels

### Reports

1. **Generate Reports**
   - Navigate to "Reports" menu
   - Select date range
   - View statistics and demographics
   - All data is anonymized for privacy

### User Management (Admin Only)

1. **View Users**
   - Navigate to "Users" menu (admin only)
   - See all system users
   - View login history

## Security Features

- **Authentication**: Secure login system with password hashing
- **Session Management**: Secure PHP sessions
- **Role-Based Access**: Three user roles (Admin, Case Manager, Staff)
- **Activity Logging**: Complete audit trail
- **Data Privacy**: Client data anonymization for national reporting
- **Input Sanitization**: Protection against SQL injection and XSS

## Data Privacy

HIFIS implements strict data privacy measures:
- Client personal information is protected
- Data is anonymized before national export
- Access is controlled by user roles
- All activities are logged for audit purposes
- Complies with Canadian privacy regulations

## Browser Compatibility

- Google Chrome (recommended)
- Mozilla Firefox
- Safari
- Microsoft Edge
- Internet Explorer 11+

## Support and Documentation

For questions or issues:
1. Check this documentation
2. Review the inline code comments
3. Check the activity log for system events
4. Contact your system administrator

## Contributing

This is a demonstration project. For production use:
1. Change all default passwords
2. Configure SSL/HTTPS
3. Set appropriate file permissions
4. Enable production error logging
5. Implement regular database backups
6. Review and update security settings

## License

This project is provided as-is for educational and demonstration purposes.

## Version

Version 1.0.0 - October 2023

---

**Note:** This system is designed to help communities manage homelessness services effectively while maintaining strict data privacy standards. Always ensure compliance with local privacy regulations and best practices.
