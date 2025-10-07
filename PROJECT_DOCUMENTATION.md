# House Management System - Complete Documentation

## Project Overview

A comprehensive multi-tenant SaaS platform for property management, enabling landlords and agents to manage their rental properties, tenants, communications, and payments through isolated tenant environments.

## Technology Stack

### Backend
- **Laravel 12**: PHP framework with modern features
- **Stancl/Tenancy 3.9**: Multi-tenancy package for database isolation
- **PHP 8.3**: Latest PHP version with performance improvements

### Frontend
- **Vue 3**: Progressive JavaScript framework
- **Inertia.js**: Modern monolith approach (no separate API)
- **Tailwind CSS**: Utility-first CSS framework
- **Vite 7**: Fast build tool and dev server

### Third-Party Services
- **TalkSasa**: Bulk SMS service
- **Twilio SendGrid**: Email delivery service
- **Paystack**: Payment processing platform

## Architecture

### Multi-Tenancy Model

The system uses **database-per-tenant** architecture:

1. **Central Database**: Stores tenant information and domains
2. **Tenant Databases**: Each tenant has isolated database for their data

**Benefits**:
- Complete data isolation
- Better security
- Independent scaling
- Easier backup and restore per tenant

### Authentication Flow

1. User accesses subdomain (e.g., `agent1.domain.com`)
2. Tenancy middleware identifies tenant from subdomain
3. Database connection switches to tenant database
4. Laravel Breeze handles authentication
5. Role-based access control determines permissions

## Database Schema

### Central Database

#### tenants
- `id` (string, primary): Unique tenant identifier
- `created_at`, `updated_at`: Timestamps

#### domains
- `id` (integer, primary)
- `domain` (string): Subdomain or full domain
- `tenant_id` (foreign key): References tenants.id
- `created_at`, `updated_at`: Timestamps

### Tenant Database

#### users
- `id` (integer, primary)
- `name` (string): User full name
- `email` (string, unique): Email address
- `role` (string): 'admin' or 'renter'
- `password` (hashed): User password
- `email_verified_at` (timestamp, nullable)
- `remember_token` (string, nullable)
- `created_at`, `updated_at`: Timestamps

#### apartments
- `id` (integer, primary)
- `name` (string): Apartment name
- `location` (string): Physical location
- `number_of_units` (integer): Total units
- `description` (text, nullable): Additional details
- `created_at`, `updated_at`: Timestamps

#### renters
- `id` (integer, primary)
- `full_name` (string): Renter full name
- `email` (string, unique): Email address
- `phone_number` (string): Contact number
- `id_number` (string, unique): National ID or passport
- `apartment_id` (foreign key): References apartments.id
- `house_number` (string): Unit/house number
- `move_in_date` (date): Move-in date
- `user_id` (foreign key, nullable): References users.id
- `created_at`, `updated_at`: Timestamps

#### sms_logs
- `id` (integer, primary)
- `recipient_name` (string): Recipient name
- `phone_number` (string): Recipient phone
- `message` (text): SMS content
- `status` (string): 'pending', 'sent', 'failed'
- `sent_at` (timestamp, nullable): Send timestamp
- `created_at`, `updated_at`: Timestamps

#### email_logs
- `id` (integer, primary)
- `subject` (string): Email subject
- `recipients` (text): JSON array of email addresses
- `body` (text): Email content (HTML)
- `status` (string): 'pending', 'sent', 'failed'
- `sent_at` (timestamp, nullable): Send timestamp
- `created_at`, `updated_at`: Timestamps

#### payments
- `id` (integer, primary)
- `renter_id` (foreign key): References renters.id
- `renter_name` (string): Renter name (denormalized)
- `amount` (decimal 10,2): Payment amount
- `reference` (string, unique): Payment reference
- `status` (string): 'pending', 'success', 'failed'
- `paid_at` (timestamp, nullable): Payment timestamp
- `created_at`, `updated_at`: Timestamps

## Controllers

### DashboardController
- **index()**: Display dashboard with statistics and recent payments

### ApartmentController (Resource)
- **index()**: List all apartments with pagination
- **create()**: Show create form
- **store()**: Save new apartment
- **show()**: Display apartment details with renters
- **edit()**: Show edit form
- **update()**: Update apartment
- **destroy()**: Delete apartment

### RenterController (Resource)
- **index()**: List renters with search/filter
- **create()**: Show create form
- **store()**: Save new renter
- **show()**: Display renter details with payments
- **edit()**: Show edit form
- **update()**: Update renter
- **destroy()**: Delete renter

