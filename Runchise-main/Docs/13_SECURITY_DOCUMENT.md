# 13 — SECURITY DOCUMENT
**NexaPOS ERP — SaaS POS + ERP Platform**

---

## Table of Contents
1. [OWASP Top 10 Mitigation Controls Matrix](#1-owasp-top-10-mitigation-controls-matrix)
2. [Authentication Framework Security Standards](#2-authentication-framework-security-standards)
3. [Authorization Checks & Cross-Tenant Leakage Mitigation](#3-authorization-checks--cross-tenant-leakage-mitigation)
4. [Data Encryption Specifications](#4-data-encryption-specifications)
5. [Immutable Audit Log System](#5-immutable-audit-log-system)
6. [Backup and Disaster Recovery Strategy](#6-backup-and-disaster-recovery-strategy)

---

## 1. OWASP Top 10 Mitigation Controls Matrix

This section describes how NexaPOS ERP addresses vulnerability vectors within the CodeIgniter 4 framework context.

| OWASP Vulnerability Category | NexaPOS Framework Defense Implementation |
|-----------------------------|----------------------------------------|
| **A01:2021-Broken Access Control** | Enforced at route level using CI4 Filters. Dynamic query interceptors force `tenant_id` scopes on all operations. |
| **A02:2021-Cryptographic Failures** | Data in transit uses TLS 1.3. Cryptographic fields are hashed via Argon2id. REST JWT tokens utilize HS256/RS256 keys. |
| **A03:2021-Injection (SQLi/XSS)** | CI4 Query Builder forces PDO parameter binding for all queries. XSS protection filter strips tags from HTML user inputs. |
| **A04:2021-Insecure Design** | Separation of concerns (Controllers -> Services -> Models). Secure state workflows (e.g. stock adjustment approvals). |
| **A05:2021-Security Misconfiguration**| Nginx configs block direct directory access. Production environment forces `CI_ENVIRONMENT = production` disabling debugging tools. |
| **A06:2021-Vulnerable & Outdated Components** | Automated weekly security scan using `composer audit` and Github Dependabot alerts. |
| **A07:2021-Identification & Auth Failures** | Argon2id password hashing, mandatory session rotation on privilege changes, IP rate-limiting, lockout timers. |
| **A08:2021-Software & Data Integrity Failures** | Digitally signed update hooks. Auto-verification of payload schemas for queue job deserializations. |
| **A09:2021-Security Logging & Monitoring** | Monolog engine routing errors and audit logs to Elasticsearch. Real-time Slack/WhatsApp integration for system alerts. |
| **A10:2021-Server-Side Request Forgery (SSRF)** | Restricting outgoing curl connections from app nodes using DNS resolution blocklists for internal networks. |

---

## 2. Authentication Framework Security Standards

- **Password Policy Constraints:** Minimum 10 characters, requiring uppercase, lowercase, numeric digits, and special characters.
- **Brute Force Lockout Policy:** If 5 failed login attempts occur within 5 minutes for a single IP address or email target, the account is locked for 15 minutes.
- **Session Lifecycles:** Web session cookie lifetimes are set to 2 hours with `HTTPOnly`, `Secure`, and `SameSite = Strict` properties active.
- **MFA Flow:** TOTP (RFC 6238) algorithm verification is enforced for administrative profiles (Tenant Owner and Super Admin).

---

## 3. Authorization Checks & Cross-Tenant Leakage Mitigation

Data isolation is maintained by enforcing `tenant_id` separation:
- Every table containing tenant-owned data must include a `tenant_id` column.
- The `BaseTenantModel` overrides default CI4 active record functions (e.g. `find()`, `findAll()`, `insert()`, `update()`, `delete()`) to append `tenant_id` clauses to queries.
- A tenant validation check must run on every HTTP request before routing execution.

```php
// Conceptual code for isolation verification
class BaseTenantModel extends \CodeIgniter\Model {
    protected $tenantId;

    public function __construct() {
        parent::__construct();
        // Dynamically extract active Tenant ID from Application State
        $this->tenantId = service('tenant')->getId();
    }

    protected function initializeTenantScope() {
        // Enforce tenant scoping constraint
        $this->builder()->where('tenant_id', $this->tenantId);
    }
}
```

---

## 4. Data Encryption Specifications

### Data in Transit
- Mandatory TLS 1.3 connection protocol. Any incoming HTTP requests are redirected to HTTPS.

### Data at Rest
- Sensitive database fields (e.g., customer identity cards, employee tax IDs, API keys, credentials) are encrypted at rest using AES-256-GCM via CodeIgniter 4's `Encryption` library.
- The Master Encryption Key (`app.encryption_key`) is stored in environment variables (`.env`) and managed by an external KMS (Key Management Service) in production.

---

## 5. Immutable Audit Log System

NexaPOS ERP contains an append-only logging system.
- **Table:** `audit_logs`
- **Fields:** Timestamp, User ID, IP Address, HTTP User Agent, Action, Target Table, Original Payload, Changed Payload.
- **Immutability Rule:** No system route or controller method is allowed to execute `UPDATE` or `DELETE` commands on the `audit_logs` table. DB triggers are established on the DB server to block update and delete requests on this table.

---

## 6. Backup and Disaster Recovery Strategy

To prevent data loss:
- **Backup Frequency:** Automated incremental database snapshots are captured hourly. Full physical backups are generated daily at 02:00 AM (WIB).
- **Storage Redundancy:** Backups are encrypted with AES-256 and pushed to an offsite S3 bucket using object-lock configurations to prevent deletion.
- **Retention Lifetime:** Backups are retained for 30 days. Monthly archives are retained for 7 years to meet Indonesian legal requirements.
- **Disaster Recovery Target:** Recovery Point Objective (RPO) is less than 1 hour. Recovery Time Objective (RTO) is less than 4 hours.

---

*Document maintained by: Tech Security Architect | Last updated: June 2026 | Version: 1.0*
