# HIFIS - Homeless Individuals and Families Information System

[![Platform](https://img.shields.io/badge/Platform-Web-blue.svg)](https://github.com/acesonder/HIFIS-Oct23)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange.svg)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/License-Demo-green.svg)](LICENSE)

## Overview

HIFIS is a comprehensive web-based platform for managing homeless service delivery across Canada. Built with MySQL, PHP, HTML, CSS, and JavaScript with Ajax functionality, this system enables service providers to:

- **Manage client information** and case histories
- **Track services** provided to individuals and families
- **Coordinate access** through vulnerability assessments
- **Monitor resources** like shelter bed availability in real-time
- **Generate reports** and analytics for data-driven decisions
- **Protect privacy** with data anonymization for national reporting

## Quick Start

### Installation

1. **Prerequisites**: Web server, PHP 7.4+, MySQL 5.7+

2. **Setup Database**:
   ```bash
   mysql -u root -p -e "CREATE DATABASE hifis_db;"
   mysql -u root -p hifis_db < database.sql
   ```

3. **Configure**: Edit `includes/config.php` with your database credentials

4. **Access**: Navigate to `http://localhost/HIFIS-Oct23`

### Default Login
- **Username**: `admin`
- **Password**: `admin123`

## Key Features

### ✨ Client Management
- Complete intake and registration system
- Client history tracking
- Status management (active/inactive/exited)
- Search and filtering capabilities

### 📊 Coordinated Access
- Vulnerability assessments
- Housing priority classification
- Service referral system
- Priority-based allocation

### 🏠 Resource Management
- Real-time shelter capacity tracking
- Bed availability monitoring
- Auto-updating every 30 seconds
- Multiple resource types (beds, rooms, units)

### 📈 Reports & Analytics
- Demographic breakdowns
- Service utilization statistics
- Custom date range reports
- Export-ready data

### 🔐 Security & Privacy
- Role-based access control (Admin, Case Manager, Staff)
- Password-protected authentication
- Complete activity logging
- Data anonymization for national reporting

### ⚡ Real-time Features
- Ajax-powered updates
- Live shelter capacity
- Instant client search
- Dynamic dashboard statistics

## Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6)
- **Ajax**: Vanilla JavaScript XMLHttpRequest
- **Design**: Custom responsive CSS

## Project Structure

```
HIFIS-Oct23/
├── api/              # Ajax API endpoints
├── css/              # Stylesheets
├── includes/         # Core PHP files
├── js/               # JavaScript files
├── assets/           # Images and static files
├── database.sql      # Database schema
├── *.php             # Application pages
└── DOCUMENTATION.md  # Complete documentation
```

## Documentation

For complete setup instructions, usage guide, and API documentation, see [DOCUMENTATION.md](DOCUMENTATION.md)

## Screenshots

### Dashboard
Real-time statistics and recent activity

### Client Management
Complete client information and service history

### Resource Tracking
Live shelter capacity monitoring

### Reports
Comprehensive analytics and demographics

## How HIFIS Works

1. **Data Collection**: Service providers collect and manage client information
2. **Client Management**: Track clients through their service journey
3. **Community Coordination**: Share data across providers for coordinated support
4. **National Contribution**: Anonymized data helps understand homelessness trends
5. **Improved Delivery**: Data-driven decisions improve service effectiveness

## Contributing

This is a demonstration project showcasing a complete HIFIS implementation. For production use:
- Change default credentials
- Enable HTTPS/SSL
- Configure appropriate file permissions
- Implement backup systems
- Review security settings

## Version

**v1.0.0** - October 2023

## Support

For questions or issues, please refer to the [DOCUMENTATION.md](DOCUMENTATION.md) file or contact your system administrator.

## License

Provided as-is for educational and demonstration purposes.

---

**Built to support communities in preventing and ending homelessness through better data management and coordination.**