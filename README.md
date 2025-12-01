# Event Registration PHTML

A PHP-based event registration and management system. This project allows administrators and clients to manage events, participants, and registrations with a modern UI and secure authentication.

## Features
- Admin and client dashboards with role-based access
- Event creation, update, and deletion
- Participant management
- Registration and login system with secure password hashing
- Responsive design with Bootstrap 5 and custom styling
- Modern UI with gradients, animations, and hover effects

## Getting Started

### Quick Setup (Recommended)
1. Clone the repository
2. Start your local server (WAMP/XAMPP)
3. Access the setup script: `http://localhost/EventRegistration%20PHTML/php/setup_database.php`
4. Click "Initialize Database" to create all tables and add default users
5. Login with the credentials below

### Manual Setup
1. Clone the repository
2. Import `database/schema.sql` into your MySQL server
3. Configure database settings in `php/dbconnect.php` if needed
4. Start your local server (WAMP/XAMPP)
5. Access the app via your browser

## Database Configuration

The default database settings are:
- **Host:** localhost
- **Database:** gestion_evenements
- **User:** root
- **Password:** (empty)

Edit `php/dbconnect.php` if your settings are different.

## Default Login Credentials

### Admin Account
- **Username:** `admin`
- **Password:** `admin123`
- **Email:** admin@evenements.com
- **Role:** Administrator (full access to manage events, participants, and registrations)

### Client Account (Demo)
- **Username:** `client`
- **Password:** `client123`
- **Email:** client@evenements.com
- **Role:** Client (can view and register for events)

**Note:** Change these passwords in production!

## Folder Structure
- `php/` - PHP controllers and logic
- `phtml/` - PHTML templates (views)
- `database/` - SQL schema and setup files
- `style.css` - Custom styles with CSS variables
- `assets/js/` - Custom JavaScript

## Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- WAMP/XAMPP or similar local server
- PDO extension enabled

## User Roles

### Administrator
- Full access to all features
- Manage events (create, update, delete)
- Manage participants (add, edit, delete)
- View all registrations
- Access admin dashboard with statistics

### Client
- View available events
- Register for events
- View personal registrations
- Access client dashboard
- Cannot manage events or participants

## License
MIT
