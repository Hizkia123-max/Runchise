# 09 — DATABASE DESIGN
**NexaPOS ERP — SaaS POS + ERP Platform**

---

## Table of Contents
1. [Entity Relationship Diagram Description](#1-entity-relationship-diagram-description)
2. [Database Architecture & Multi-Tenancy Design](#2-database-architecture--multi-tenancy-design)
3. [Table Definitions by Module Domain](#3-table-definitions-by-module-domain)
   - [Core SaaS Billing Module](#core-saas-billing-module)
   - [Core Authentication & Tenant Profiles](#core-authentication--tenant-profiles)
   - [Core POS & Branch System](#core-pos--branch-system)
   - [Core Inventory & Purchasing System](#core-inventory--purchasing-system)
   - [Core Accounting System](#core-accounting-system)
   - [Core HR & Payroll System](#core-hr--payroll-system)
4. [Global Constraints, Indexes, and Optimizations](#4-global-constraints-indexes-and-optimizations)

---

## 1. Entity Relationship Diagram Description

NexaPOS ERP uses a relational MySQL database. The core tables connect to one another through explicit foreign keys. A `tenant_id` column is added to all tenant-specific tables to enforce partition isolation.

```
[Tenants] 1 ─────── 1..* [Branches] 1 ────── 1..* [POS Sessions]
    │                        │                     │
    ├─────── 1..* [Users]    ├────── 1..* [Stock]  └────── 1..* [Transactions] ── 1..* [Tx Items]
    │                        │                                   │
    ├─────── 1..* [Products] └────── 1..* [Transfers]            ├────── 1..* [Journal Entries]
    │                                                            │
    └─────── 1..* [Customers] ───────────────────────────────────┘
```

---

## 2. Database Architecture & Multi-Tenancy Design

- **Multi-Tenancy Model:** Shared database, logical schema isolation via `tenant_id` foreign key.
- **Auto-Filtering:** Every query executed by CI4 model classes must append `WHERE tenant_id = <active_tenant_id>` to prevent data leaks.
- **Key Generation:** Primary keys use `BIGINT UNSIGNED AUTO_INCREMENT` or `VARCHAR(36)` (UUID) for global uniqueness where appropriate (e.g. REST API resources).

---

## 3. Table Definitions by Module Domain

### Core SaaS Billing Module

#### `saas_plans`
- **Purpose:** Stores definition of billing subscription packages.
- **Columns:**
  - `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
  - `name` (VARCHAR(50), Unique, Not Null)
  - `price_monthly` (DECIMAL(15,2), Not Null)
  - `max_branches` (INT, Not Null)
  - `max_users` (INT, Not Null)
  - `max_monthly_transactions` (INT, Not Null)
  - `has_accounting` (TINYINT(1), Default 0)
  - `has_hr` (TINYINT(1), Default 0)
  - `created_at` (TIMESTAMP)
  - `updated_at` (TIMESTAMP)

#### `saas_subscriptions`
- **Purpose:** Tracks tenant subscription history and billing statuses.
- **Columns:**
  - `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
  - `tenant_id` (BIGINT UNSIGNED, Foreign Key, Index)
  - `plan_id` (BIGINT UNSIGNED, Foreign Key)
  - `status` (ENUM('Trial', 'Active', 'GracePeriod', 'Suspended'), Default 'Trial')
  - `starts_at` (TIMESTAMP, Not Null)
  - `expires_at` (TIMESTAMP, Not Null)
  - `auto_renew` (TINYINT(1), Default 1)
  - `created_at` (TIMESTAMP)
  - `updated_at` (TIMESTAMP)

---

### Core Authentication & Tenant Profiles

#### `tenants`
- **Purpose:** Root business tenant registry record.
- **Columns:**
  - `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
  - `company_name` (VARCHAR(150), Not Null)
  - `subdomain` (VARCHAR(60), Unique, Not Null)
  - `custom_domain` (VARCHAR(100), Unique, Nullable)
  - `status` (ENUM('Pending', 'Active', 'Suspended'), Default 'Active')
  - `created_at` (TIMESTAMP)
  - `updated_at` (TIMESTAMP)

#### `users`
- **Purpose:** User profiles across all roles and tenants.
- **Columns:**
  - `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
  - `tenant_id` (BIGINT UNSIGNED, Foreign Key, Index)
  - `name` (VARCHAR(100), Not Null)
  - `email` (VARCHAR(100), Unique, Not Null)
  - `password_hash` (VARCHAR(255), Not Null)
  - `role` (ENUM('SuperAdmin', 'TenantOwner', 'Manager', 'Cashier', 'InventoryStaff', 'Accountant', 'HRStaff'), Not Null)
  - `phone` (VARCHAR(20), Nullable)
  - `mfa_secret` (VARCHAR(100), Nullable)
  - `mfa_enabled` (TINYINT(1), Default 0)
  - `created_at` (TIMESTAMP)
  - `updated_at` (TIMESTAMP)

---

### Core POS & Branch System

#### `branches`
- **Purpose:** Branches and outlet definitions.
- **Columns:**
  - `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
  - `tenant_id` (BIGINT UNSIGNED, Foreign Key, Index)
  - `name` (VARCHAR(100), Not Null)
  - `address` (TEXT, Nullable)
  - `phone` (VARCHAR(20), Nullable)
  - `created_at` (TIMESTAMP)
  - `updated_at` (TIMESTAMP)

#### `pos_sessions`
- **Purpose:** Register drawer shifts and opening cash totals.
- **Columns:**
  - `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
  - `tenant_id` (BIGINT UNSIGNED, Foreign Key, Index)
  - `branch_id` (BIGINT UNSIGNED, Foreign Key)
  - `user_id` (BIGINT UNSIGNED, Foreign Key)
  - `opening_cash` (DECIMAL(15,2), Not Null)
  - `closing_cash` (DECIMAL(15,2), Nullable)
  - `status` (ENUM('Open', 'Closed'), Default 'Open')
  - `opened_at` (TIMESTAMP, Default Current)
  - `closed_at` (TIMESTAMP, Nullable)

#### `transactions`
- **Purpose:** Store headers of sales checkouts.
- **Columns:**
  - `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
  - `tenant_id` (BIGINT UNSIGNED, Foreign Key, Index)
  - `branch_id` (BIGINT UNSIGNED, Foreign Key)
  - `pos_session_id` (BIGINT UNSIGNED, Foreign Key)
  - `customer_id` (BIGINT UNSIGNED, Nullable, Foreign Key)
  - `invoice_number` (VARCHAR(50), Unique, Not Null)
  - `subtotal` (DECIMAL(15,2), Not Null)
  - `discount_amount` (DECIMAL(15,2), Default 0.00)
  - `tax_amount` (DECIMAL(15,2), Default 0.00)
  - `total` (DECIMAL(15,2), Not Null)
  - `payment_method` (ENUM('Cash', 'QRIS', 'Card', 'Split'), Not Null)
  - `payment_status` (ENUM('Unpaid', 'Paid', 'Refunded'), Default 'Unpaid')
  - `created_at` (TIMESTAMP)
  - `updated_at` (TIMESTAMP)

#### `transaction_items`
- **Purpose:** Line items of sales.
- **Columns:**
  - `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
  - `tenant_id` (BIGINT UNSIGNED, Foreign Key, Index)
  - `transaction_id` (BIGINT UNSIGNED, Foreign Key)
  - `product_id` (BIGINT UNSIGNED, Foreign Key)
  - `quantity` (DECIMAL(10,2), Not Null)
  - `unit_price` (DECIMAL(15,2), Not Null)
  - `discount_amount` (DECIMAL(15,2), Default 0.00)
  - `tax_amount` (DECIMAL(15,2), Default 0.00)
  - `total` (DECIMAL(15,2), Not Null)

---

### Core Inventory & Purchasing System

#### `products`
- **Purpose:** Master product catalog record.
- **Columns:**
  - `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
  - `tenant_id` (BIGINT UNSIGNED, Foreign Key, Index)
  - `sku` (VARCHAR(50), Unique, Not Null)
  - `name` (VARCHAR(150), Not Null)
  - `barcode` (VARCHAR(100), Nullable, Index)
  - `price` (DECIMAL(15,2), Not Null)
  - `cost` (DECIMAL(15,2), Not Null)
  - `reorder_point` (INT, Default 10)
  - `created_at` (TIMESTAMP)
  - `updated_at` (TIMESTAMP)

#### `inventory_stocks`
- **Purpose:** Current stock level balance per warehouse/branch.
- **Columns:**
  - `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
  - `tenant_id` (BIGINT UNSIGNED, Foreign Key, Index)
  - `branch_id` (BIGINT UNSIGNED, Foreign Key)
  - `product_id` (BIGINT UNSIGNED, Foreign Key)
  - `quantity` (DECIMAL(10,2), Default 0.00)
  - `created_at` (TIMESTAMP)
  - `updated_at` (TIMESTAMP)

---

### Core Accounting System

#### `accounting_coa`
- **Purpose:** Chart of Accounts structure definitions.
- **Columns:**
  - `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
  - `tenant_id` (BIGINT UNSIGNED, Foreign Key, Index)
  - `code` (VARCHAR(20), Not Null)
  - `name` (VARCHAR(100), Not Null)
  - `type` (ENUM('Asset', 'Liability', 'Equity', 'Revenue', 'Expense'), Not Null)
  - `parent_id` (BIGINT UNSIGNED, Nullable)
  - `created_at` (TIMESTAMP)

#### `journal_entries`
- **Purpose:** Head records of Double-Entry book adjustments.
- **Columns:**
  - `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
  - `tenant_id` (BIGINT UNSIGNED, Foreign Key, Index)
  - `reference_number` (VARCHAR(50), Unique)
  - `description` (TEXT, Nullable)
  - `posting_date` (DATE, Not Null)
  - `created_at` (TIMESTAMP)

#### `journal_lines`
- **Purpose:** Line rows containing Credits and Debits balance updates.
- **Columns:**
  - `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
  - `tenant_id` (BIGINT UNSIGNED, Foreign Key, Index)
  - `journal_entry_id` (BIGINT UNSIGNED, Foreign Key)
  - `coa_id` (BIGINT UNSIGNED, Foreign Key)
  - `debit` (DECIMAL(15,2), Default 0.00)
  - `credit` (DECIMAL(15,2), Default 0.00)

---

### Core HR & Payroll System

#### `employees`
- **Purpose:** HR database repository records.
- **Columns:**
  - `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
  - `tenant_id` (BIGINT UNSIGNED, Foreign Key, Index)
  - `user_id` (BIGINT UNSIGNED, Nullable, Foreign Key)
  - `employee_code` (VARCHAR(50), Unique, Not Null)
  - `identity_card` (VARCHAR(30), Not Null)
  - `name` (VARCHAR(100), Not Null)
  - `salary` (DECIMAL(15,2), Not Null)
  - `hired_at` (DATE, Not Null)
  - `created_at` (TIMESTAMP)

#### `attendance_logs`
- **Purpose:** Timesheet logs with geo location tracking.
- **Columns:**
  - `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
  - `tenant_id` (BIGINT UNSIGNED, Foreign Key, Index)
  - `employee_id` (BIGINT UNSIGNED, Foreign Key)
  - `clock_in` (TIMESTAMP, Nullable)
  - `clock_out` (TIMESTAMP, Nullable)
  - `gps_latitude_in` (DECIMAL(10,8), Nullable)
  - `gps_longitude_in` (DECIMAL(11,8), Nullable)
  - `gps_latitude_out` (DECIMAL(10,8), Nullable)
  - `gps_longitude_out` (DECIMAL(11,8), Nullable)

---

## 4. Global Constraints, Indexes, and Optimizations

### Unique Composite Constraints
- To prevent duplicate SKU creation within a tenant space, `products` uses a composite unique constraint: `UNIQUE KEY `tenant_sku_unique` (`tenant_id`, `sku`)`.
- To prevent duplicate invoicing: `UNIQUE KEY `tenant_invoice_unique` (`tenant_id`, `invoice_number`)`.

### Indexes for Partition Filtering
All queries filters operations check `tenant_id` in their `WHERE` clause. Therefore, composite indexes are set on the leading query filters columns:
- `INDEX `tenant_branch_idx` (`tenant_id`, `branch_id`)`
- `INDEX `tenant_sku_idx` (`tenant_id`, `sku`)`
- `INDEX `tenant_barcode_idx` (`tenant_id`, `barcode`)`
- `INDEX `tenant_user_idx` (`tenant_id`, `email`)`

---

*Document maintained by: Database Architect | Last updated: June 2026 | Version: 1.0*
