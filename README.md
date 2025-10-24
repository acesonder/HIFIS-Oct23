# HIFIS - Homeless Individuals and Families Information System

A comprehensive web-based data collection and case management system for homelessness service providers in Canada. Built with MySQL, PHP, HTML, CSS, JavaScript, and Ajax.

## Overview

HIFIS (Homeless Individuals and Families Information System) is designed to support homelessness service providers by:

- Managing client information and case data
- Tracking service delivery in real-time
- Supporting Coordinated Access implementation
- Enabling data analysis at local and national levels
- Facilitating day-to-day operations for service providers

## Features

### Core Functionality
- **Client Management**: Add, edit, view, and search client records
- **Real-time Updates**: Ajax-powered interface for instant data updates
- **Service Tracking**: Monitor services provided to clients
- **Case Management**: Document case notes and track client progress
- **Reports & Analytics**: View demographics, service utilization, and trends
- **Responsive Design**: Mobile-friendly interface

### Technical Features
- Secure database operations with prepared statements
- Real-time search functionality using Ajax
- RESTful API endpoints for data operations
- Modern, responsive UI with CSS Grid and Flexbox
- Form validation (client-side and server-side)
- XSS protection through HTML escaping

## Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Ajax**: XMLHttpRequest for asynchronous operations
- **Architecture**: MVC-inspired structure

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache or Nginx web server
- Web browser (Chrome, Firefox, Safari, Edge)

### Setup Instructions

1. **Clone the Repository**
   ```bash
   git clone https://github.com/acesonder/HIFIS-Oct23.git
   cd HIFIS-Oct23
   ```

2. **Database Setup**
   - Create a MySQL database:
     ```sql
     CREATE DATABASE hifis_db;
     ```
   - Import the schema:
     ```bash
     mysql -u root -p hifis_db < database/schema.sql
     ```

3. **Configure Database Connection**
   - Copy the database configuration template:
     ```bash
     cp includes/db_config.php.example includes/db_config.php
     ```
   - Edit `includes/db_config.php` and update your database credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'your_username');
     define('DB_PASS', 'your_password');
     define('DB_NAME', 'hifis_db');
     ```

4. **Web Server Configuration**
   - Point your web server document root to the project directory
   - Ensure PHP is enabled
   - For Apache, enable mod_rewrite if using URL rewriting

5. **Set Permissions**
   ```bash
   chmod 755 /path/to/HIFIS-Oct23
   chmod 644 /path/to/HIFIS-Oct23/includes/db_config.php
   ```

6. **Access the Application**
   - Open your web browser
   - Navigate to: `http://localhost/HIFIS-Oct23/` (or your configured URL)

## Project Structure

```
HIFIS-Oct23/
├── api/                      # Ajax API endpoints
│   ├── add_client.php       # Add new client
│   ├── delete_client.php    # Delete client
│   ├── search_clients.php   # Search clients
│   └── dashboard_stats.php  # Dashboard statistics
├── assets/
│   ├── css/
│   │   └── style.css        # Main stylesheet
│   └── js/
│       └── main.js          # JavaScript and Ajax functions
├── database/
│   └── schema.sql           # Database schema
├── includes/
│   ├── db_config.php        # Database configuration
│   ├── header.php           # Common header
│   └── footer.php           # Common footer
├── index.php                # Dashboard/home page
├── clients.php              # Client list
├── add_client.php           # Add client form
├── edit_client.php          # Edit client form
├── client_details.php       # Client details view
├── services.php             # Services management
├── reports.php              # Reports and analytics
├── .gitignore               # Git ignore file
└── README.md                # This file
```

## Usage Guide

### Adding a New Client
1. Navigate to "Clients" in the navigation menu
2. Click "Add New Client"
3. Fill in the client information form
4. Click "Save Client"
5. The system will validate and save the data using Ajax

### Searching for Clients
1. Go to the "Clients" page
2. Use the search box at the top
3. Type any part of the client's name, phone, or email
4. Results update automatically as you type (Ajax-powered)

### Viewing Client Details
1. From the clients list, click "View" next to any client
2. See complete client information, services received, and case notes

### Managing Services
1. Navigate to "Services"
2. View available service categories
3. See recent client service assignments

### Viewing Reports
1. Click "Reports" in the navigation
2. View demographics, trends, and service utilization
3. Use data for planning and compliance reporting

## API Endpoints

All API endpoints return JSON responses:

- `POST /api/add_client.php` - Add a new client
- `POST /api/delete_client.php` - Delete a client
- `GET /api/search_clients.php?q={search_term}` - Search clients
- `GET /api/dashboard_stats.php` - Get dashboard statistics

## Security Features

- **SQL Injection Protection**: All database queries use prepared statements
- **XSS Prevention**: All output is escaped using `htmlspecialchars()`
- **Input Validation**: Both client-side and server-side validation
- **Secure Configuration**: Database credentials stored in separate config file

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Contributing

This is an educational/demonstration project. For production use, consider:
- Adding user authentication and authorization
- Implementing role-based access control
- Adding audit logging
- Implementing data encryption
- Adding comprehensive error handling
- Creating automated tests

## License

This project is provided as-is for educational purposes.

## Support

For issues or questions about this implementation, please create an issue in the GitHub repository.

## Acknowledgments

HIFIS is inspired by the Canadian Homeless Individuals and Families Information System used by homelessness service providers across Canada to support coordinated access and data-driven decision making.