
# GREFSO Website

This is the web application for the GREFSO research group. It provides information about the group, its activities, team members, publications, and research areas.

## Features

- Public pages for About, Activities, Publications, Team, and Contact
- Admin dashboard for managing content (about sections, activities, events, team, publications, news, etc.)
- Responsive design with modern UI components
- Dynamic content fetched from a MySQL database
- User authentication for admin area

## Project Structure

```
.
├── about.php
├── activities.php
├── colloque2010.php
├── contact.php
├── grefso_db.sql
├── index.php
├── jdm.php
├── login.php
├── logout.php
├── publications.php
├── team.php
├── admin/
│   ├── about.php
│   ├── activities.php
│   ├── ...
│   └── includes/
├── assets/
│   ├── css/
│   ├── images/
│   └── js/
├── documents/
│   ├── doc/
│   ├── other/
│   └── pdf/
├── images/
├── includes/
├── pages/
└── README.md
```

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache, Nginx, etc.)

## Setup

1. **Clone the repository**  
   Download or clone this repository to your web server directory.

2. **Database Setup**  
   - Import the `grefso_db.sql` file into your MySQL server to create the necessary tables and sample data.
   - Update the database connection settings in [`includes/config.php`](includes/config.php).

3. **Configure File Permissions**  
   Ensure the web server has read access to all files and write access to any upload directories if needed.

4. **Access the Application**  
   - Public site: Open `index.php` in your browser.
   - Admin area: Go to `/admin/login.php` and log in with your admin credentials.

## Customization

- Update styles in [`assets/css/`](assets/css/)
- Add or update images in [`assets/images/`](assets/images/) or [`images/`](images/)
- Modify PHP templates in the root directory or under [`admin/`](admin/)

## License

This project is for educational and research purposes. Please contact the GREFSO team for usage permissions.

---

For any issues or questions, please contact the project maintainer.