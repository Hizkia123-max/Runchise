# 04 — BUSINESS REQUIREMENTS DOCUMENT (BRD)
**NexaPOS ERP — SaaS POS + ERP Platform**

---

## Table of Contents
1. [Business Framework & Strategy](#1-business-framework--strategy)
2. [Market Analysis & Value Proposition](#2-market-analysis--value-proposition)
3. [Business Scope & Modules](#3-business-scope--modules)
4. [Regulatory Compliance (Indonesia)](#4-regulatory-compliance-indonesia)
5. [Commercial Models & Billing Workflows](#5-commercial-models--billing-workflows)
6. [Operational and Service Level Requirements](#6-operational-and-service-level-requirements)

---

## 1. Business Framework & Strategy

### 1.1 Executive Mandate
The objective of NexaPOS ERP is to capture the digital transformation market for Indonesian micro, small, and medium enterprises (MSMEs/UMKM). The business goal is to provide a single software platform that manages every operational pipeline of a retail, F&B, or service merchant.

---

## 2. Market Analysis & Value Proposition

### 2.1 The MSME Digital Gap
In Indonesia, over 60 million MSMEs form the backbone of the economy. However, less than 20% are digitalized. Existing solutions are either too complex (enterprise ERPs like SAP) or overly simplified (basic POS applications that lack deep accounting and human resource modules).

### 2.2 NexaPOS Value Proposition Matrix

| Value Proposition | Business Impact | Customer Benefit |
|-------------------|-----------------|------------------|
| **All-in-One Operations** | Reduces overhead costs from multiple software subscriptions. | Single bill, unified user accounts, connected data. |
| **Instant QRIS Processing** | Speeds up payments and reduces errors. | Immediate settlement options, lower transaction errors. |
| **Integrated Accounting** | Keeps books updated automatically from daily transactions. | tax-ready balance sheets without extra accounting fees. |
| **Multi-Branch Operations**| Streamlines multi-location management. | Centrally managed prices, inventory, and staff rosters. |

---

## 3. Business Scope & Modules

The platform supports 56 functional modules grouped into core business domains:

```
[ NexaPOS Business Domains ]
 ├── Core Commerce  (POS, Inventory, Products, Categories, Stock Transfers)
 ├── Supply Chain   (Purchase Orders, Goods Receiving, Supplier Management)
 ├── CRM & Marketing (Loyalty Points, Membership Tiers, Vouchers, CRM Profiles)
 ├── HR & Payroll   (Attendance, Rostering, Shifts, Automated Payroll Engines)
 ├── Financials     (Journal Entries, General Ledger, Tax, P&L, Balance Sheets)
 └── SaaS Billing   (Plan Subscriptions, Feature Gates, Invoicing)
```

---

## 4. Regulatory Compliance (Indonesia)

NexaPOS ERP must comply with the following Indonesian regulations:

### 4.1 Tax Compliance (Direktorat Jenderal Pajak - DJP)
- **PPN (Pajak Pertambahan Nilai):** Support automated calculation of PPN (currently 11%) on invoices and POS receipts.
- **PPh (Pajak Penghasilan):** Support computation of corporate tax deductions, and employee income tax (PPh Pasal 21) within the HR/Payroll module.

### 4.2 Financial Regulations (Bank Indonesia)
- **QRIS Standard:** Automated generation of dynamic QRIS codes in compliance with Bank Indonesia's ASPI standards.
- **Payment Processing:** Payment processing integration with licensed PJP (Penyelenggara Jasa Pembayaran) Category 1 gateways (e.g. Midtrans, Xendit).

### 4.3 Data Privacy (UU No. 27/2022 tentang Pelindungan Data Pribadi - UU PDP)
- Personal data of tenant customers, employees, and owners must be encrypted at rest.
- Right to erasure (Right to be forgotten) workflows must exist for customer database records.

---

## 5. Commercial Models & Billing Workflows

The SaaS monetization is driven by tiered subscriptions:

### 5.1 Pricing Plan Matrix

| Metric / Feature | Starter Plan | Pro Plan | Enterprise Plan |
|-------------------|--------------|----------|-----------------|
| **Monthly Pricing** | Rp 150,000 / month | Rp 300,000 / month | Rp 600,000 / month |
| **Branch Limit** | 1 Branch | Up to 5 Branches | Unlimited Branches |
| **User Limit** | 3 Users | Up to 15 Users | Unlimited Users |
| **Invoice / Month** | Max 1,000 | Max 10,000 | Unlimited |
| **Accounting Module** | Read-Only Reports | Full Accounting | Full + Custom Journals |
| **HR / Payroll** | Basic Attendance | Full HR Suite | Full HR Suite + API |
| **Marketplace Sync** | No | Optional Addon | Included |

### 5.2 Subscription Upgrade/Downgrade Business Rules
- **Rule 1:** When upgrading mid-cycle, the remaining credit of the current plan is prorated and applied as a discount on the new plan invoice.
- **Rule 2:** Downgrades are only permitted at the end of the current billing cycle.
- **Rule 3:** If a tenant fails to pay their renewal invoice within 3 days past the due date, access status changes to "Grace Period" (POS operations disabled, report access enabled). After 14 days, the status changes to "Suspended" (all access disabled).

---

## 6. Operational and Service Level Requirements

- **Operational Hours:** System availability must be maintained 24/7/365.
- **Customer Support SLA:**
  - Critical Issues (POS offline): System restoration or workaround within 2 hours.
  - High Issues (Inventory sync issues): Resolution within 6 hours.
  - Low/Medium Issues (Report formatting): Resolution within 24 hours.
- **Data Retention Policy:** Tenant data is retained for 7 years post-subscription termination to comply with Indonesian accounting laws, after which data is scrubbed.

---

*Document maintained by: Business Operations Team | Last updated: June 2026 | Version: 1.0*
