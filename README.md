
# AgriPulse Namibia — Farm Produce & Livestock Tracker

**Goal:** Simple marketplace for farmers (admin) to list **Crops** and **Livestock**; buyers can register, log in, add to cart, checkout, and receive an HTML receipt. SMTP email receipts supported via PHPMailer (local dev with MailHog or external SMTP).

## Tech
- PHP 8+ (XAMPP)
- MySQL (MariaDB)
- HTML/CSS/JS
- PHPMailer (via Composer)

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
   - Copy `app/config.example.php` to `app/config.php`
   - Update DB credentials if needed.
   - For local email testing, install MailHog (see below) and set SMTP to `127.0.0.1:1025` with no auth.
6. Visit the site:
   - <http://localhost/agripulse/public/>
7. Default admin (farmer) login:
   - **Email:** farmer@agripulse.com
   - **Password:** farmer1

## SMTP Options
### A) Local dev — MailHog (recommended for demo)
1. Download MailHog for Windows (GitHub releases).
2. Run `MailHog.exe` (it opens SMTP :1025 and Web UI :8025).
3. Set in `app/config.php` → `MAIL_HOST=127.0.0.1`, `MAIL_PORT=1025`, `MAIL_USERNAME=""`, `MAIL_PASSWORD=""`, `MAIL_SECURE=""`
4. Check emails at <http://localhost:8025>

### B) External SMTP (e.g., Brevo/Sendinblue or Gmail)
- Set `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM`, `MAIL_FROM_NAME` in `app/config.php`.
- Gmail requires 2FA + App Passwords; otherwise use a provider like Brevo.

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
- Always saved to `storage/receipts/order_{id}.html`
- If SMTP configured, also emailed to the buyer. A copy of the HTML is saved to `storage/outbox/`

## Git
```bash
git init
git remote add origin https://github.com/got-fry/G53-Project
git add .
git commit -m "Starter code: AgriPulse tracker"
git push -u origin main
```
