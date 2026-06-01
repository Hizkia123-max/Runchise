# 16 — CODEIGNITER 4 PROJECT STRUCTURE
**NexaPOS ERP — SaaS POS + ERP Platform**

---

## Table of Contents
1. [Core Directory Architecture Layout](#1-core-directory-architecture-layout)
2. [Module Domain Directory Mapping Example](#2-module-domain-directory-mapping-example)
3. [Core Configurations Mapping](#3-core-configurations-mapping)

---

## 1. Core Directory Architecture Layout

The directory structure of NexaPOS ERP is optimized for high-volume enterprise operations. We avoid mixing code logic by grouping functionality into a modular architecture inside the `app/Modules/` workspace directory.

```
/
├── app/                        # Application Core Root Namespace
│   ├── Config/                 # Global configurations (App, Autoload, Database)
│   │   ├── Autoload.php        # Namespace registration for Modules
│   │   ├── Database.php        # Database configuration & drivers
│   │   ├── Filters.php         # Security & routing middleware definitions
│   │   └── Routes.php          # REST API & Web Page route maps
│   │
│   ├── Controllers/            # Base fallback global Controllers
│   ├── Database/               # Global Schema and Database operations
│   │   ├── Migrations/         # Dynamic table generation migrations
│   │   └── Seeds/              # Testing and configuration data seeds
│   │
│   ├── Filters/                # Global Middleware Interceptors
│   │   ├── JWTAuthFilter.php   # Token verification API gatekeeper
│   │   └── TenantFilter.php    # Active tenant resolver
│   │
│   ├── Helpers/                # Custom utility functions (audit, response, formats)
│   ├── Libraries/              # Custom integrations (Midtrans, Xendit, SMS Gateway)
│   ├── Models/                 # Custom base Models
│   │   └── BaseTenantModel.php # Implements database tenant filter scoping
│   │
│   ├── Modules/                # Isolated Domain Workspaces (Modules)
│   │   ├── Authentication/     # Authentication & Authorization Module
│   │   ├── POS/                # Cashier & POS Sales Module
│   │   ├── Inventory/          # Stocks, Warehousing, and Opname Module
│   │   ├── Purchasing/         # Purchase Order & Procurement Module
│   │   ├── Financials/         # Accounting, Ledger, and Tax Module
│   │   ├── HR/                 # Shifts, Attendance, and Payroll Module
│   │   └── CRM/                # Loyalty, Membership, and Marketing Module
│   │
│   ├── Services/               # Shared system-wide Services
│   └── Views/                  # Shared system UI layouts, errors, templates
│
├── public/                     # Document root containing Nginx index.php entry
│   ├── index.php               # Frontend controller bootstrapper
│   ├── assets/                 # Compiled assets (CSS, JS, Fonts, Images)
│   └── uploads/                # Local temp file storage
│
├── tests/                      # Testing workspace directory
├── writable/                   # Cache, logs, and temporary engine files
├── composer.json               # Package configurations and PHP requirements
├── docker-compose.yml          # Container configuration setups
└── spark                       # CodeIgniter 4 CLI command controller utility
```

---

## 2. Module Domain Directory Mapping Example

Each domain inside `app/Modules/` is self-contained. It packages its own controllers, models, views, services, validation configurations, and custom tests.

Below is the directory map of the **POS** Module as an example:

```
app/Modules/POS/
├── Config/
│   └── Routes.php              # Module-specific API and Web route mappings
├── Controllers/
│   ├── Web/
│   │   └── POSViewController.php# Renders UI POS layouts and panels
│   └── API/
│       └── POSController.php   # Handles checkout API payload submissions
├── Models/
│   ├── TransactionModel.php    # DB logic for transactions
│   └── TransactionItemModel.php# DB logic for transaction lines
├── Views/
│   ├── terminal.php            # Screen layout matching POS wireframe specs
│   └── receipt_template.php    # ESC/POS printer formatting engine view
├── Services/
│   ├── CheckoutService.php     # Logic for inventory checks and accounting entries
│   └── QRISService.php         # Generates dynamic payment gateway codes
└── Tests/
    ├── POSUnitTest.php         # Local isolated tests
    └── POSApiTest.php          # End-to-end interface route tests
```

---

## 3. Core Configurations Mapping

To enable this modular architecture, specific configurations are modified inside the CodeIgniter 4 config directory files.

### 3.1 Namespace Registration (`app/Config/Autoload.php`)
This directs CodeIgniter to locate the custom module namespaces.

```php
public $psr4 = [
    APP_NAMESPACE => APPPATH, // The default 'App' directory
    'Config'      => APPPATH . 'Config',
    'App\Modules' => APPPATH . 'Modules', // Register Modules directory
];
```

### 3.2 Global Route Dispatcher (`app/Config/Routes.php`)
This loops through each module folder and loads their internal routing configurations.

```php
// Load global routing configurations
$routes->get('/', 'Home::index');

// Dynamically discover and load Routes.php files inside app/Modules
if (is_dir(APPPATH . 'Modules')) {
    $modulesDir = new \DirectoryIterator(APPPATH . 'Modules');
    foreach ($modulesDir as $fileInfo) {
        if ($fileInfo->isDir() && !$fileInfo->isDot()) {
            $routesPath = $fileInfo->getPathname() . '/Config/Routes.php';
            if (file_exists($routesPath)) {
                require $routesPath;
            }
        }
    }
}
```

---

*Document maintained by: Tech Architecture Team | Last updated: June 2026 | Version: 1.0*
