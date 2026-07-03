# 06 — USER STORIES
**NexaPOS ERP — SaaS POS + ERP Platform**

---

## Epic 1: Authentication & Authorization

### US-AUTH-01: Multi-Factor Authentication (MFA)
- **User Story:** As an Owner, I want to enable Multi-Factor Authentication (MFA) on my user account, so that my corporate sales and financial records are protected from unauthorized access.
- **Acceptance Criteria:**
  - Tenant users can view an "MFA Setup" tab in their profile settings.
  - The system must generate a standard TOTP QR code that can be scanned by Google Authenticator or Authy.
  - The user must enter a valid OTP code to activate MFA.
  - Once active, the system must prompt for an OTP code during login after verifying email and password credentials.
  - The system must provide 5 backup recovery codes.

### US-AUTH-02: Granular Role Permissions (RBAC)
- **User Story:** As a Tenant Owner, I want to define specific module access permissions for my roles (e.g. Cashiers can only access POS, Accountants can only access Ledger), so that staff are restricted to their operational domains.
- **Acceptance Criteria:**
  - The system must provide a Role & Permission Matrix UI.
  - Admin can select a role (e.g. Cashier) and check/uncheck module-level access permissions (Read, Create, Edit, Delete).
  - Checking a permission must immediately update the access control policies for that user role in the DB.
  - If a user tries to access a URL or API endpoint for which they do not have permissions, the system must return a 403 Forbidden error page/JSON response.

---

## Epic 2: Multi-Branch & Multi-Tenant

### US-TENANT-01: Tenant Configuration & Subdomain Setup
- **User Story:** As a newly registered merchant, I want to set a custom subdomain during onboarding, so that my staff can access our dedicated system instance.
- **Acceptance Criteria:**
  - During the sign-up flow, the user must input their company name and a desired subdomain name.
  - The system must validate the subdomain prefix to ensure uniqueness.
  - Upon successful registration, the tenant record must be created, and the sub-domain DNS resolver must route `[subdomain].nexapos.id` to the tenant's context.
  - The system must load the tenant's settings (logo, color theme, timezone) when their subdomain is accessed.

### US-BRANCH-01: Branch Resource Isolation
- **User Story:** As a Branch Manager, I want my view to filter and display only sales and inventory data related to my branch, so that I am not distracted by other branches' operations.
- **Acceptance Criteria:**
  - Users must be assigned to one or more specific branches.
  - Upon logging in, if a user is assigned to multiple branches, they must select their active session branch.
  - The backend database queries must dynamically apply a filter condition matching the active branch ID.
  - All inventory checks, sales transactions, and daily reports must reflect only the active branch's data.

---

## Epic 3: POS Operations

### US-POS-01: Barcode Scan Processing
- **User Story:** As a Cashier, I want to scan product barcodes using a USB/Bluetooth scanner, so that items are instantly added to the checkout cart without manual search.
- **Acceptance Criteria:**
  - The POS layout must focus the barcode input field by default.
  - When a barcode value is scanned, the POS must fetch the matching item from the local IndexDB/cache.
  - If the product exists, it must be added to the active cart with an incremented quantity.
  - If the product has multiple variations, a modal must display to select the variant.
  - The scan input must clear automatically and be ready for the next barcode.

### US-POS-02: Dynamic QRIS Settlement
- **User Story:** As a Cashier, I want to display a dynamic QRIS payment code on the customer screen, so that customers can pay using e-wallets and the transaction can settle instantly.
- **Acceptance Criteria:**
  - Upon selecting payment method "QRIS", the system must call the payment gateway API to generate a transaction-specific QR code containing the exact checkout total.
  - The POS UI must render the QR code alongside a 120-second countdown timer.
  - The backend must listen for payment webhook notifications from the payment gateway.
  - Upon receiving the webhook, the POS UI must automatically transition to the "Payment Successful" screen, print the receipt, and close the transaction.

---

## Epic 4: Inventory Management

### US-INV-01: Stock Transfer & Verification
- **User Story:** As a Warehouse Admin, I want to create a Stock Transfer Request to move inventory to a retail branch, so that stock is balanced across locations.
- **Acceptance Criteria:**
  - The system must provide a Stock Transfer UI where users specify Source Warehouse, Target Branch, items, and quantities.
  - Upon submission, items are deducted from the source warehouse and marked as "In Transit".
  - The receiving branch must see the pending transfer request on their dashboard.
  - When the receiving branch staff verifies and accepts the shipment, the items are added to the target branch inventory.
  - Any quantities damaged or missing in transit must be reported as a variance during receiving.

---

## Epic 5: Purchasing & Receiving

### US-PUR-01: Purchase Order Approval Flow
- **User Story:** As a Store Manager, I want my drafted Purchase Orders to require approval from the Business Owner before they are emailed to suppliers, to ensure budget compliance.
- **Acceptance Criteria:**
  - Staff can draft a Purchase Order (PO) and select a supplier.
  - The PO status defaults to "Draft/Pending Approval".
  - The Business Owner receives an email/WhatsApp notification about the pending PO.
  - The Business Owner can approve or reject the PO from the approval dashboard.
  - Once approved, the system must generate a PO PDF and automatically email it to the supplier's registered email address.

---

## Epic 6: CRM, Loyalty & Discounts

### US-CRM-01: Tiered Customer Loyalty Program
- **User Story:** As a Marketing Manager, I want to set up custom loyalty point programs where customers earn points based on their purchase value, so that we can increase customer retention.
- **Acceptance Criteria:**
  - Admin can configure the loyalty conversion rate (e.g. 1 point per Rp 10,000 spent).
  - Admin can set membership tiers (Bronze, Silver, Gold) with multiplier rules (e.g. Gold tier earns 1.5x points).
  - During checkout, selecting a customer must display their current tier and loyalty points.
  - Upon completing a transaction, points must be calculated and added to the customer's balance.
  - The system must support points-to-cash redemption at a defined conversion rate (e.g. 1 point = Rp 100).

---

## Epic 7: Financials & Accounting

### US-ACC-01: Automated Journal Entries
- **User Story:** As an Accountant, I want the system to auto-generate double-entry journal postings when sales and purchases occur, so that I don't have to manually post daily transactions.
- **Acceptance Criteria:**
  - The system must support mapping POS transaction categories to Chart of Accounts (COA) numbers.
  - When a POS transaction completes:
    - Debit Cash/Bank/Receivables (based on payment method).
    - Credit Sales Revenue.
    - Debit Cost of Goods Sold (COGS).
    - Credit Inventory Asset.
  - When a supplier invoice is recorded:
    - Debit Inventory Asset (or Expense).
    - Credit Accounts Payable.
  - The General Ledger must update in real time with these postings.

---

## Epic 8: HR & Payroll

### US-HR-01: Geo-Fenced Mobile Attendance
- **User Story:** As an Employee, I want to clock in and out using my mobile device, so that my attendance is logged without using a physical card machine.
- **Acceptance Criteria:**
  - The employee dashboard must show a "Clock In/Out" interface when accessed on mobile.
  - The system must request GPS location access from the browser.
  - The system must validate the employee's GPS coordinates against the configured geo-fence radius (e.g. 50 meters) of their assigned branch.
  - If the employee is within bounds, the system logs the attendance record with a timestamp and location.
  - If out of bounds, the system must block the check-in and display a "Location verification failed" alert.

---

*Document maintained by: Product Team | Last updated: June 2026 | Version: 1.0*
