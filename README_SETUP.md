# House Management System - Setup Guide

A modern multi-tenant house management system built with Laravel 12, Vue 3, and Inertia.js.

## Features

- **Multi-Tenancy**: Each landlord/agent operates in an isolated database context with subdomain-based access
- **Authentication**: Role-based authentication (Admin/Renter) using Laravel Breeze
- **Apartments Management**: Create, edit, view, and delete apartments
- **Renters Management**: Manage renters with search and filtering capabilities
- **SMS Integration**: Send bulk SMS via TalkSasa API
- **Email Integration**: Send bulk emails via Twilio SendGrid
- **Payment Integration**: Collect payments via Paystack with webhook support
- **Dashboard**: Analytics and summary cards with recent payment tracking
- **Responsive Design**: Mobile-friendly UI with Tailwind CSS

## Requirements

- PHP 8.3 or higher
- Composer
- Node.js 22.x
- MySQL/PostgreSQL/SQLite
- TalkSasa API credentials (optional)
- SendGrid API credentials (optional)
- Paystack API credentials (optional)

## Installation

### 1. Clone and Install Dependencies

```bash
cd house-management
composer install
npm install --legacy-peer-deps
```

### 2. Environment Configuration

Copy the example environment file:

```bash
cp .env.example .env
```

Update the following in `.env`:

```env
APP_NAME="House Management System"
APP_URL=http://localhost

# Database Configuration
DB_CONNECTION=sqlite
# OR for MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=house_management
# DB_USERNAME=root
# DB_PASSWORD=

# TalkSasa SMS Configuration
TALKSASA_API_KEY=your_api_key_here
TALKSASA_SENDER_ID=your_sender_id

# SendGrid Email Configuration
SENDGRID_API_KEY=your_sendgrid_api_key
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Paystack Payment Configuration
PAYSTACK_PUBLIC_KEY=your_paystack_public_key
PAYSTACK_SECRET_KEY=your_paystack_secret_key
```

### 3. Generate Application Key

```bash
php artisan key:generate
```

### 4. Run Migrations

```bash
# Run central database migrations (tenants table)
php artisan migrate

# Run tenant migrations
php artisan tenants:migrate
```

### 5. Build Frontend Assets

```bash
npm run build
# OR for development:
npm run dev
```

### 6. Start Development Server

```bash
php artisan serve
```

## Multi-Tenancy Setup

### Creating a Tenant

You can create tenants programmatically or via tinker:

```bash
php artisan tinker
```

```php
$tenant = \App\Models\Tenant::create(['id' => 'agent1']);
$tenant->domains()->create(['domain' => 'agent1.localhost']);
```

### Accessing Tenant

Access the tenant at: `http://agent1.localhost:8000`

For production, configure your DNS to point subdomains to your server.

## Database Structure

### Central Database Tables
- `tenants` - Tenant information
- `domains` - Tenant domains

### Tenant Database Tables
- `users` - User accounts (Admin/Renter)
- `apartments` - Apartment properties
- `renters` - Renter information
- `sms_logs` - SMS communication logs
- `email_logs` - Email communication logs
- `payments` - Payment transactions

## API Integrations

### TalkSasa SMS

The system integrates with TalkSasa for sending bulk SMS. Configure your API credentials in `.env`:

```env
TALKSASA_API_KEY=your_api_key
TALKSASA_SENDER_ID=your_sender_id
```

### Twilio SendGrid

Email functionality uses SendGrid API. Configure in `.env`:

```env
SENDGRID_API_KEY=your_api_key
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

### Paystack Payments

Payment processing via Paystack. Configure in `.env`:

```env
PAYSTACK_PUBLIC_KEY=pk_test_xxx
PAYSTACK_SECRET_KEY=sk_test_xxx
```

**Webhook URL**: `https://yourdomain.com/payments/webhook`

Configure this URL in your Paystack dashboard to receive payment notifications.

## Usage

### Admin Features

1. **Dashboard**: View statistics and recent payments
2. **Apartments**: Manage apartment properties
3. **Renters**: Add and manage renters
4. **SMS**: Send bulk SMS to selected or all renters
5. **Emails**: Send bulk emails to selected or all renters
6. **Payments**: Create payment records, track status, export to CSV

### Renter Features

Renters can:
- View their apartment details
- Check payment status
- Receive SMS and email notifications

## Development

### Running Tests

```bash
php artisan test
```

### Code Style

```bash
./vendor/bin/pint
```

### Building for Production

```bash
npm run build
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Deployment

1. Set up your web server (Apache/Nginx)
2. Configure subdomain wildcard DNS
3. Set proper file permissions
4. Configure environment variables
5. Run migrations
6. Build frontend assets
7. Set up SSL certificates for subdomains

## Security

- All routes are protected with authentication middleware
- CSRF protection enabled
- Input validation on all forms
- Role-based access control
- Secure API credential management

## Support

For issues and questions, please refer to the documentation or contact support.

## License

This project is proprietary software.
