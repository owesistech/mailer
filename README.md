# 📧 PHP SMTP Email API

A lightweight REST API for sending emails via SMTP using PHPMailer, with CORS support and Base64 payload handling.

---

## 🚀 Features

- SMTP email sending
- REST API (`/send`)
- CORS enabled
- Base64 email body support
- `.env` configuration
- PHPMailer integration
- JSON responses
- Simple deployment

---

## 📦 Requirements

- PHP 8+
- Composer
- SMTP server (e.g. Gmail, Mailgun, custom SMTP)

---

## 📥 Installation

```bash
git clone https://your-repo-url.git
cd smtp-email-api
composer install

📦 Install Dependencies
composer require phpmailer/phpmailer
composer require vlucas/phpdotenv

⚙️ Setup .env

Create a .env file in root:
SMTP_HOST=smtp.example.com
SMTP_PORT=587
SMTP_USER=your@email.com
SMTP_PASS=yourpassword
SMTP_ENCRYPTION=tls

MAIL_FROM_EMAIL=your@email.com
MAIL_FROM_NAME="SMTP API"


▶️ Run API

If using Apache:
https://your-domain.com/send

If using local PHP server:
php -S localhost:8000

📡 API Endpoint
Send Email

POST /send

Headers
```Content-Type: application/json```

**📤 Request Body**
{
  "from": "sender@example.com",
  "to": "receiver@example.com",
  "subject": "Hello",
  "body": "PGgxPkhlbGxvIFdvcmxkPC9oMT4="
}
```⚠️ body must be Base64 encoded HTML```

**📥 Response **
Success
{
  "success": true,
  "message": "Email sent successfully"
}

**Error**
{
  "success": false,
  "message": "Missing required fields"
}

📌 Endpoint Example
POST https://yourdomain.com/send

✨ Author
@Frank Galos
Built for scalable SMTP email delivery APIs.
