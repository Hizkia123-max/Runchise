# 14 — TEST PLAN
**NexaPOS ERP — SaaS POS + ERP Platform**

---

## Table of Contents
1. [Testing Strategy & Scope](#1-testing-strategy--scope)
2. [Unit Testing Specifications](#2-unit-testing-specifications)
3. [Integration Testing Specifications](#3-integration-testing-specifications)
4. [REST API Testing Specifications](#4-rest-api-testing-specifications)
5. [User Acceptance Testing (UAT) Flows](#5-user-acceptance-testing-uat-flows)
6. [Performance & Load Testing Specifications](#6-performance--load-testing-specifications)

---

## 1. Testing Strategy & Scope

The testing strategy for NexaPOS ERP is structured around the test pyramid. We emphasize unit and integration test coverage for core business services (e.g. accounting, inventory, SaaS billing) and automated API testing.

```
       / \
      /   \      UAT / Manual Testing (UI/UX validation)
     / UAT \
    /-------\    API Integration Tests (Postman/Newman, PHPUnit)
   /   API   \
  /-----------\  Unit Tests (PHPUnit: Services, Models, Helpers)
 /    Unit     \
/_______________\
```

- **UAT & Manual Testing:** Manual validation of responsive browser layout flows, POS thermal print streams, and local payment hardware connectivity.
- **API Integration Tests:** Automated route verification checks, validation constraints, and payload updates using PHPUnit route testing.
- **Unit Tests:** Automated isolation tests verifying functional behaviors, logical loops, and data calculations.

---

## 2. Unit Testing Specifications

### Target Tooling
- PHPUnit 10.x, Mockery (for decoupling class dependencies).

### Execution Command
```bash
# Execute all PHPUnit unit tests
./vendor/bin/phpunit --testsuite Unit
```

### Test Case Registry (Unit)

| Test ID | Module | Target Class / Method | Test Scenario | Expected Outcome |
|---------|--------|-----------------------|---------------|------------------|
| **UT-001** | Auth | `AuthService::verifyPassword` | Correct password input | Returns `true` |
| **UT-002** | Auth | `AuthService::verifyPassword` | Incorrect password input | Returns `false` |
| **UT-003** | Inventory| `FIFOValuationEngine::calculateCOGS` | Deducting stock across three batch costs | COGS matches weighted FIFO calculations |
| **UT-004** | Inventory| `StockAdjustment::validateQuantity` | Adjusting below zero stock value | Throws `NegativeInventoryException` |
| **UT-005** | Accounting| `JournalEntry::isBalanced` | Total Debit and Credit are equal | Returns `true` |
| **UT-006** | Accounting| `JournalEntry::isBalanced` | Total Debit and Credit are unequal | Returns `false` |

---

## 3. Integration Testing Specifications

Integration testing checks interaction points between controllers, domain services, databases, caching layers, and external mock endpoints.

### Execution Command
```bash
# Execute all integration tests
./vendor/bin/phpunit --testsuite Integration
```

### Test Case Registry (Integration)

| Test ID | Module | Scenario Flow | Assertions |
|---------|--------|---------------|------------|
| **IT-001** | Inventory | Stock Transfer request created by Source Warehouse. | Stock is reserved, and status changes to "InTransit". |
| **IT-002** | Inventory | Target Branch verifies and confirms Stock Transfer. | Destination stock increases, source reserves release. |
| **IT-003** | POS | Cashier closes active POS session shift. | Actual drawer cash is recorded, session changes to "Closed". |
| **IT-004** | SaaS Billing| Tenant upgrade action processed. | Plan constraints update immediately, prorated charge posted. |

---

## 4. REST API Testing Specifications

API verification tests validation handlers, route mappings, HTTP statuses, and JSON responses.

### Execution Command
```bash
# Execute route tests
./vendor/bin/phpunit --testsuite Feature
```

### Test Case Registry (REST API)

| Test ID | Method | Endpoint Route | Request Body / Params | Expected HTTP Status | Expected Response Properties |
|---------|--------|----------------|-----------------------|----------------------|------------------------------|
| **API-001** | POST | `/api/v1/auth/login` | Valid email & password | 200 OK | `"success": true`, JWT token string |
| **API-002** | POST | `/api/v1/auth/login` | Missing password field | 422 Unprocessable | `"error_code": "ERR_VALIDATION_FAILED"`|
| **API-003** | GET | `/api/v1/inventory/stocks`| Missing auth header | 401 Unauthorized | `"error_code": "ERR_INVALID_TOKEN"` |
| **API-004** | POST | `/api/v1/pos/transactions`| Valid transaction body | 201 Created | `"invoice_number"`, `"grand_total"` |

---

## 5. User Acceptance Testing (UAT) Flows

UAT is executed in the staging environment to verify end-to-end user workflows.

### UAT-01: End-to-End POS checkout (QRIS payment)
1. **Action:** Log in as a Cashier, open a new POS Session with an opening balance of Rp 100,000.
2. **Action:** Navigate to the POS screen, scan three items.
3. **Action:** Select dynamic QRIS payment.
4. **Action:** Simulate a success webhook response from the payment gateway mock console.
5. **Validation:** System shifts to payment success screen, receipt prints, session ledger shows the transaction.

### UAT-02: End-to-End Stock Opname Flow
1. **Action:** Log in as Inventory Staff, create a Stock Opname document.
2. **Action:** Enter physical count values with variances.
3. **Action:** Submit for approval (where variance exceeds the threshold).
4. **Action:** Log in as Manager, view pending alerts, and approve the variance request.
5. **Validation:** Inventory Stock balances update, and double-entry adjustment journals are posted.

---

## 6. Performance & Load Testing Specifications

- **Target Tooling:** k6 or Apache JMeter.
- **Target Performance KPIs:**
  - Standard operational requests must load in under 2.0s.
  - API endpoints must return data in under 300ms.
  - The system must support a minimum of 200 requests/second with a failure rate of less than 0.1%.
- **Load Test Scenario Script (k6):**
  - Gradually scale virtual users (VUs) from 0 to 500 over a 5-minute ramp-up period.
  - Maintain peak load of 500 VUs for 10 minutes.
  - Verify that the error rate remains under 1%, memory remains stable on application nodes, and MySQL CPU usage stays below 70%.

---

*Document maintained by: QA Lead | Last updated: June 2026 | Version: 1.0*
