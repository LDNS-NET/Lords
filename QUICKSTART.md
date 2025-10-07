# House Management System - Quick Start Guide

## Getting Started in 5 Minutes

### Prerequisites
- PHP 8.3+
- Composer
- Node.js 22+
- SQLite (default) or MySQL/PostgreSQL

### Installation Steps

1. **Install Dependencies**
```bash
cd house-management
composer install
npm install --legacy-peer-deps
```

2. **Configure Environment**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Run Migrations**
```bash
php artisan migrate
```

4. **Build Frontend**
```bash
npm run build
```

5. **Start Server**
```bash
php artisan serve
```

Visit: `http://localhost:8000`

### Creating Your First Tenant

Open Laravel Tinker:
```bash
php artisan tinker
```

Create a tenant:
```php
$tenant = \App\Models\Tenant::create(['id' => 'demo']);
$tenant->domains()->create(['domain' => 'demo.localhost']);
exit
```

### Accessing the Tenant

1. **Update your hosts file** (optional for local development):
   - Windows: `C:\Windows\System32\drivers\etc\hosts`
   - Mac/Linux: `/etc/hosts`
   
   Add:
   ```
   127.0.0.1 demo.localhost
   ```

2. **Access the tenant**:
   Visit: `http://demo.localhost:8000`

3. **Register an account**:
   Click "Register" and create your admin account

### First Steps After Login

1. **Create an Apartment**
   - Navigate to "Apartments"
   - Click "Add Apartment"
   - Fill in the details

2. **Add a Renter**
   - Navigate to "Renters"
   - Click "Add Renter"
   - Select apartment and fill in renter details

3. **Create a Payment**
   - Navigate to "Payments"
   - Click "Create Payment"
   - Select renter and enter amount

4. **Send SMS/Email**
   - Configure API keys in `.env` first
   - Navigate to "SMS" or "Emails"
   - Compose and send to selected renters

### Development Mode

For hot-reloading during development:
```bash
npm run dev
```

In another terminal:
```bash
php artisan serve
```

### API Configuration (Optional)

To enable SMS, Email, and Payment features, add to `.env`:

```env
# TalkSasa SMS
TALKSASA_API_KEY=your_api_key
TALKSASA_SENDER_ID=your_sender_id

# SendGrid Email
SENDGRID_API_KEY=your_api_key

# Paystack Payments
PAYSTACK_PUBLIC_KEY=pk_test_xxx
PAYSTACK_SECRET_KEY=sk_test_xxx
```

### Production Deployment

For production deployment:

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false` in `.env`
3. Configure your actual domain
4. Set up wildcard DNS for subdomains
5. Install SSL certificates
6. Run optimization commands:
```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Troubleshooting

**Issue**: Can't access tenant subdomain
**Solution**: Check hosts file or DNS configuration

**Issue**: Frontend not loading
**Solution**: Run `npm run build` and clear browser cache

**Issue**: Database errors
**Solution**: Run `php artisan migrate:fresh` (WARNING: deletes all data)

**Issue**: Permission errors
**Solution**: Set proper permissions:
```bash
chmod -R 775 storage bootstrap/cache
```

### Next Steps

- Read `README_SETUP.md` for detailed setup instructions
- Read `PROJECT_DOCUMENTATION.md` for complete technical documentation
- Configure third-party API integrations
- Customize the UI to match your branding
- Add more tenants as needed

### Support

For issues and questions, refer to the documentation files included in the project.

### File Structure

```
house-management/
├── app/
│   ├── Http/Controllers/     # Backend controllers
│   └── Models/                # Database models
├── database/
│   └── migrations/            # Database migrations
├── resources/
│   └── js/
│       └── Pages/             # Vue 3 pages
├── routes/
│   ├── tenant.php             # Tenant routes
│   └── web.php                # Central routes
├── README_SETUP.md            # Detailed setup guide
├── PROJECT_DOCUMENTATION.md   # Complete technical docs
└── QUICKSTART.md              # This file
```

### Key Features

- Multi-tenant architecture with subdomain isolation
- Role-based authentication (Admin/Renter)
- Apartment and renter management
- SMS integration (TalkSasa)
- Email integration (SendGrid)
- Payment processing (Paystack)
- Dashboard with analytics
- Responsive design with Tailwind CSS

Enjoy using the House Management System!
