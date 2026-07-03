# 11 — UI/UX SPECIFICATION
**NexaPOS ERP — SaaS POS + ERP Platform**

---

## Table of Contents
1. [User Interface Design System & Philosophy](#1-user-interface-design-system--philosophy)
2. [Global Layout & Navigation Architecture](#2-global-layout--navigation-architecture)
3. [Dashboard Layout Wireframe](#3-dashboard-layout-wireframe)
4. [POS Screen Wireframe](#4-pos-screen-wireframe)
5. [Inventory & Stock Management Screen Wireframe](#5-inventory--stock-management-screen-wireframe)
6. [Purchase Orders Screen Wireframe](#6-purchase-orders-screen-wireframe)
7. [CRM & Customer Profile Screen Wireframe](#7-crm--customer-profile-screen-wireframe)
8. [Reports & Financials Screen Wireframe](#8-reports--financials-screen-wireframe)

---

## 1. User Interface Design System & Philosophy

NexaPOS ERP implements a premium, modern design layout using Bootstrap 5, customized with HSL color palettes, subtle shadows, and glassmorphism elements.

### Color Palette Tokens (Modern Dark/Light Neutral)
- **Primary Color:** Deep Teal `#0d9488` (HSL `173, 80%, 36%`)
- **Secondary Color:** Slate Blue `#4f46e5` (HSL `244, 76%, 59%`)
- **Background Light:** Warm Neutral `#f8fafc` (HSL `210, 40%, 98%`)
- **Background Dark/Sidebar:** Charcoal Slate `#0f172a` (HSL `222, 47%, 11%`)
- **Text Primary:** Obsidian `#1e293b` (HSL `217, 33%, 17%`)
- **Success Accent:** Mint Green `#10b981` (HSL `162, 76%, 41%`)
- **Danger Accent:** Coral Red `#ef4444` (HSL `0, 84%, 60%`)

---

## 2. Global Layout & Navigation Architecture

The system dashboard viewport uses a split layout:
- **Left Sidebar Navigation:** Fixed width (250px), dark slate background, collapsible.
- **Top Header Bar:** Sticky (60px height), glassmorphism background, search bar, branch selector dropdown, and profile controls.
- **Main Content Area:** Auto-scrolling viewport, light grey background, card-based component wrappers.

---

## 3. Dashboard Layout Wireframe

### Wireframe Diagram
```
+----------------------------------------------------------------------------------+
| Sidebar (Dark)      | Top Header: [Search...]  [Branch: Jakarta-01 v]  [Profile] |
|---------------------+------------------------------------------------------------|
| [*] NexaPOS Logo    |  DASHBOARD OVERVIEW                                        |
|                     |                                                            |
| [ ] Dashboards      |  +------------------------------------------------------+  |
| [ ] POS Terminal    |  | KPIs:                                                |  |
| [ ] Inventory       |  | Today's Sales: Rp 12.5M | Transactions: 85           |  |
| [ ] Purchasing      |  | Low Stock Items: 4      | Shifts Active: 2           |  |
| [ ] Accounting      |  +------------------------------------------------------+  |
| [ ] HR / Staff      |                                                            |
| [ ] CRM / Customers |  +--------------------+  +------------------------------+  |
| [ ] Settings        |  | Sales Trend Chart  |  | Low Stock Warning Table      |  |
|                     |  |                    |  | Item A (3 left) [Reorder]    |  |
|                     |  | [Line Chart: 7 Days|  | Item B (1 left) [Reorder]    |  |
|                     |  +--------------------+  +------------------------------+  |
+----------------------------------------------------------------------------------+
```

---

## 4. POS Screen Wireframe

The POS Terminal viewport optimizes touchscreen real estate, dividing the canvas into a Left-Side Item Grid (70% width) and a Right-Side Transaction Cart (30% width).

### Wireframe Diagram
```
+----------------------------------------------------------------------------------+
| POS: [Search SKU/Barcode...]   Category Filter: [All] [Food] [Drink] [Retail]     |
+---------------------------------------------+------------------------------------+
|                                             | CART LIST                          |
|  +--------------+  +--------------+         |  Item 01  x2   Rp 100,000          |
|  | Product A    |  | Product B    |         |  Item 02  x1   Rp  25,000          |
|  | Rp 50,000    |  | Rp 25,000    |         |                                    |
|  | [In Stock]   |  | [In Stock]   |         |                                    |
|  +--------------+  +--------------+         |------------------------------------|
|  +--------------+  +--------------+         | Customer: [Walk-in Customer      v] |
|  | Product C    |  | Product D    |         |------------------------------------|
|  | Rp 15,000    |  | Rp 80,000    |         | Subtotal:             Rp 125,000   |
|  | [Out of Stock]  | [Low Stock]  |         | Tax (PPN 11%):        Rp  13,750   |
|  +--------------+  +--------------+         | Grand Total:          Rp 138,750   |
|                                             |------------------------------------|
|                                             | Payment: [ Cash ]  [ Card ] [ QRIS]|
|                                             | +--------------------------------+ |
|                                             | |        [PAY NOW (Rp 138,750)]  | |
|                                             | +--------------------------------+ |
+---------------------------------------------+------------------------------------+
```

---

## 5. Inventory & Stock Management Screen Wireframe

This layout details the real-time inventory ledger with action triggers for stock opname, adjustments, and internal stock transfers.

### Wireframe Diagram
```
+----------------------------------------------------------------------------------+
| Inventory / Stocks                                    [+ Stock Opname] [+ Transfer] |
+----------------------------------------------------------------------------------+
| Filters: Warehouse: [All Warehouses v]   Status: [Low Stock v]  [Search product] |
|----------------------------------------------------------------------------------|
| SKU        | Product Name       | Warehouse  | Physical Stock | Status   | Action|
|------------|--------------------|------------|----------------|----------|-------|
| SK-0012    | Wireless Mouse X1  | Whse-A     | 3.00           | Low Stock| [Edit]|
| SK-0094    | USB Cable USB-C    | Whse-B     | 145.00         | In Stock | [Edit]|
| SK-0112    | LED Monitor 24"    | Whse-A     | 0.00           | Out      | [Edit]|
|------------|--------------------|------------|----------------|----------|-------|
| [First] [1] [2] [3] ... [Next]                                                   |
+----------------------------------------------------------------------------------+
```

---

## 6. Purchase Orders Screen Wireframe

### Wireframe Diagram
```
+----------------------------------------------------------------------------------+
| Purchase Orders                                                      [+ New PO]  |
+----------------------------------------------------------------------------------+
| PO Number   | Date       | Supplier   | Total Value | Status      | Actions      |
|-------------|------------|------------|-------------|-------------|--------------|
| PO-2026-001 | 2026-06-01 | PT Supplier| Rp 45,000,000| Pending Appr| [View] [Appr]|
| PO-2026-002 | 2026-05-28 | CV Dagang  | Rp 12,000,000| Received    | [View]       |
| PO-2026-003 | 2026-05-15 | Sinar Indo | Rp 8,500,000| Sent        | [View] [Recv]|
+----------------------------------------------------------------------------------+
```

---

## 7. CRM & Customer Profile Screen Wireframe

### Wireframe Diagram
```
+----------------------------------------------------------------------------------+
| CRM / Customers                                                [+ New Customer]  |
+----------------------------------------------------------------------------------+
| ID  | Customer Name | Phone       | Tier   | Points | Total Spent  | Actions     |
|-----|---------------|-------------|--------|--------|--------------|-------------|
| 12  | Ani Wijaya    | 08122334455 | Gold   | 1,250  | Rp 12,500,000| [View] [Add]|
| 13  | Budi Prasetyo | 08119988776 | Silver | 450    | Rp 4,500,000 | [View] [Add]|
| 14  | Charles Lim   | 08172233441 | Bronze | 50     | Rp 500,000   | [View] [Add]|
+----------------------------------------------------------------------------------+
```

---

## 8. Reports & Financials Screen Wireframe

### Wireframe Diagram
```
+----------------------------------------------------------------------------------+
| Financial Reports: Profit & Loss                                                  |
+----------------------------------------------------------------------------------+
| Filter Period: [ 2026-05-01 ] to [ 2026-05-31 ]   Branch: [All Branches v] [PDF] |
|----------------------------------------------------------------------------------|
| Account Description                                | Debit (Rp)  | Credit (Rp)   |
|----------------------------------------------------+-------------+---------------|
| REVENUES                                           |             |               |
|   4-1000 - Retail Sales Revenue                    |             | 185,000,000   |
|   4-2000 - Services Revenue                        |             | 15,000,000    |
| TOTAL REVENUES                                     |             | 200,000,000   |
|                                                    |             |               |
| COST OF GOODS SOLD                                 |             |               |
|   5-1000 - COGS Retail                             | 110,000,000 |               |
| TOTAL COGS                                         | 110,000,000 |               |
|                                                    |             |               |
| GROSS PROFIT                                       |             | 90,000,000    |
+----------------------------------------------------------------------------------+
```

---

*Document maintained by: Design Team | Last updated: June 2026 | Version: 1.0*
