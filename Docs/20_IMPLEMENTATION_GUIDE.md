# 20 — IMPLEMENTATION GUIDE
**NexaPOS ERP — SaaS POS + ERP Platform**

---

## Table of Contents
1. [Developer Onboarding & Setup Sequence](#1-developer-onboarding--setup-sequence)
2. [Step-by-Step Backend Architecture Initialization](#2-step-by-step-backend-architecture-initialization)
3. [Step-by-Step Frontend UI & Layout Integration](#3-step-by-step-frontend-ui--layout-integration)
4. [Step-by-Step Database Migration & Seeding Sequence](#4-step-by-step-database-migration--seeding-sequence)
5. [Step-by-Step Integration & Payment Setup](#5-step-by-step-integration--payment-setup)
6. [Pre-Production Readiness & Security Review Checklist](#6-pre-production-readiness--security-review-checklist)

---

## 1. Developer Onboarding & Setup Sequence

To onboard a CodeIgniter 4 developer to NexaPOS ERP:

1. **System Prerequisite Checks:** Ensure local environment runs PHP 8.3+, Composer 2.7+, Docker Engine, and MySQL 8.0 client libraries.
2. **Repository Checkout:** Clone the main application repository workspace code.
3. **Environment Setup:** Copy the system configuration templates to create local environmental keys:
   ```bash
   cp env.example .env
   ```
4. **Dependency Installation:** Build and pull local Composer libraries and framework extensions:
   ```bash
   composer install
   ```
5. **Start Docker Environment:** Spin up local Docker containers for MySQL, Redis, and Web Services:
   ```bash
   docker-compose up -d
   ```

---

## 2. Step-by-Step Backend Architecture Initialization

Follow this sequence to initialize the CodeIgniter 4 backend components:

### Step 2.1: Custom Autoloading Setup
Configure `app/Config/Autoload.php` to register the custom modular namespace structure:
```php
public $psr4 = [
    APP_NAMESPACE => APPPATH,
    'Config'      => APPPATH . 'Config',
    'App\Modules' => APPPATH . 'Modules', // Map Modules workspace
];
```

### Step 2.2: Tenant Scoping Interceptor (Base Model)
Create `app/Models/BaseTenantModel.php` to automate tenant isolation:
```php
namespace App\Models;

use CodeIgniter\Model;

class BaseTenantModel extends Model {
    protected $tenantId;

    public function __construct() {
        parent::__construct();
        // Resolve active tenant ID from request context
        $this->tenantId = service('tenant')->getId();
    }

    protected function initializeTenantScope() {
        // Enforce tenant ID filter on queries
        $this->builder()->where('tenant_id', $this->tenantId);
    }
}
```

### Step 2.3: Tenant Domain Resolver (Filter)
Create `app/Filters/TenantFilter.php` to extract and bind the active tenant ID:
```php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class TenantFilter implements FilterInterface {
    public function before(RequestInterface $request, $arguments = null) {
        $host = $request->getUri()->getHost();
        $subdomain = explode('.', $host)[0];

        // Resolve Tenant record matching subdomain from DB
        $tenantModel = new \App\Models\TenantModel();
        $tenant = $tenantModel->where('subdomain', $subdomain)->first();

        if (!$tenant) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Tenant not found');
        }

        // Bind active Tenant ID to application state
        service('tenant')->setId($tenant['id']);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
```

---

## 3. Step-by-Step Frontend UI & Layout Integration

1. **Bootstrap 5 Compilation:** Bundle customized Bootstrap 5 and jQuery frameworks into `public/assets/css/theme.css` and `public/assets/js/app.js`.
2. **Core Layout Setup:** Create the base workspace shell `app/Views/layouts/dashboard.php` containing:
   - Sidebar navigation.
   - Header with active branch switcher and profile info.
   - Main card container.
3. **DataTables Configuration:** Configure standard Ajax query templates in `public/assets/js/datatables-config.js` to process multi-tenant pagination parameters.

---

## 4. Step-by-Step Database Migration & Seeding Sequence

1. **Run Migrations:** Execute the schema build scripts using the CI4 command runner:
   ```bash
   php spark migrate -all
   ```
2. **Execute Seeding:** Insert global SaaS plans and initial system roles:
   ```bash
   php spark db:seed SaaSPlansSeeder
   php spark db:seed SystemRolesSeeder
   ```
3. **Seed Mock Tenant:** Generate a test tenant workspace database partition for local testing:
   ```bash
   php spark db:seed DevTenantSeeder
   ```

---

## 5. Step-by-Step Integration & Payment Setup

1. **Integrate SDKs:** Add payment gateway integration classes in `app/Libraries/PaymentGateway.php`.
2. **Configure Webhook Endpoints:** Establish the listener endpoint for payment updates:
   - Route path: `POST /api/v1/saas/billing/webhook`.
3. **Secure Webhook Verification:** Verify webhook requests using the payment gateway signature header before updating payment status in the database.
4. **Implement QRIS Code Generation:** Wrap the payment gateway SDK methods to generate dynamic QRIS code images on POS client screens.

---

## 6. Pre-Production Readiness & Security Review Checklist

Before deploying the platform to production nodes, developers must complete this checklist:

- [ ] **Environment Verification:** Verify that `CI_ENVIRONMENT` is set to `production` in the `.env` file.
- [ ] **Encryption Key Rotation:** Confirm that the master key (`encryption.key`) is generated using `php spark key:generate` and is not using default keys.
- [ ] **Database Safeguards:** Verify that database users do not have global root privileges, and SSL connection enforcement is active.
- [ ] **SSL Enforcement:** Confirm that Nginx forces SSL redirects on all routes.
- [ ] **Asset Minification:** Ensure JavaScript and CSS assets are minified and packaged for production delivery.
- [ ] **Queue Supervisor Monitoring:** Verify that Supervisor is active and monitoring workers processing queue jobs in the background.

---

*Document maintained by: Tech Lead | Last updated: June 2026 | Version: 1.0*
