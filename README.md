# NexaPOS ERP

A cloud-based, multi-tenant SaaS ERP + POS platform built with **CodeIgniter 4** (PHP 8.3+), MySQL 8, Redis, and Docker.

---

## 🚀 Quick Start with Docker

```bash
# Clone and setup
git clone https://gitlab.com/hkevin-group/kuliah.git
cd kuliah
cp env .env   # Edit .env with your settings

# Build and start all services
docker-compose up --build -d

# Run database migrations
docker-compose exec app php spark migrate

# Seed initial demo data
docker-compose exec app php spark db:seed InitialDataSeeder
```

**Access the application at:** `http://localhost:8080`

**Default credentials:** `admin@nexapos.id` / `Admin@12345`

---

## 📋 Module Structure

```
app/Modules/
├── Authentication/    # Login, MFA, RBAC
├── POS/               # Cashier terminal, sessions, receipts
└── Inventory/         # Products, stock, transfers, opname
```

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | CodeIgniter 4.7 (PHP 8.3) |
| Database | MySQL 8.0 |
| Cache / Queue | Redis 7.2 |
| Web Server | Nginx 1.26 |
| Frontend | Bootstrap 5.3 + jQuery |
| Containers | Docker + Docker Compose |

## 📄 Documentation

Full documentation is available in the [`Docs/`](./Docs) directory, covering:
- Project Overview & Roadmap
- Product Requirements (PRD) & SRS
- Database Design & API Specification
- UI/UX Wireframes & Role-Permission Matrix
- DevOps Deployment Guide & Sprint Plans

## 🌐 Key URLs

| Page | URL |
|------|-----|
| Login | `http://localhost:8080/auth/login` |
| POS Terminal | `http://localhost:8080/pos/terminal` |
| Inventory | `http://localhost:8080/inventory/stock` |
| Products | `http://localhost:8080/inventory/products` |
| API Health | `http://localhost:8080/api/v1/inventory/stocks` |

---

*NexaPOS ERP © 2026 — Powered by CodeIgniter 4*