### SmsController
- **index()**: List SMS logs
- **create()**: Show SMS compose form
- **store()**: Send SMS to selected renters
- **sendSms()**: Private method for TalkSasa API integration

### EmailController
- **index()**: List email logs
- **create()**: Show email compose form
- **store()**: Send email to selected renters
- **sendEmail()**: Private method for SendGrid API integration

### PaymentController (Resource)
- **index()**: List payments with filtering
- **create()**: Show create form
- **store()**: Create payment record
- **show()**: Display payment details
- **initiate()**: Initialize Paystack payment
- **verify()**: Verify Paystack payment
- **webhook()**: Handle Paystack webhooks
- **export()**: Export payments to CSV
- **destroy()**: Delete payment

## Routes Structure

All routes are defined in `routes/tenant.php` and protected by:
- `InitializeTenancyByDomain` middleware
- `PreventAccessFromCentralDomains` middleware
- `auth` middleware (except webhook)

### Route List
```
GET  /dashboard              - Dashboard
GET  /apartments             - List apartments
GET  /apartments/create      - Create apartment form
POST /apartments             - Store apartment
GET  /apartments/{id}        - Show apartment
GET  /apartments/{id}/edit   - Edit apartment form
PUT  /apartments/{id}        - Update apartment
DELETE /apartments/{id}      - Delete apartment

GET  /renters                - List renters
GET  /renters/create         - Create renter form
POST /renters                - Store renter
GET  /renters/{id}           - Show renter
GET  /renters/{id}/edit      - Edit renter form
PUT  /renters/{id}           - Update renter
DELETE /renters/{id}         - Delete renter

GET  /sms                    - List SMS logs
GET  /sms/create             - Compose SMS form
POST /sms                    - Send SMS

GET  /emails                 - List email logs
GET  /emails/create          - Compose email form
POST /emails                 - Send email

GET  /payments               - List payments
GET  /payments/create        - Create payment form
POST /payments               - Store payment
GET  /payments/{id}          - Show payment
POST /payments/initiate      - Initialize Paystack payment
GET  /payments/verify        - Verify Paystack payment
GET  /payments/export        - Export payments CSV
DELETE /payments/{id}        - Delete payment
POST /payments/webhook       - Paystack webhook (no auth)
```

## Frontend Components

### Layout Components
- **AuthenticatedLayout.vue**: Main layout with navigation
- **GuestLayout.vue**: Layout for login/register pages

### Page Components

#### Dashboard.vue
- Statistics cards (apartments, renters, payments, SMS, emails)
- Recent payments table

#### Apartments/
- **Index.vue**: Apartments list with pagination
- **Create.vue**: Create apartment form
- **Edit.vue**: Edit apartment form (to be created)
- **Show.vue**: Apartment details (to be created)

#### Renters/
- **Index.vue**: Renters list with search
- **Create.vue**: Create renter form
- **Edit.vue**: Edit renter form (to be created)
- **Show.vue**: Renter details with payments (to be created)

#### Sms/
- **Index.vue**: SMS logs list (to be created)
- **Create.vue**: Compose SMS form (to be created)

#### Emails/
- **Index.vue**: Email logs list (to be created)
- **Create.vue**: Compose email form (to be created)

#### Payments/
- **Index.vue**: Payments list with filters (to be created)
- **Create.vue**: Create payment form (to be created)
- **Show.vue**: Payment details (to be created)

## API Integrations

### TalkSasa SMS API

**Endpoint**: `https://api.talksasa.com/v1/sms/send`

**Request**:
```json
{
  "api_key": "your_api_key",
  "sender_id": "your_sender_id",
  "phone": "+254712345678",
  "message": "Your message here"
}
```

**Response**: Success (200) or Error

### Twilio SendGrid API

**Endpoint**: `https://api.sendgrid.com/v3/mail/send`

**Headers**:
```
Authorization: Bearer {api_key}
Content-Type: application/json
```

**Request**:
```json
{
  "personalizations": [
    {"to": [{"email": "recipient@example.com"}]}
  ],
  "from": {
    "email": "sender@example.com",
    "name": "Sender Name"
  },
  "subject": "Email Subject",
  "content": [
    {
      "type": "text/html",
      "value": "<p>Email body</p>"
    }
  ]
}
```

### Paystack API

#### Initialize Transaction
**Endpoint**: `https://api.paystack.co/transaction/initialize`

**Headers**:
```
Authorization: Bearer {secret_key}
Content-Type: application/json
```

**Request**:
```json
{
  "email": "customer@example.com",
  "amount": 50000,
  "reference": "REF-ABC123",
  "callback_url": "https://yourdomain.com/payments/verify"
}
```

