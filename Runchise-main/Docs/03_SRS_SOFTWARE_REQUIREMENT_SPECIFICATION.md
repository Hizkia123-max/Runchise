# 03 — SOFTWARE REQUIREMENT SPECIFICATION (SRS)
**NexaPOS ERP — SaaS POS + ERP Platform**

---

## Table of Contents
1. [Introduction](#1-introduction)
2. [Overall Description](#2-overall-description)
3. [System Requirements](#3-system-requirements)
4. [Functional Requirements](#4-functional-requirements)
5. [Non-Functional Requirements](#5-non-functional-requirements)
6. [System Constraints](#6-system-constraints)
7. [Assumptions and Dependencies](#7-assumptions-and-dependencies)
8. [Business Rules](#8-business-rules)

---

## 1. Introduction

### 1.1 Purpose
This Software Requirements Specification (SRS) details the specifications, design patterns, systems, and structures of NexaPOS ERP. It is written to serve as the definitive specification for engineers, QA analysts, DevOps teams, and architects to build, deploy, and verify the platform.

### 1.2 Scope
This document covers the entire suite of 55+ modules of NexaPOS ERP, a multi-tenant Software-as-a-Service (SaaS) POS and ERP application designed for CodeIgniter 4 (PHP 8.3+) utilizing MySQL 8.0+, Redis caching/queueing, and a mobile-responsive frontend built on Bootstrap 5 and jQuery.

---

## 2. Overall Description

### 2.1 Product Perspective
NexaPOS ERP operates as a centralized web application. The platform provides tenant isolation using a shared database with tenant partitioning (logical isolation). The tenant isolation is maintained through CodeIgniter 4 active record filters, query scopes, and tenant middleware.

### 2.2 Product Functions
The high-level capabilities include:
- SaaS multi-tenant tenant provisioning, billing, plans.
- Multi-branch operational routing.
- Real-time Point of Sale (POS) with QRIS & local gateway integrations.
- Inventory control: Multi-warehouse tracking, FIFO/LIFO valuation, auto reordering, stock opname.
- Financial systems: Double-entry automated bookkeeping, general ledger, balance sheets, local tax (PPN/PPh).
- HR & Payroll: Shift planning, geo-fenced attendance, basic payroll.

---

## 3. System Requirements

### 3.1 Software Requirements (Server & Production Environment)
- **Operating System:** Linux (Ubuntu 22.04 LTS or higher suggested)
- **PHP Version:** PHP 8.3+ with modules: `intl`, `mbstring`, `curl`, `xml`, `mysqlnd`, `gd`, `zip`, `redis`
- **Database Server:** MySQL 8.0+
- **Caching & Queue Server:** Redis 7.2+
- **Web Server:** Nginx 1.26+ (configured for reverse proxy and PHP-FPM)
- **Containerization:** Docker Engine 26.0+ & Docker Compose v2+
- **Process Manager:** Supervisor (for executing PHP background queues)

### 3.2 Hardware Requirements (Minimum Production Environment)
- **CPU:** 4 vCPUs (Intel Xeon / AMD EPYC)
- **RAM:** 8 GB DDR4 ECC RAM
- **Storage:** 80 GB SSD (NVMe storage recommended for DB performance)
- **Bandwidth:** 100 Mbps unmetered connection

---

## 4. Functional Requirements

Functional requirements are categorized across modules and track the core data flows of the system.

### 4.1 Tenant Lifecycle & Subscription
- **SRS-F-001:** Registration process MUST request name, company name, email, phone number, and generate a tenant ID.
- **SRS-F-002:** The system MUST restrict feature access based on the tenant's current subscription plan.
- **SRS-F-003:** Automatic subscription renewal notifications MUST be delivered via WhatsApp and email 7 days prior to expiry.

### 4.2 Branch and Warehouses
- **SRS-F-004:** Each tenant MUST be allowed to configure multiple branches and warehouses.
- **SRS-F-005:** Inventory changes MUST be logged per specific branch/warehouse.
- **SRS-F-006:** A user's access control check MUST confirm they are authorized to access the requested branch prior to returning data.

### 4.3 POS Processing
- **SRS-F-007:** The POS interface MUST function offline using IndexDB for local cart storage, updating data to the MySQL backend when connection status returns to online.
- **SRS-F-008:** POS transactions MUST support payment gateway Webhooks for instant status update notifications (e.g. Midtrans/Xendit QRIS payments).
- **SRS-F-009:** Receipt generation MUST compile a formatted receipt string for direct integration with standard thermal printers (ESC/POS).

---

## 5. Non-Functional Requirements

### 5.1 Security
- **SRS-NF-001:** All communications MUST be forced over HTTPS/TLS 1.3.
- **SRS-NF-002:** User passwords MUST be stored using PHP's `password_hash()` with `PASSWORD_ARGON2ID` or `PASSWORD_BCRYPT` with cost factor 12.
- **SRS-NF-003:** Security tokens for REST APIs MUST use JWT (JSON Web Tokens) with HS256/RS256 algorithms and a 1-hour expiry time.

### 5.2 Performance & Reliability
- **SRS-NF-004:** Web pages MUST render complete UI components in under 2.5 seconds (LCP) under average network conditions.
- **SRS-NF-005:** The backend API response time for transaction creations (POS checkout) MUST be less than 500ms under standard loads.
- **SRS-NF-006:** Uptime SLA for the SaaS platform MUST meet 99.9% excluding planned maintenance windows.

### 5.3 Scalability
- **SRS-NF-007:** MySQL schema design MUST partition audit logs and high-volume transaction records by Tenant ID to support future database horizontal sharding.

---

## 6. System Constraints
- **CON-01 (Database):** All tenant transactions must occur within a unified database cluster. Sharding will only be performed at a tenant ID level when required.
- **CON-02 (Frontend Framework):** To maintain maximum backward compatibility and simplify custom merchant hardware UI views (such as old physical POS displays), React or Vue is avoided. The platform uses a responsive Bootstrap 5 and jQuery frontend.
- **CON-03 (PHP Version):** The application code MUST only leverage features supported natively in PHP 8.3+.

---

## 7. Assumptions and Dependencies
- **ASM-01:** It is assumed that merchants will maintain active internet connections for real-time payment gateway verifications, though POS supports offline draft orders.
- **DEP-01:** The system depends on external services for SMS, Email, and WhatsApp delivery (Fonnte/Wablas).
- **DEP-02:** Third-party payment gateways (Xendit/Midtrans) must maintain API availability for QRIS/E-Wallet generation.

---

## 8. Business Rules

### BR-01: Journal Balancing Rule
Every accounting transaction generated by the system MUST balance. Total Debits MUST equal Total Credits. If a transaction is imbalanced, the model operation MUST rollback and raise an exception.

### BR-02: FIFO Inventory Valuation Rule
For tenants using the FIFO inventory valuation method, cost of goods sold (COGS) calculations must extract unit costs from the earliest active inventory receipt batch that has remaining quantity.

### BR-03: Multi-Branch Data Separation Rule
An employee assigned to Branch A cannot view sales, inventory, shifts, or orders of Branch B unless assigned a role with "Cross-Branch Access" enabled (e.g., Tenant Owner or Super Admin).

### BR-04: Tenant Over-Limit Handling Rule
If a tenant exceeds their plan threshold (e.g., maximum branches, users, or monthly transaction volumes), the platform must prevent the creation of new resource records and trigger a plan upgrade workflow.

---

*Document maintained by: Tech Architecture Team | Last updated: June 2026 | Version: 1.0*
