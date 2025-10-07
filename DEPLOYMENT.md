# House Management System - Deployment Guide

## Server Requirements

### Minimum Requirements
- **OS**: Ubuntu 20.04+ or similar Linux distribution
- **PHP**: 8.3 or higher
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Database**: MySQL 8.0+, PostgreSQL 13+, or SQLite 3
- **Memory**: 2GB RAM minimum, 4GB recommended
- **Storage**: 10GB minimum
- **Node.js**: 22.x for building assets

### PHP Extensions Required
- BCMath
- Ctype
- cURL
- DOM
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PCRE
- PDO
- Tokenizer
- XML
- GD
- Intl

## Production Deployment

### 1. Server Setup

#### Install PHP and Extensions
```bash
sudo apt update
sudo apt install -y software-properties-common
sudo add-apt-repository -y ppa:ondrej/php
sudo apt update
sudo apt install -y php8.3 php8.3-cli php8.3-fpm php8.3-mysql \
    php8.3-pgsql php8.3-sqlite3 php8.3-curl php8.3-mbstring \
    php8.3-xml php8.3-zip php8.3-bcmath php8.3-gd php8.3-intl
```

#### Install Composer
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

#### Install Node.js
```bash
curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
sudo apt install -y nodejs
```

### 2. Database Setup

#### MySQL
```bash
sudo apt install -y mysql-server
sudo mysql_secure_installation
```

Create database:
```sql
CREATE DATABASE house_management;
CREATE USER 'house_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON house_management.* TO 'house_user'@'localhost';
FLUSH PRIVILEGES;
```

### 3. Deploy Application

#### Clone/Upload Project
```bash
cd /var/www
sudo mkdir house-management
sudo chown $USER:$USER house-management
cd house-management
# Upload your project files here
```

#### Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
npm install --legacy-peer-deps
npm run build
```

#### Configure Environment
```bash
cp .env.example .env
nano .env
```

Update `.env`:
```env
APP_NAME="House Management System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=house_management
DB_USERNAME=house_user
DB_PASSWORD=secure_password

# Add your API keys
TALKSASA_API_KEY=your_real_api_key
TALKSASA_SENDER_ID=your_sender_id
SENDGRID_API_KEY=your_real_api_key
PAYSTACK_PUBLIC_KEY=pk_live_xxx
PAYSTACK_SECRET_KEY=sk_live_xxx
```

#### Generate Key and Run Migrations
```bash
php artisan key:generate
php artisan migrate --force
```

#### Optimize Application
```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Set Permissions
```bash
sudo chown -R www-data:www-data /var/www/house-management
sudo chmod -R 775 /var/www/house-management/storage
sudo chmod -R 775 /var/www/house-management/bootstrap/cache
```

### 4. Web Server Configuration

#### Nginx Configuration

Create `/etc/nginx/sites-available/house-management`:

```nginx
server {
    listen 80;
    server_name yourdomain.com *.yourdomain.com;
    root /var/www/house-management/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/house-management /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

#### Apache Configuration

Create `/etc/apache2/sites-available/house-management.conf`:

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias *.yourdomain.com
    DocumentRoot /var/www/house-management/public

    <Directory /var/www/house-management/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/house-management-error.log
    CustomLog ${APACHE_LOG_DIR}/house-management-access.log combined
</VirtualHost>
```

Enable site and modules:
```bash
sudo a2enmod rewrite
sudo a2ensite house-management
sudo systemctl restart apache2
```

### 5. SSL Configuration

#### Install Certbot
```bash
sudo apt install -y certbot python3-certbot-nginx
# OR for Apache:
# sudo apt install -y certbot python3-certbot-apache
```

#### Obtain Wildcard Certificate
```bash
sudo certbot certonly --manual --preferred-challenges dns \
    -d yourdomain.com -d *.yourdomain.com
```

Follow the instructions to add DNS TXT records.

#### Configure SSL in Nginx

Update `/etc/nginx/sites-available/house-management`:

```nginx
server {
    listen 443 ssl http2;
    server_name yourdomain.com *.yourdomain.com;
    root /var/www/house-management/public;

    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # ... rest of configuration
}

server {
    listen 80;
    server_name yourdomain.com *.yourdomain.com;
    return 301 https://$host$request_uri;
}
```

