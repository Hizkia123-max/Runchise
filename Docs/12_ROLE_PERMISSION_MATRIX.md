# 12 — ROLE & PERMISSION MATRIX
**NexaPOS ERP — SaaS POS + ERP Platform**

---

## Table of Contents
1. [Introduction to Role-Based Access Control (RBAC)](#1-introduction-to-role-based-access-control-rbac)
2. [Module Access Privilege Levels Mapping](#2-module-access-privilege-levels-mapping)
3. [The Core Role-Permission Grid Matrix](#3-the-core-role-permission-grid-matrix)
4. [Inheritance Rules & Restrictions](#4-inheritance-rules--restrictions)

---

## 1. Introduction to Role-Based Access Control (RBAC)

NexaPOS ERP manages access control authorization checks using a structured Role-Based Access Control (RBAC) engine. Permissions are granularly mapped to controller methods, route endpoints, view components, and database filters.

### Role Definitions
1. **Super Admin (SA):** Infrastructure manager. Controls global SaaS plans, tenant registries, and billing status, but has no access to tenant-specific operation metrics unless requested for technical debugging.
2. **Tenant Owner (TO):** Top administrator for a business tenant context. Possesses absolute privileges within their tenant workspace, including billing configuration.
3. **Manager (MN):** Branch or regional supervisor. Manages daily branch transactions, edits item logs, and approves stock audits.
4. **Cashier (CS):** POS register operator. Interacts with the front-end POS checkout UI, opens/closes cash drawers, and processes refunds.
5. **Inventory Staff (IS):** Warehouse clerk. Manages goods receipts, handles stock counts, and processes inter-branch transfers.
6. **Accountant (AC):** Financial clerk. Reviews journals, balances books, tracks PPN/PPh, and generates financial statements.
7. **HR Staff (HR):** Human resources manager. Manages employee records, schedules work shifts, processes attendance inputs, and runs monthly payroll.
8. **Customer (CU):** Public consumer client. Read-only access to customer portal views (viewing personal loyalty points and active vouchers).

---

## 2. Module Access Privilege Levels Mapping

To maintain database security, access control is grouped into four permission categories:
- **C (Create):** Ability to draft new records.
- **R (Read):** Ability to search and view existing records.
- **U (Update):** Ability to edit, adjust, or override existing records.
- **D (Delete):** Ability to permanently remove or archive records.

---

## 3. The Core Role-Permission Grid Matrix

| Module Domain Area | Super Admin | Tenant Owner | Manager | Cashier | Inventory Staff | Accountant | HR Staff | Customer |
|--------------------|:-----------:|:------------:|:-------:|:-------:|:---------------:|:----------:|:--------:|:--------:|
| **SaaS Billing & Plans** | CRUD | R (Self) | None | None | None | None | None | None |
| **Tenant Settings** | CRUD | CRUD | RU | None | None | None | None | None |
| **Branch Configurations** | R | CRUD | RU | None | None | None | None | None |
| **POS Sales Register** | None | CRUD | CRUD | CR | None | None | None | None |
| **Product Master Catalog**| None | CRUD | RU | R | None | None | None | None |
| **Inventory Stock Ledger**| None | CRUD | CRUD | R | CRUD | R | None | None |
| **Purchase Orders (PO)** | None | CRUD | CRU | None | CRU | R | None | None |
| **Supplier Profiles** | None | CRUD | CRU | None | CRU | R | None | None |
| **Customer CRM & Loyalty**| None | CRUD | CRUD | CRU | None | R | None | R (Self) |
| **Double-Entry Journal** | None | CRUD | R | None | None | CRUD | None | None |
| **Ledgers & P&L Reports** | None | CRUD | R | None | None | CRUD | None | None |
| **Tax Settings** | None | CRUD | R | None | None | CRUD | None | None |
| **Employee HR Records** | None | CRUD | R | None | None | None | CRUD | None |
| **Attendance Timesheets** | None | CRUD | CRUD | C (Self)| None | None | CRUD | None |
| **Payroll Runs** | None | CRUD | None | None | None | RU | CRUD | R (Self) |
| **Security Audit Logs** | R | R | None | None | None | None | None | None |

---

## 4. Inheritance Rules & Restrictions

1. **Owner Rule:** A Tenant Owner has access to all resources within their partition. They cannot modify or view other tenants' databases.
2. **Branch Constraints:** Cashiers, Inventory Staff, and Managers are constrained to the branch ID associated with their active user session.
3. **Audit Rule:** No role (including Tenant Owner and Super Admin) has delete (D) permissions on the `audit_logs` table. This log is append-only.
4. **Payroll Isolation Rule:** Payroll information (e.g. employee base salaries) is blocked from view by Managers, Cashiers, and Accountants. Only Tenant Owners and HR Staff roles can view or modify salary configurations.

---

*Document maintained by: Tech Security Architect | Last updated: June 2026 | Version: 1.0*
