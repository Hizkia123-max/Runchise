# 07 — WORKFLOW DOCUMENTATION
**NexaPOS ERP — SaaS POS + ERP Platform**

---

## Table of Contents
1. [Sales and POS Checkout Flow](#1-sales-and-pos-checkout-flow)
2. [Purchase Order and Receiving Flow](#2-purchase-order-and-receiving-flow)
3. [Inventory Transfer and Adjustment Flow](#3-inventory-transfer-and-adjustment-flow)
4. [CRM and Loyalty Campaign Flow](#4-crm-and-loyalty-campaign-flow)
5. [Accounting Posting Flow](#5-accounting-posting-flow)
6. [HR Attendance and Payroll Flow](#6-hr-attendance-and-payroll-flow)
7. [SaaS Tenant Onboarding & Subscription Flow](#7-saas-tenant-onboarding--subscription-flow)

---

## 1. Sales and POS Checkout Flow

This workflow illustrates how a cashier handles sales transactions on the POS screen, manages cash/e-wallet payments, and syncs transaction records.

```mermaid
flowchart TD
    A([Start Checkout]) --> B[Scan Barcode / Search Product]
    B --> C{Verify Stock Availability}
    C -- No Stock --> D[Show Stock Alert]
    D --> B
    C -- Stock OK --> E[Add Product to Cart]
    E --> F{Apply Discounts/Vouchers?}
    F -- Yes --> G[Calculate Promotion Rules & Deduct Totals]
    F -- No --> H[Compile Transaction Totals]
    G --> H
    H --> I[Select Payment Method]
    I -- QRIS/E-Wallet --> J[Request Dynamic QR Code]
    J --> K[Render QR Code & Wait for Settlement]
    K --> L{Settlement Confirmed?}
    L -- Yes --> M[Record Sales Invoice]
    L -- No/Timeout --> N[Cancel QRIS Request & Return to Payment Selection]
    N --> I
    I -- Cash/Card --> O[Receive Payment & Calculate Change]
    O --> M
    M --> P[Generate Accounting Journals]
    P --> Q[Deduct Inventory Stock FIFO]
    Q --> R[Update Loyalty Points]
    R --> S[Print Receipt & Open Cash Drawer]
    S --> T([End Checkout])
```

---

## 2. Purchase Order and Receiving Flow

This workflow outlines how purchase requests are routed for approvals, sent to suppliers, and checked in at the warehouse.

```mermaid
flowchart TD
    A([Start Procurement]) --> B[Draft Purchase Order]
    B --> C[Assign Supplier & Items]
    C --> D{Total PO Value > Limit?}
    D -- Yes --> E[Route for Manager/Owner Approval]
    E --> F{Approval Granted?}
    F -- No --> G[Mark PO as Rejected / Revise]
    G --> B
    D -- No --> H[Mark PO as Approved]
    F -- Yes --> H
    H --> I[Generate PDF & Email to Supplier]
    I --> J[Wait for Goods Delivery]
    J --> K[Goods Arrive at Warehouse]
    K --> L[Verify Physical Counts & Expiry against PO]
    L --> M{Discrepancy Found?}
    M -- Yes --> N[Log Variance & Flag Damaged Items]
    M -- No --> O[Complete Goods Receipt Document]
    N --> O
    O --> P[Update Warehouse Stock Balance]
    P --> Q[Generate Accounts Payable Invoice]
    Q --> R([End Procurement])
```

---

## 3. Inventory Transfer and Adjustment Flow

This workflow tracks the stock movements between internal branch warehouses and the validation of inventory levels during audits.

```mermaid
flowchart TD
    A([Start Inventory Move]) --> B[Initiate Stock Transfer Request]
    B --> C[Select Source & Destination Locations]
    C --> D[Add Items & Quantities]
    D --> E[Lock Items in Source Location]
    E --> F[Generate Outbound Shipping Document]
    F --> G[Dispatch Cargo / In-Transit Status]
    G --> H[Arrival at Destination Warehouse]
    H --> I[Verify Received Counts]
    I --> J{Matches Shipment?}
    J -- No --> K[Log Discrepancy & Create Adjustment Ticket]
    J -- Yes --> L[Update Destination Stock Balance]
    K --> L
    L --> M[Deduct Source Location Stock]
    M --> N([End Inventory Move])
```

---

## 4. CRM and Loyalty Campaign Flow

This workflow defines how customers are registered, segmented, and rewarded with points and vouchers based on sales activity.

```mermaid
flowchart TD
    A([Customer Visit]) --> B{Existing Customer?}
    B -- No --> C[Create Customer Profile via POS/Portal]
    B -- Yes --> D[Lookup Customer via Phone/ID]
    C --> D
    D --> E[Check Membership Tier Status]
    E --> F[Process POS Checkout Transaction]
    F --> G[Calculate Earned Loyalty Points]
    G --> H[Add Points to Customer Account Balance]
    H --> I{Points Threshold Reached for Reward?}
    I -- Yes --> J[Auto-Issue Loyalty Reward Voucher]
    I -- No --> K[Update Profile Statistics]
    J --> K
    K --> L[Generate WhatsApp/SMS Thank You Card]
    L --> M([End CRM Action])
```

---

## 5. Accounting Posting Flow

This workflow details the automated trigger points that generate ledger accounts and double-entry book records from commercial activity.

```mermaid
flowchart TD
    A([System Business Event]) --> B{Event Type?}
    B -- POS Checkout --> C[Debit: Cash/E-Wallet Asset Account]
    C --> D[Credit: Sales Revenue Account]
    D --> E[Debit: Cost of Goods Sold Expense]
    E --> F[Credit: Inventory Asset Account]
    B -- Purchase Receipt --> G[Debit: Inventory Asset Account]
    G --> H[Credit: Accounts Payable Liability]
    B -- Payroll Settlement --> I[Debit: Wages and Salaries Expense]
    I --> J[Credit: Cash/Bank Asset Account]
    F --> K[Post Double-Entry Journal Draft]
    H --> K
    J --> K
    K --> L{Is Journal Balanced?}
    L -- No --> M[Rollback DB Transaction & Alert SysAdmin]
    L -- Yes --> N[Commit Posting to General Ledger Tables]
    N --> O([End Posting Workflow])
```

---

## 6. HR Attendance and Payroll Flow

This workflow demonstrates how employee timesheets are recorded, checked against shifts, and processed to compile salaries.

```mermaid
flowchart TD
    A([Shift Start]) --> B[Employee Clock-In via Mobile]
    B --> C[Validate GPS Geofence & Shift Schedule]
    C --> D{Within Geofence Radius?}
    D -- No --> E[Reject Clock-In & Alert Manager]
    D -- Yes --> F[Log Clock-In Timestamp]
    F --> G[Work Shift Hours]
    G --> H[Employee Clock-Out via Mobile]
    H --> I[Log Clock-Out Timestamp]
    I --> J[Calculate Worked Hours & Overtime]
    J --> K[End of Month Payroll Cycle Run]
    K --> L[Aggregate Attendance & Deduction Penalties]
    L --> M[Generate Payslip PDF]
    M --> N[Disburse Salary & Post Payroll Journal]
    N --> O([End HR Workflow])
```

---

## 7. SaaS Tenant Onboarding & Subscription Flow

This workflow documents the lifecycle of a tenant, from initial system provisioning through billing and plan tier gates.

```mermaid
flowchart TD
    A([New Sign-Up Request]) --> B[Create Tenant Workspace Database Record]
    B --> C[Setup Isolated Schema & Seed Standard Master Data]
    C --> D[Generate Default Tenant Owner Admin User]
    D --> E[Assign Trial Period Plan (14 Days)]
    E --> F[Send Welcome Email with Setup Tutorial]
    F --> G[Run Business POS & Inventory Operations]
    G --> H{Trial Period Near Expiration?}
    H -- Yes --> I[Display Upgrade Notification & Email Alerts]
    H -- No --> G
    I --> J{Tenant Pays Subscription Invoice?}
    J -- Yes --> K[Activate Paid Tier Plan & Update Expiry Date]
    K --> G
    J -- No --> L{Past Grace Period?}
    L -- Yes --> M[Suspend Tenant Account / Deactivate POS]
    L -- No --> G
    M --> N([End Lifecycle])
```

---

*Document maintained by: Tech Architecture Team | Last updated: June 2026 | Version: 1.0*
