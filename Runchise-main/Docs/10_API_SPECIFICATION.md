# 10 — API SPECIFICATION
**NexaPOS ERP — SaaS POS + ERP Platform**

---

## Table of Contents
1. [Authentication Flow](#1-authentication-flow)
2. [Global Headers & Responses](#2-global-headers--responses)
3. [Core REST API Endpoints](#3-core-rest-api-endpoints)
   - [Auth: Login (`POST /api/v1/auth/login`)](#auth-login-post-apiv1authlogin)
   - [POS: Create Transaction (`POST /api/v1/pos/transactions`)](#pos-create-transaction-post-apiv1postransactions)
   - [Inventory: Get Stock Levels (`GET /api/v1/inventory/stocks`)](#inventory-get-stock-levels-get-apiv1inventorystocks)
   - [CRM: Add Customer (`POST /api/v1/crm/customers`)](#crm-add-customer-post-apiv1crmcustomers)
4. [Error Handling & Code Registry](#4-error-handling--code-registry)

---

## 1. Authentication Flow

The API uses JWT (JSON Web Tokens) for client request authentication.

```
[Client App] ─── (POST /api/v1/auth/login with credentials) ───> [NexaPOS Gate]
[Client App] <─── (Returns JWT Bearer Access Token & Expiry) ──── [NexaPOS Gate]
[Client App] ─── (Subsequent calls with Authorization Header) ──> [NexaPOS Gate]
```

To request data, clients must supply the returned token in the authorization header:
```http
Authorization: Bearer <JWT_TOKEN>
```

---

## 2. Global Headers & Responses

### Required Request Headers
- `Accept: application/json`
- `Content-Type: application/json`
- `X-Tenant-Domain: subdomain.nexapos.id` (Used to determine active tenant context)

### Global JSON Success Shell
```json
{
  "success": true,
  "message": "Resource action executed successfully",
  "data": {},
  "meta": {
    "timestamp": "2026-06-01T12:00:00Z"
  }
}
```

---

## 3. Core REST API Endpoints

### Auth: Login (`POST /api/v1/auth/login`)

- **Description:** Authenticate client credentials and issue a JWT token.
- **Request Body:**
  ```json
  {
    "email": "owner@brandstore.com",
    "password": "SecurePassword123"
  }
  ```
- **Response Example (200 OK):**
  ```json
  {
    "success": true,
    "message": "Authentication successful",
    "data": {
      "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
      "expires_in": 3600,
      "user": {
        "name": "Budi Santoso",
        "email": "owner@brandstore.com",
        "role": "TenantOwner"
      }
    },
    "meta": {
      "timestamp": "2026-06-01T12:00:01Z"
    }
  }
  ```

---

### POS: Create Transaction (`POST /api/v1/pos/transactions`)

- **Description:** Submit a new transaction from a POS checkout terminal.
- **Request Body:**
  ```json
  {
    "branch_id": 1,
    "pos_session_id": 12,
    "customer_id": 5,
    "payment_method": "QRIS",
    "items": [
      {
        "product_id": 101,
        "quantity": 2.00,
        "unit_price": 50000.00,
        "discount_amount": 0.00
      },
      {
        "product_id": 102,
        "quantity": 1.00,
        "unit_price": 25000.00,
        "discount_amount": 5000.00
      }
    ]
  }
  ```
- **Response Example (201 Created):**
  ```json
  {
    "success": true,
    "message": "Transaction created successfully",
    "data": {
      "transaction_id": 5801,
      "invoice_number": "INV-20260601-002",
      "subtotal": 125000.00,
      "discount_total": 5000.00,
      "tax_total": 13200.00,
      "grand_total": 133200.00,
      "payment_status": "Paid",
      "qris_payload": "00020101021226300016ID.CO.XENDIT.WWW...",
      "created_at": "2026-06-01T12:01:10Z"
    },
    "meta": {
      "timestamp": "2026-06-01T12:01:11Z"
    }
  }
  ```

---

### Inventory: Get Stock Levels (`GET /api/v1/inventory/stocks`)

- **Description:** Returns inventory stock balances, filtered by branch ID.
- **Request Parameters:**
  - `branch_id` (Query String, Required, Integer)
  - `limit` (Query String, Optional, Default 10)
- **Response Example (200 OK):**
  ```json
  {
    "success": true,
    "message": "Stock levels retrieved",
    "data": [
      {
        "product_id": 101,
        "sku": "PROD-EL-01",
        "name": "Wireless Mouse EX-5",
        "quantity": 45.00,
        "reorder_point": 10
      },
      {
        "product_id": 102,
        "sku": "PROD-EL-02",
        "name": "Keyboard Ergonomic Pro",
        "quantity": 8.00,
        "reorder_point": 15
      }
    ],
    "meta": {
      "timestamp": "2026-06-01T12:05:00Z"
    }
  }
  ```

---

### CRM: Add Customer (`POST /api/v1/crm/customers`)

- **Description:** Registers a customer profile to receive loyalty points.
- **Request Body:**
  ```json
  {
    "name": "Joko Widodo",
    "email": "joko.w@email.com",
    "phone": "08123456789",
    "membership_tier": "Silver"
  }
  ```
- **Response Example (210 Created):**
  ```json
  {
    "success": true,
    "message": "Customer registered successfully",
    "data": {
      "customer_id": 402,
      "name": "Joko Widodo",
      "loyalty_points": 0,
      "membership_tier": "Silver",
      "created_at": "2026-06-01T12:06:00Z"
    },
    "meta": {
      "timestamp": "2026-06-01T12:06:01Z"
    }
  }
  ```

---

## 4. Error Handling & Code Registry

When a request encounters validation errors, system faults, or authorization rejections, the gateway API returns standard HTTP status codes along with a JSON error payload structure.

### Global JSON Error Shell
```json
{
  "success": false,
  "error_code": "ERR_VALIDATION_FAILED",
  "message": "Input validation constraints failed",
  "errors": {
    "email": "The email field must contain a unique value."
  },
  "meta": {
    "timestamp": "2026-06-01T12:10:00Z"
  }
}
```

### Error Code Reference Registry

| HTTP Status | Error Code | Description | Action Required |
|-------------|------------|-------------|-----------------|
| 400 Bad Request | `ERR_BAD_REQUEST` | Malformed JSON or input types. | Verify request body format. |
| 401 Unauthorized | `ERR_INVALID_TOKEN` | Token is invalid, expired, or missing. | Re-authenticate via `/auth/login`. |
| 403 Forbidden | `ERR_INSUFFICIENT_PERMISSIONS` | User does not have access rights. | Check role access matrix. |
| 404 Not Found | `ERR_RESOURCE_NOT_FOUND` | Record matching requested ID doesn't exist. | Verify path IDs. |
| 422 Unprocessable | `ERR_VALIDATION_FAILED` | Fields fail syntax rules. | Correct inputs matching errors list. |
| 429 Too Many Requests | `ERR_RATE_LIMIT_EXCEEDED` | Request rate limit exceeded. | Throttle client request sequences. |
| 500 Internal Error | `ERR_INTERNAL_SERVER_ERROR` | System exception or DB deadlock. | Contact system support team. |

---

*Document maintained by: Tech Architecture Team | Last updated: June 2026 | Version: 1.0*
