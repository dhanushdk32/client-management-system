# 🏢 Client Management System

A full-featured, multi-guard enterprise portal built with **Laravel 12**, **MySQL**, and **Bootstrap 5**. The system provides separate and isolated portals for **Administrators** and **Clients** with automated email notifications, OTP-based password recovery, document management, support ticketing, and real-time activity auditing.

---

## ✨ Key Features

### 🛡️ Dual-Guard Authentication & Portals
- **Isolated Admin Portal (`/login/admin`):** Dedicated executive dashboard for company administrators.
- **Isolated Client Portal (`/login/client`):** Customized portal for clients to track services, raise tickets, and verify documents.
- **Smart Middleware Redirection:** Protects administrative routes and automatically routes users to their respective login portals.

### 👥 Client Management & Auto-Onboarding
- Admin can register clients with full company profile details.
- **Automated Welcome Email:** Automatically generates a secure initial password and emails it directly to the newly registered client via SMTP.
- Instant user account provisioning in `client_users`.

### 🔐 Secure OTP Password Recovery
- Clean OTP password reset flow via Gmail SMTP (`smtp.gmail.com`).
- 6-digit cryptographic OTP generation with automated expiry.

### 📁 Document Verification & Management
- Document upload and download for clients and admins.
- Status tracking (Verified, Pending, Action Required).

### 🎫 Support Ticketing System
- Two-way ticketing and reply system between clients and admins.
- Real-time status updates (Open, In Progress, Closed).

### 📊 Reporting & Activity Auditing
- Real-time logging of user activity across modules.
- CSV/Excel export for clients and service summaries.

---

## 🛠️ Technology Stack

- **Backend:** Laravel 12 (PHP 8.2+)
- **Database:** MySQL
- **Frontend:** Blade, Bootstrap 5, Vanilla CSS, FontAwesome 6
- **Mailing:** Laravel Mailables (SMTP / Gmail SSL)
- **Authentication:** Dual Guard (`admin` via `portal_admins`, `client` via `client_users`)

---

## 🚀 Getting Started

### 1. Clone the Repository
```bash
git clone https://github.com/YOUR_USERNAME/client_management.git
cd client_management
```

### 2. Install Dependencies
```bash
composer install
npm install && npm run build
```

### 3. Environment Configuration
Copy the example environment file and generate the application key:
```bash
cp .env.example .env
php artisan key:generate
```

Configure your database and SMTP settings in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=client_db
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME="your-email@gmail.com"
MAIL_PASSWORD="your-google-app-password"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Run Migrations & Seeders
```bash
php artisan migrate
```

### 5. Start the Application
```bash
php artisan serve
```

Access the application in your browser:
- **Client Portal:** `http://127.0.0.1:8000/login/client`
- **Admin Portal:** `http://127.0.0.1:8000/login/admin`

---

## 📄 License
This project is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
