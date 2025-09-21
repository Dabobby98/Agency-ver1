# Email System Setup Documentation

## Overview
The website now has a fully functional email system using PHPMailer for both contact forms and newsletter subscriptions.

## Features Implemented
- ✅ Professional contact form with HTML and plain text versions
- ✅ Newsletter subscription system with admin notifications
- ✅ SMTP configuration with proper port/encryption handling
- ✅ Error logging and debugging capabilities
- ✅ Secure credential management via environment variables

## SMTP Configuration
The system automatically configures encryption based on the port:
- **Port 465**: Uses SMTPS (implicit TLS)
- **Port 587**: Uses STARTTLS (explicit TLS)

## Environment Variables Required
```
SMTP_HOST=smtp.gmail.com
SMTP_PORT=465
SMTP_USERNAME=your_email@gmail.com
SMTP_PASSWORD=your_app_password
FROM_EMAIL=your_email@gmail.com
FROM_NAME=Your Company Name
CONTACT_EMAIL=contact@yourcompany.com
NEWSLETTER_EMAIL=newsletter@yourcompany.com
```

## Gmail Setup Instructions
1. Enable 2-Factor Authentication on your Gmail account
2. Generate an App Password:
   - Go to Google Account settings
   - Security → App passwords
   - Generate a password for "Mail"
   - Use this App Password in SMTP_PASSWORD (not your regular password)

## Files Modified
- `php/EmailService.php` - Main email service class
- `php/form_process.php` - Contact form processing
- `php/newsletter_process.php` - Newsletter subscription processing
- `composer.json` - PHPMailer dependency

## Email Templates
- Contact forms include sender information, subject, and message
- Newsletter confirmations are sent to both admin and subscriber
- All emails support both HTML and plain text formats

## Security Features
- HTML sanitization of form inputs
- Environment variable credential storage
- Professional error messages to users
- Detailed error logging for administrators

## Testing
Both contact form and newsletter functionality have been tested and are working correctly with the provided SMTP credentials.

## Troubleshooting
- Check error logs for detailed SMTP debugging information
- Verify Gmail App Password is correctly set
- Ensure port/encryption combination is correct
- Check that outbound SMTP connections are allowed by hosting provider