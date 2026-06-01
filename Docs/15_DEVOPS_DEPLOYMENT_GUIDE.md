# 15 — DEVOPS DEPLOYMENT GUIDE
**NexaPOS ERP — SaaS POS + ERP Platform**

---

## Table of Contents
1. [Introduction & Infrastructure Standard](#1-introduction--infrastructure-standard)
2. [Local Setup with Docker Compose](#2-local-setup-with-docker-compose)
3. [Nginx Configuration File (`nginx.conf`)](#3-nginx-configuration-file-nginxconf)
4. [Production Environment Configuration (`.env`)](#4-production-environment-configuration-env)
5. [Process Management with Supervisor](#5-process-management-with-supervisor)
6. [CI/CD Deployment Pipeline (GitHub Actions)](#6-cicd-deployment-pipeline-github-actions)

---

## 1. Introduction & Infrastructure Standard

NexaPOS ERP relies on a containerized infrastructure architecture. High-availability clusters utilize isolated docker containers for the web server, application nodes, cache layer, and database engines.

---

## 2. Local Setup with Docker Compose

Below is the complete, production-ready `docker-compose.yml` structure for spinning up the development environment.

```yaml
version: '3.8'

services:
  # Web Server Proxy
  webserver:
    image: nginx:1.26-alpine
    container_name: nexapos_webserver
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app

  # CodeIgniter 4 Application Node
  app:
    build:
      context: .
      dockerfile: docker/Dockerfile
    container_name: nexapos_app
    volumes:
      - ./:/var/www/html
    environment:
      - CI_ENVIRONMENT=development
    depends_on:
      - db
      - redis

  # MySQL Database Server
  db:
    image: mysql:8.0
    container_name: nexapos_db
    ports:
      - "3306:3306"
    environment:
      - MYSQL_DATABASE=nexapos_db
      - MYSQL_ROOT_PASSWORD=root_secret
    volumes:
      - nexapos_mysql_data:/var/lib/mysql

  # Caching and Queue Store
  redis:
    image: redis:7.2-alpine
    container_name: nexapos_redis
    ports:
      - "6379:6379"
    volumes:
      - nexapos_redis_data:/data

volumes:
  nexapos_mysql_data:
  nexapos_redis_data:
```

---

## 3. Nginx Configuration File (`nginx.conf`)

This configuration routes traffic to the PHP-FPM container and enforces security parameters.

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name *.nexapos.id;

    root /var/www/html/public;
    index index.php index.html index.htm;

    charset utf-8;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    # Pass PHP scripts to FastCGI server
    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Block access to hidden files (.env, .git)
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 4. Production Environment Configuration (`.env`)

This file configures PHP, database connections, Redis caching, and email gateways.

```ini
#--------------------------------------------------------------------
# SYSTEM ENVIRONMENT CONFIGURATION
#--------------------------------------------------------------------
CI_ENVIRONMENT = production
app.baseURL = 'https://nexapos.id/'
app.forceGlobalSecureRequests = true

#--------------------------------------------------------------------
# DATABASE SETTINGS
#--------------------------------------------------------------------
database.default.hostname = nexapos-db-primary.vpc.internal
database.default.database = nexapos_prod
database.default.username = app_user
database.default.password = DB_Secure_Password_123
database.default.DBDriver = MySQLi
database.default.DBPrefix = ""
database.default.port = 3306

#--------------------------------------------------------------------
# REDIS SETTINGS
#--------------------------------------------------------------------
redis.host = nexapos-redis.vpc.internal
redis.password = Redis_Secure_Password_123
redis.port = 6379
redis.database = 0

#--------------------------------------------------------------------
# SECURITY ENCRYPTION KEYS
#--------------------------------------------------------------------
encryption.key = hex2bin:f1e4a3b8d9c2e5b7a1f9e8d7c6b5a4f3...
encryption.driver = OpenSSL
encryption.cipher = AES-256-GCM

#--------------------------------------------------------------------
# EMAIL GATEWAY CONFIGURATION
#--------------------------------------------------------------------
email.protocol = smtp
email.SMTPHost = smtp.sendgrid.net
email.SMTPUser = apikey
email.SMTPPass = SG.SendGrid_API_Key_Goes_Here...
email.SMTPPort = 587
email.SMTPCrypto = tls

#--------------------------------------------------------------------
# THIRD PARTY INTEGRATION KEYS
#--------------------------------------------------------------------
MIDTRANS_SERVER_KEY = VT-server-MidtransKeyExample...
XENDIT_SECRET_KEY = xnd_development_KeyExample...
FONNTE_WA_TOKEN = FonnteTokenExample...
```

---

## 5. Process Management with Supervisor

To process Redis queue transactions (e.g. sending emails, posting accounting metrics asynchronously), Supervisor manages background runner processes.

### Supervisor Config File (`/etc/supervisor/conf.d/nexapos-worker.conf`)
```ini
[program:nexapos-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/spark queue:work
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/www/html/writable/logs/queue_worker.log
stopwaitsecs=3600
```

---

## 6. CI/CD Deployment Pipeline (GitHub Actions)

This pipeline builds, tests, and deploys code adjustments to production nodes automatically.

```yaml
name: Deploy NexaPOS ERP to Production

on:
  push:
    branches:
      - main

jobs:
  run-tests:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout Code
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
          extensions: mbstring, xml, intl, gd, zip, mysqlnd, redis

      - name: Install Dependencies
        run: composer install --no-progress --prefer-dist --optimize-autoloader

      - name: Execute PHPUnit Tests
        run: ./vendor/bin/phpunit --configuration phpunit.xml

  deploy:
    needs: run-tests
    runs-on: ubuntu-latest
    steps:
      - name: Deploy Code via SSH
        uses: appleboy/ssh-action@v1.0.3
        with:
          host: production.nexapos.id
          username: ubuntu
          key: ${{ secrets.SSH_PRIVATE_KEY }}
          script: |
            cd /var/www/html
            git pull origin main
            composer install --no-dev --optimize-autoloader
            php spark migrate
            php spark cache:clear
            sudo supervisorctl restart nexapos-worker:*
```

---

*Document maintained by: DevOps Lead | Last updated: June 2026 | Version: 1.0*
