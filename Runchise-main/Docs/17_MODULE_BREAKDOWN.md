# 17 — MODULE BREAKDOWN
**NexaPOS ERP — SaaS POS + ERP Platform**

---

## Table of Contents
1. [Core Business Domains Matrix](#1-core-business-domains-matrix)
2. [Module Breakdown Directory](#2-module-breakdown-directory)
   - [Authentication & SaaS Provisioning Domain](#authentication--saas-provisioning-domain)
   - [POS & Retail Store Operations Domain](#pos--retail-store-operations-domain)
   - [Inventory & Purchasing Management Domain](#inventory--purchasing-management-domain)
   - [Double-Entry Accounting & Finance Domain](#double-entry-accounting--finance-domain)
   - [Human Resources & Attendance Domain](#human-resources--attendance-domain)
   - [Customer Relationship & Loyalty Domain](#customer-relationship--loyalty-domain)

---

## 1. Core Business Domains Matrix

To build, test, and release features cleanly, NexaPOS ERP is structured into six core business domains:

```
                  [ NexaPOS Business Domains ]
                  
   +--------------------+  +--------------------+  +--------------------+
   |  SaaS & AUTH       |  |  POS & RETAIL      |  |  INVENTORY & SC    |
   |  - Tenants         |  |  - POS Checkout    |  |  - Warehouses      |
   |  - Subscriptions   |  |  - Branch Manager  |  |  - Purchase Orders |
   |  - RBAC Security   |  |  - Sessions        |  |  - Stock Opname    |
   +--------------------+  +--------------------+  +--------------------+
   
   +--------------------+  +--------------------+  +--------------------+
   |  FINANCE & ACC     |  |  HR & STAFF        |  |  CRM & CAMPAIGNS   |
   |  - General Ledger  |  |  - Attendance      |  |  - Loyalty Points  |
   |  - P&L Statements  |  |  - Rostering       |  |  - Membership      |
   |  - Tax / PPN / PPh |  |  - Payroll Runs    |  |  - Vouchers        |
   +--------------------+  +--------------------+  +--------------------+
```

---

## 2. Module Breakdown Directory

### Authentication & SaaS Provisioning Domain

#### Module: Authentication & RBAC
- **Submodules:** User Sessions, MFA Administration, Password recovery.
- **Views/Pages:** `/login`, `/register`, `/mfa/setup`, `/profile`.
- **Controllers:** `AuthController.php`, `MFAController.php`, `ProfileController.php`.
- **Models:** `UserModel.php`, `RolePermissionModel.php`.
- **Database Tables:** `users`, `roles`, `permissions`, `role_permissions`.
- **APIs Exposed:** `POST /api/v1/auth/login`, `POST /api/v1/auth/mfa/verify`.

#### Module: Tenant & Plan Management
- **Submodules:** Workspace creation, Domain routing, Subscription checkout.
- **Views/Pages:** `/admin/tenants`, `/billing/invoice`, `/billing/upgrade`.
- **Controllers:** `TenantController.php`, `SubscriptionController.php`.
- **Models:** `TenantModel.php`, `SubscriptionModel.php`, `SaaSPlanModel.php`.
- **Database Tables:** `tenants`, `saas_plans`, `saas_subscriptions`, `saas_invoices`.
- **APIs Exposed:** `GET /api/v1/saas/plans`, `POST /api/v1/saas/billing/webhook`.

---

### POS & Retail Store Operations Domain

#### Module: POS Cashier Checkout
- **Submodules:** Product lookup, Cart validation, Receipt thermal printing, Shift Drawer.
- **Views/Pages:** `/pos/terminal`, `/pos/sessions`.
- **Controllers:** `POSController.php`, `POSSessionController.php`.
- **Models:** `TransactionModel.php`, `TransactionItemModel.php`, `POSSessionModel.php`.
- **Database Tables:** `transactions`, `transaction_items`, `pos_sessions`.
- **APIs Exposed:** `POST /api/v1/pos/transactions`, `POST /api/v1/pos/sessions/open`.

#### Module: Multi-Branch & Store Management
- **Submodules:** Outlet configurations, Employee mapping.
- **Views/Pages:** `/admin/branches`, `/admin/warehouses`.
- **Controllers:** `BranchController.php`, `WarehouseController.php`.
- **Models:** `BranchModel.php`, `WarehouseModel.php`.
- **Database Tables:** `branches`, `warehouses`.
- **APIs Exposed:** `GET /api/v1/branches`, `POST /api/v1/branches`.

---

### Inventory & Purchasing Management Domain

#### Module: Product Catalog
- **Submodules:** Variant creation, Barcode management, Categories.
- **Views/Pages:** `/products`, `/categories`, `/brands`.
- **Controllers:** `ProductController.php`, `CategoryController.php`.
- **Models:** `ProductModel.php`, `CategoryModel.php`, `BrandModel.php`.
- **Database Tables:** `products`, `product_variants`, `categories`, `brands`.
- **APIs Exposed:** `GET /api/v1/products`, `POST /api/v1/products`.

#### Module: Inventory Stock Control
- **Submodules:** FIFO valuation, Reorder triggers, Stock Opname audits, Transfers.
- **Views/Pages:** `/inventory/stock`, `/inventory/transfers`, `/inventory/opname`.
- **Controllers:** `StockController.php`, `TransferController.php`, `OpnameController.php`.
- **Models:** `StockModel.php`, `TransferModel.php`, `OpnameModel.php`.
- **Database Tables:** `inventory_stocks`, `stock_transfers`, `stock_opnames`, `stock_adjustments`.
- **APIs Exposed:** `GET /api/v1/inventory/stocks`, `POST /api/v1/inventory/transfers`.

#### Module: Purchasing & Goods Receiving
- **Submodules:** Supplier index, Purchase Orders draft, Goods receiving.
- **Views/Pages:** `/purchase/orders`, `/purchase/receiving`, `/purchase/suppliers`.
- **Controllers:** `PurchaseController.php`, `SupplierController.php`.
- **Models:** `PurchaseOrderModel.php`, `GoodsReceiptModel.php`, `SupplierModel.php`.
- **Database Tables:** `purchase_orders`, `purchase_order_items`, `goods_receipts`, `suppliers`.
- **APIs Exposed:** `POST /api/v1/purchase/orders`, `POST /api/v1/purchase/receiving`.

---

### Double-Entry Accounting & Finance Domain

#### Module: Ledger Bookkeeping
- **Submodules:** Chart of Accounts (COA) mapping, Journal posting, General Ledger.
- **Views/Pages:** `/accounting/coa`, `/accounting/journals`, `/accounting/ledger`.
- **Controllers:** `COAController.php`, `JournalController.php`, `LedgerController.php`.
- **Models:** `COAModel.php`, `JournalEntryModel.php`, `JournalLineModel.php`.
- **Database Tables:** `accounting_coa`, `journal_entries`, `journal_lines`.
- **APIs Exposed:** `POST /api/v1/accounting/journals`, `GET /api/v1/accounting/coa`.

#### Module: Tax & Statement Reporting
- **Submodules:** PPN/PPh settings, P&L, Balance Sheet, Cash Flow.
- **Views/Pages:** `/reports/profit-loss`, `/reports/balance-sheet`, `/admin/taxes`.
- **Controllers:** `ReportController.php`, `TaxController.php`.
- **Models:** `TaxRateModel.php`.
- **Database Tables:** `tax_rates`, `tax_transactions`.
- **APIs Exposed:** `GET /api/v1/reports/profit-loss`, `GET /api/v1/reports/balance-sheet`.

---

### Human Resources & Attendance Domain

#### Module: Staff & Attendance Tracker
- **Submodules:** Employee profiles, Shift schedules, GPS Geo-fenced mobile attendance.
- **Views/Pages:** `/employees`, `/hr/attendance`, `/hr/shifts`.
- **Controllers:** `EmployeeController.php`, `AttendanceController.php`, `ShiftController.php`.
- **Models:** `EmployeeModel.php`, `AttendanceLogModel.php`, `ShiftModel.php`.
- **Database Tables:** `employees`, `attendance_logs`, `shifts`, `employee_shifts`.
- **APIs Exposed:** `POST /api/v1/hr/attendance/clock-in`, `POST /api/v1/hr/attendance/clock-out`.

#### Module: Payroll Administration
- **Submodules:** Salary configuration, Overtime calculation, Payslip generation.
- **Views/Pages:** `/hr/payroll`, `/hr/payroll/payslips`.
- **Controllers:** `PayrollController.php`.
- **Models:** `PayrollRunModel.php`, `PayslipModel.php`.
- **Database Tables:** `payroll_runs`, `payslips`, `salary_components`.
- **APIs Exposed:** `POST /api/v1/hr/payroll/runs`, `GET /api/v1/hr/payslips/{id}`.

---

### Customer Relationship & Loyalty Domain

#### Module: Customer CRM & Loyalty
- **Submodules:** Customer database, Point rules, Membership tiers, Campaign Vouchers.
- **Views/Pages:** `/crm/customers`, `/crm/loyalty-rules`, `/crm/vouchers`.
- **Controllers:** `CustomerController.php`, `LoyaltyController.php`, `VoucherController.php`.
- **Models:** `CustomerModel.php`, `LoyaltyRuleModel.php`, `VoucherModel.php`.
- **Database Tables:** `customers`, `customer_loyalty_logs`, `vouchers`, `membership_tiers`.
- **APIs Exposed:** `POST /api/v1/crm/customers`, `POST /api/v1/crm/vouchers/redeem`.

---

*Document maintained by: Tech Architecture Team | Last updated: June 2026 | Version: 1.0*