### 6. DNS Configuration

Configure wildcard subdomain in your DNS provider:

```
Type    Name    Value               TTL
A       @       your_server_ip      3600
A       *       your_server_ip      3600
```

### 7. Create First Tenant

```bash
php artisan tinker
```

```php
$tenant = \App\Models\Tenant::create(['id' => 'demo']);
$tenant->domains()->create(['domain' => 'demo.yourdomain.com']);
exit
```

### 8. Configure Paystack Webhook

In your Paystack dashboard:
1. Go to Settings > Webhooks
2. Add webhook URL: `https://demo.yourdomain.com/payments/webhook`
3. Select events: `charge.success`

### 9. Monitoring and Maintenance

#### Set Up Cron Jobs (if using queue)
```bash
crontab -e
```

Add:
```
* * * * * cd /var/www/house-management && php artisan schedule:run >> /dev/null 2>&1
```

#### Set Up Log Rotation
Create `/etc/logrotate.d/house-management`:

```
/var/www/house-management/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

#### Monitor Logs
```bash
tail -f /var/www/house-management/storage/logs/laravel.log
```

### 10. Backup Strategy

#### Database Backup
```bash
#!/bin/bash
# /usr/local/bin/backup-database.sh
mysqldump -u house_user -p'secure_password' house_management > \
    /backups/house-management-$(date +%Y%m%d).sql
# Keep only last 30 days
find /backups -name "house-management-*.sql" -mtime +30 -delete
```

#### Full Backup
```bash
#!/bin/bash
# /usr/local/bin/backup-full.sh
tar -czf /backups/house-management-full-$(date +%Y%m%d).tar.gz \
    /var/www/house-management \
    --exclude='node_modules' \
    --exclude='vendor'
find /backups -name "house-management-full-*.tar.gz" -mtime +7 -delete
```

Schedule in crontab:
```
0 2 * * * /usr/local/bin/backup-database.sh
0 3 * * 0 /usr/local/bin/backup-full.sh
```

## Troubleshooting

### Common Issues

**Issue**: 500 Internal Server Error
**Solution**: 
- Check storage permissions
- Check error logs
- Ensure `.env` is configured correctly

**Issue**: Subdomain not working
**Solution**:
- Verify DNS configuration
- Check web server configuration
- Ensure wildcard is set up correctly

**Issue**: Database connection failed
**Solution**:
- Verify database credentials in `.env`
- Ensure database user has proper permissions
- Check if database service is running

**Issue**: Assets not loading
**Solution**:
- Run `npm run build`
- Check `APP_URL` in `.env`
- Clear browser cache

## Security Checklist

- [ ] Set `APP_DEBUG=false` in production
- [ ] Use strong database passwords
- [ ] Enable HTTPS with valid SSL certificate
- [ ] Configure firewall (UFW or iptables)
- [ ] Keep PHP and dependencies updated
- [ ] Set proper file permissions
- [ ] Enable fail2ban for SSH protection
- [ ] Regular security audits
- [ ] Monitor logs for suspicious activity
- [ ] Keep backups in secure location

## Performance Optimization

1. **Enable OPcache**: Already enabled in PHP 8.3
2. **Use Redis for caching**: Configure in `.env`
3. **Enable Gzip compression**: Configure in web server
4. **Use CDN for static assets**: CloudFlare, etc.
5. **Database indexing**: Already configured in migrations
6. **Queue long-running tasks**: Configure queue workers

## Scaling Considerations

- Use load balancer for multiple app servers
- Separate database server
- Redis for session and cache
- Queue workers on separate servers
- CDN for static assets
- Database read replicas

## Support

For production support and issues, refer to Laravel documentation and community resources.

## Updates

To update the application:

1. Backup database and files
2. Pull latest code
3. Run `composer install --no-dev`
4. Run `npm install && npm run build`
5. Run `php artisan migrate --force`
6. Clear caches: `php artisan optimize:clear`
7. Rebuild caches: `php artisan optimize`
8. Test thoroughly

---

**Important**: Always test deployment in a staging environment before deploying to production.
