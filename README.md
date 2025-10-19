
# AgriPulse Namibia — Farm Produce & Livestock Tracker

A simple marketplace for farmersm who are the admins on the platform to list and manage **Crops** and **Livestock**; buyers can register, log in, add to cart, checkout, and receive a receipt of their purchase through MailHog. 

## Tech we ysed
- PHP 8+ (XAMPP)
- MySQL/ PhPMyAdmin
- HTML/CSS/JS
- PHPMailer confirgured through Composer

## Quick Start (XAMPP on Windows)
1. Copy the whole **agripulse** folder to: `C:\xampp\htdocs\agripulse`
2. Open **XAMPP Control Panel** → Start **Apache** and **MySQL**.
3. Create DB:
   - Go to: <http://localhost/phpmyadmin>
   - Create database: `agripulse_db` (utf8mb4_general_ci)
   - Import `migrations.sql` from this folder.
4. Install dependencies:
   - Open **Command Prompt**:
     ```bat
     cd C:\xampp\htdocs\agripulse
     composer install
     ```
5. Configure app:
   - Update DB credentials if needed.
   - For local email testing, install MailHog (see below) and set SMTP to `127.0.0.1:1025` with no auth.
6. Visit the site:
   - <http://localhost/agripulse/public/>
7. The admin login information:
   - **Email:** farmer@agripulse.com
   - **Password:** farmer1

## SMTP Used
### A) Mailhog
1. Download MailHog for Windows (GitHub releases).
2. Run `MailHog.exe` (it opens SMTP :1025 and Web UI :8025).
3. Set in `app/config.php` → `MAIL_HOST=127.0.0.1`, `MAIL_PORT=1025`, `MAIL_USERNAME=""`, `MAIL_PASSWORD=""`, `MAIL_SECURE=""`
4. Check purchase confirmation emails at <http://localhost:8025>


## Project Structure
```
agripulse/
  app/
    bootstrap.php
    config.example.php
    db.php
    auth.php
    mailer.php
    helpers.php
    ProductRepository.php
    OrderService.php
  public/
    index.php
    login.php
    register.php
    logout.php
    product.php
    cart.php
    checkout.php
    thanks.php
    buyer/orders.php
    admin/dashboard.php
    admin/products.php
    admin/product_new.php
    admin/product_edit.php
    admin/orders.php
    assets/css/style.css
    assets/js/app.js
  storage/outbox/
  storage/receipts/
  migrations.sql
  composer.json
  README.md
```

## Receipts
- Sent to MailHog