**Response**:
```json
{
  "status": true,
  "data": {
    "authorization_url": "https://checkout.paystack.com/...",
    "access_code": "...",
    "reference": "REF-ABC123"
  }
}
```

#### Verify Transaction
**Endpoint**: `https://api.paystack.co/transaction/verify/{reference}`

**Headers**:
```
Authorization: Bearer {secret_key}
```

**Response**:
```json
{
  "status": true,
  "data": {
    "status": "success",
    "reference": "REF-ABC123",
    "amount": 50000
  }
}
```

#### Webhook
**URL**: `https://yourdomain.com/payments/webhook`

**Headers**:
```
x-paystack-signature: {hmac_sha512_signature}
```

**Payload**:
```json
{
  "event": "charge.success",
  "data": {
    "reference": "REF-ABC123",
    "status": "success",
    "amount": 50000
  }
}
```

## Configuration Files

### config/tenancy.php
Multi-tenancy configuration including:
- Tenant model
- Database connection
- Middleware
- Features enabled

### .env Variables
```env
# App
APP_NAME="House Management System"
APP_URL=http://localhost

# Database
DB_CONNECTION=sqlite

# TalkSasa
TALKSASA_API_KEY=
TALKSASA_SENDER_ID=

# SendGrid
SENDGRID_API_KEY=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME=

# Paystack
PAYSTACK_PUBLIC_KEY=
PAYSTACK_SECRET_KEY=
```

## Development Workflow

1. **Backend Changes**: Edit controllers, models, migrations
2. **Frontend Changes**: Edit Vue components in `resources/js/Pages/`
3. **Styling**: Use Tailwind CSS utility classes
4. **Testing**: Run `php artisan test`
5. **Build**: Run `npm run build` for production

## Deployment Checklist

- [ ] Set up production server (Apache/Nginx)
- [ ] Configure wildcard subdomain DNS
- [ ] Install PHP 8.3, Composer, Node.js
- [ ] Clone repository
- [ ] Install dependencies
- [ ] Configure `.env` with production values
- [ ] Generate application key
- [ ] Run migrations
- [ ] Build frontend assets
- [ ] Set file permissions (storage, bootstrap/cache)
- [ ] Configure SSL certificates
- [ ] Set up cron jobs (if needed)
- [ ] Configure queue workers (if needed)
- [ ] Set up monitoring and logging
- [ ] Configure backups

## Security Best Practices

1. **Environment Variables**: Never commit `.env` file
2. **API Keys**: Store securely in environment variables
3. **CSRF Protection**: Enabled by default in Laravel
4. **SQL Injection**: Use Eloquent ORM and parameter binding
5. **XSS Protection**: Vue.js escapes output by default
6. **Authentication**: Use Laravel Breeze with secure password hashing
7. **Authorization**: Implement role-based access control
8. **HTTPS**: Always use SSL in production
9. **Webhook Signature**: Verify Paystack webhook signatures

## Performance Optimization

1. **Database Indexing**: Add indexes on frequently queried columns
2. **Eager Loading**: Use `with()` to prevent N+1 queries
3. **Caching**: Implement Redis/Memcached for sessions and cache
4. **Queue Jobs**: Move SMS/email sending to background jobs
5. **Asset Optimization**: Minify and compress frontend assets
6. **CDN**: Serve static assets from CDN
7. **Database Optimization**: Regular maintenance and query optimization

## Troubleshooting

### Common Issues

**Issue**: Tenant not found
**Solution**: Ensure domain is created for tenant

**Issue**: SMS not sending
**Solution**: Check TalkSasa API credentials and balance

**Issue**: Email not sending
**Solution**: Verify SendGrid API key and sender verification

**Issue**: Payment webhook not working
**Solution**: Ensure webhook URL is publicly accessible and signature verification is correct

**Issue**: Frontend not updating
**Solution**: Run `npm run build` and clear browser cache

## Future Enhancements

- Multi-user access per tenant with granular permissions
- Automated rent reminders via SMS/Email
- Lease agreement management
- Maintenance request tracking
- Tenant portal with self-service features
- Advanced reporting and analytics
- Mobile app (React Native/Flutter)
- Integration with more payment gateways
- Document storage (contracts, IDs)
- Automated late payment penalties

## Support and Maintenance

For ongoing support and maintenance:
1. Monitor error logs regularly
2. Keep dependencies updated
3. Backup databases regularly
4. Monitor API usage and costs
5. Review security advisories
6. Test new features in staging environment

## License

Proprietary software. All rights reserved.
