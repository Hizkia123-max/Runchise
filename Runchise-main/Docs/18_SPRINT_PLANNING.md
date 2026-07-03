# 18 — SPRINT PLANNING
**NexaPOS ERP — SaaS POS + ERP Platform**

---

## Table of Contents
1. [Release Cadence & Velocity Assumptions](#1-release-cadence--velocity-assumptions)
2. [Sprint Backlog Index (Sprint 1 - 20)](#2-sprint-backlog-index-sprint-1---20)
   - [Phase 1: SaaS Core and Authorization Foundation](#phase-1-saas-core-and-authorization-foundation)
   - [Phase 2: Core Store POS & Inventory Operations](#phase-2-core-store-pos--inventory-operations)
   - [Phase 3: Financial Bookkeeping & Tax Systems](#phase-3-financial-bookkeeping--tax-systems)
   - [Phase 4: Employee HR, CRM Campaigns, & Integrations](#phase-4-employee-hr-crm-campaigns--integrations)
   - [Phase 5: Performance Tuning, QA, and Production Launch](#phase-5-performance-tuning-qa-and-production-launch)

---

## 1. Release Cadence & Velocity Assumptions

- **Sprint Duration:** 2 Weeks (10 Working Days).
- **Target Velocity:** 45 Story Points (SP) per sprint.
- **Estimation Matrix:** Fibonacci sequence (1, 2, 3, 5, 8, 13) mapping complexity, risk, and manual efforts.
- **CI/CD Goal:** Automated test pass triggers deployment to the staging server at the end of each sprint.

---

## 2. Sprint Backlog Index (Sprint 1 - 20)

### Phase 1: SaaS Core and Authorization Foundation

#### Sprint 1: Infrastructure Architecture Setup
- **Tasks:**
  - Setup CI4 project directory framework, configure Docker environments. (5 SP)
  - Configure MySQL schema migration tables. (5 SP)
  - Implement base layout structures using Bootstrap 5. (8 SP)
- **Dependencies:** None.
- **Total Points:** 18 SP

#### Sprint 2: Logical Tenant Data Separation
- **Tasks:**
  - Build `BaseTenantModel` to intercept database operations. (8 SP)
  - Build `TenantFilter` middleware to verify subdomains. (8 SP)
  - Create tenant onboarding page and registration workflow. (5 SP)
- **Dependencies:** Sprint 1.
- **Total Points:** 21 SP

#### Sprint 3: Authentication & Security Matrix
- **Tasks:**
  - Implement user credential login & registration routes. (5 SP)
  - Integrate JWT tokens generation for REST API routing. (8 SP)
  - Implement MFA QR code activation panel. (8 SP)
- **Dependencies:** Sprint 2.
- **Total Points:** 21 SP

#### Sprint 4: RBAC & Permissions Engine
- **Tasks:**
  - Write database schema for roles/permissions. (5 SP)
  - Create Role & Permission management UI. (8 SP)
  - Bind access control lists (ACL) check filters to router gates. (8 SP)
- **Dependencies:** Sprint 3.
- **Total Points:** 21 SP

---

### Phase 2: Core Store POS & Inventory Operations

#### Sprint 5: Master Products Catalog
- **Tasks:**
  - Implement dynamic Category, Brand, and Product schemas. (5 SP)
  - Build product CRUD page with SKU input generators. (8 SP)
  - Implement product variation option matrices. (8 SP)
- **Dependencies:** Sprint 4.
- **Total Points:** 21 SP

#### Sprint 6: Warehouses and Stock Ledgers
- **Tasks:**
  - Build warehouse branch configuration tables. (5 SP)
  - Implement FIFO/LIFO inventory calculation classes. (13 SP)
  - Build real-time stock balances list panel. (5 SP)
- **Dependencies:** Sprint 5.
- **Total Points:** 23 SP

#### Sprint 7: POS Transaction Shell
- **Tasks:**
  - Build responsive touch POS cashier checkout viewport screen. (13 SP)
  - Build local barcode scan event handlers using jQuery. (5 SP)
  - Build POS session shift (open/close cash drawer) endpoints. (8 SP)
- **Dependencies:** Sprint 6.
- **Total Points:** 26 SP

#### Sprint 8: Offline Operations & Local Sync
- **Tasks:**
  - Integrate browser IndexDB to hold offline checkout lines. (13 SP)
  - Write client-side network detection and database sync triggers. (8 SP)
  - Implement POS thermal receipts formatting view templates. (8 SP)
- **Dependencies:** Sprint 7.
- **Total Points:** 29 SP

---

### Phase 3: Financial Bookkeeping & Tax Systems

#### Sprint 9: Purchase Orders (PO) & Receivings
- **Tasks:**
  - Build Supplier profiles CRUD database tables. (5 SP)
  - Create Purchase Order workflow, approvals, and PDF email dispatchers. (8 SP)
  - Implement Goods Receiving worksheet with item variance logging. (8 SP)
- **Dependencies:** Sprint 6.
- **Total Points:** 21 SP

#### Sprint 10: Stock Opname & Transfers
- **Tasks:**
  - Build Inter-branch stock transfer pipelines. (8 SP)
  - Create Stock Opname spreadsheet interfaces with manager approvals. (8 SP)
  - Build automatic stock level reorder alert dispatchers. (5 SP)
- **Dependencies:** Sprint 9.
- **Total Points:** 21 SP

#### Sprint 11: Double-Entry Chart of Accounts (COA)
- **Tasks:**
  - Build COA CRUD settings templates. (5 SP)
  - Design journal entry and journal lines transactional schemas. (8 SP)
  - Write General Ledger transaction history lists pages. (8 SP)
- **Dependencies:** Sprint 4.
- **Total Points:** 21 SP

#### Sprint 12: Accounting Posting Engines
- **Tasks:**
  - Write transactional triggers posting journal lines from POS checkouts. (13 SP)
  - Write transactional triggers posting journal lines from Goods Receipts. (8 SP)
  - Implement balance checker safeguards (Total Debits == Total Credits). (8 SP)
- **Dependencies:** Sprint 11.
- **Total Points:** 29 SP

---

### Phase 4: Employee HR, CRM Campaigns, & Integrations

#### Sprint 13: Local Taxes & Financial Reports
- **Tasks:**
  - Build PPN/PPh automated calculation components. (8 SP)
  - Generate Profit & Loss statement calculators. (8 SP)
  - Generate Balance Sheet calculations and output exports (PDF/Excel). (8 SP)
- **Dependencies:** Sprint 12.
- **Total Points:** 24 SP

#### Sprint 14: Staff Directory & Shifts Scheduling
- **Tasks:**
  - Implement Employee profiles directory. (5 SP)
  - Build shift roster planner UI with schedule overlap checks. (8 SP)
  - Implement Geo-Fenced clock-in/out mobile views. (8 SP)
- **Dependencies:** Sprint 4.
- **Total Points:** 21 SP

#### Sprint 15: Payroll Settlement Runs
- **Tasks:**
  - Build timesheet salary rules engines. (8 SP)
  - Implement monthly Payroll Run workflow. (13 SP)
  - Write salary journal entry postings. (8 SP)
- **Dependencies:** Sprint 14.
- **Total Points:** 29 SP

#### Sprint 16: Customer Profiles & Loyalty Engines
- **Tasks:**
  - Build CRM customer profiles ledger pages. (5 SP)
  - Write loyalty points multiplier engines. (8 SP)
  - Implement points-to-cash checkout redemption rules. (8 SP)
- **Dependencies:** Sprint 7.
- **Total Points:** 21 SP

---

### Phase 5: Performance Tuning, QA, and Production Launch

#### Sprint 17: Campaign Promotions & Coupon Vouchers
- **Tasks:**
  - Implement Buy-X-Get-Y discount logic. (8 SP)
  - Create voucher generation controls with validation constraints. (8 SP)
  - Integrate WhatsApp Business API notifications. (8 SP)
- **Dependencies:** Sprint 16.
- **Total Points:** 24 SP

#### Sprint 18: Payment Gateway & QRIS Settlement
- **Tasks:**
  - Integrate Midtrans / Xendit API for invoice checkouts. (8 SP)
  - Build Dynamic QRIS payment callbacks and webhook receivers. (13 SP)
  - Implement Split payment POS options. (8 SP)
- **Dependencies:** Sprint 8.
- **Total Points:** 29 SP

#### Sprint 19: Performance Profiling & Optimization
- **Tasks:**
  - Implement Redis query caching on high-frequency directories. (8 SP)
  - Run database query optimizations and define missing indexes. (8 SP)
  - Write JMeter / k6 load verification test plans. (8 SP)
- **Dependencies:** All previous.
- **Total Points:** 24 SP

#### Sprint 20: Staging Verification & Production Rollout
- **Tasks:**
  - Perform full User Acceptance Testing (UAT). (13 SP)
  - Run penetration testing, OWASP validations, and fix critical lints. (13 SP)
  - Setup CI/CD production pipelines and trigger system launch. (8 SP)
- **Dependencies:** All previous.
- **Total Points:** 34 SP

---

*Document maintained by: PM Lead / Scrum Master | Last updated: June 2026 | Version: 1.0*
