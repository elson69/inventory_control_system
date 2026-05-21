# Database Schema

## Entity Relationship Diagram

```mermaid
erDiagram

    %% ─── Core domain ───────────────────────────────────────────────────────────

    users {
        bigint id PK
        string name
        string email UK
        timestamp email_verified_at
        string password
        string remember_token
        timestamp created_at
        timestamp updated_at
    }

    suppliers {
        bigint id PK
        bigint user_id FK
        string company_name
        string contact_name
        string phone
        text address
        timestamp created_at
        timestamp updated_at
    }

    categories {
        bigint id PK
        string name
        string slug UK
        text description
        timestamp created_at
        timestamp updated_at
    }

    products {
        bigint id PK
        bigint supplier_id FK
        bigint category_id FK
        string name
        string sku UK
        text description
        decimal price
        int stock_quantity
        int min_stock_level
        timestamp created_at
        timestamp updated_at
    }

    stock_logs {
        bigint id PK
        bigint product_id FK
        bigint user_id FK
        enum action
        int quantity_changed
        int balance_after
        text remarks
        timestamp created_at
        timestamp updated_at
    }

    %% ─── Auth infrastructure ────────────────────────────────────────────────────

    password_reset_tokens {
        string email PK
        string token
        timestamp created_at
    }

    sessions {
        string id PK
        bigint user_id FK
        string ip_address
        text user_agent
        longtext payload
        int last_activity
    }

    %% ─── Spatie Permission ───────────────────────────────────────────────────────

    permissions {
        bigint id PK
        string name
        string guard_name
        timestamp created_at
        timestamp updated_at
    }

    roles {
        bigint id PK
        string name
        string guard_name
        timestamp created_at
        timestamp updated_at
    }

    model_has_permissions {
        bigint permission_id FK
        string model_type
        bigint model_id
    }

    model_has_roles {
        bigint role_id FK
        string model_type
        bigint model_id
    }

    role_has_permissions {
        bigint permission_id FK
        bigint role_id FK
    }

    %% ─── Spatie Activity Log ─────────────────────────────────────────────────────

    activity_log {
        bigint id PK
        string log_name
        text description
        string subject_type
        string event
        bigint subject_id
        string causer_type
        bigint causer_id
        json properties
        uuid batch_uuid
        timestamp created_at
        timestamp updated_at
    }

    %% ─── Relationships ───────────────────────────────────────────────────────────

    users ||--o| suppliers : "has one"
    users ||--o{ stock_logs : "performs"
    users ||--o{ sessions : "has"

    suppliers ||--o{ products : "supplies"
    categories ||--o{ products : "categorises"
    products ||--o{ stock_logs : "tracked by"

    permissions ||--o{ model_has_permissions : ""
    permissions ||--o{ role_has_permissions : ""
    roles ||--o{ model_has_roles : ""
    roles ||--o{ role_has_permissions : ""
```

---

## Table Reference

### `users`
Stores all application users (admins and suppliers share this table; role is determined by Spatie roles).

| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| name | string | |
| email | string | unique |
| email_verified_at | timestamp | nullable |
| password | string | hashed |
| remember_token | string | nullable |
| created_at / updated_at | timestamp | |

---

### `suppliers`
One-to-one extension of `users` for supplier-specific profile data. A supplier user must have a matching row here to access the supplier panel (Filament tenancy).

| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| user_id | bigint | FK → users, cascade delete |
| company_name | string | |
| contact_name | string | |
| phone | string | nullable |
| address | text | nullable |
| created_at / updated_at | timestamp | |

---

### `categories`
Product categories.

| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| name | string | |
| slug | string | unique |
| description | text | nullable |
| created_at / updated_at | timestamp | |

---

### `products`
Core inventory item. `stock_quantity` is managed exclusively by `StockService` — it is intentionally excluded from the model's `$fillable` array to prevent direct mass-assignment.

| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| supplier_id | bigint | FK → suppliers, cascade delete |
| category_id | bigint | FK → categories, cascade delete |
| name | string | |
| sku | string | unique |
| description | text | nullable |
| price | decimal(10,2) | |
| stock_quantity | int | default 0, managed by StockService |
| min_stock_level | int | default 10, triggers LowStockDetected event |
| created_at / updated_at | timestamp | |

---

### `stock_logs`
Immutable audit trail of every stock movement. Every call to `StockService::restock()`, `deduct()`, or `adjust()` writes one row.

| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| product_id | bigint | FK → products, cascade delete |
| user_id | bigint | FK → users, cascade delete |
| action | enum | `restock`, `deduction`, `adjustment` |
| quantity_changed | int | negative for deductions |
| balance_after | int | stock_quantity after the operation |
| remarks | text | nullable |
| created_at / updated_at | timestamp | |

---

### `permissions` / `roles` / `model_has_permissions` / `model_has_roles` / `role_has_permissions`
Managed by [spatie/laravel-permission](https://github.com/spatie/laravel-permission). Provides role-based access control for the admin and supplier panels.

---

### `activity_log`
Managed by [spatie/laravel-activitylog](https://github.com/spatie/laravel-activitylog). Records model changes (create/update/delete) on `Product`, `Category`, and `Supplier` models via the `LogsActivity` trait. Uses a polymorphic `subject` relation.

| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| log_name | string | nullable |
| description | text | |
| subject_type / subject_id | polymorphic | the model that changed |
| event | string | nullable (create, update, delete) |
| causer_type / causer_id | polymorphic | the user who made the change |
| properties | json | old/new attribute values |
| batch_uuid | uuid | nullable, groups related log entries |
| created_at / updated_at | timestamp | |

---

### Infrastructure tables
| Table | Purpose |
|---|---|
| `password_reset_tokens` | Laravel password reset flow |
| `sessions` | Database-backed session storage |
| `cache` | Laravel cache driver |
| `jobs` | Laravel queue job storage |
