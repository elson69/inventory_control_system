# Project Specification: Inventory Control System

## 1. Project Overview

Build a scalable, web-based Inventory Control System. The system manages products, categories, suppliers, and tracks stock movements via a strict stock log. It includes role-based access control with distinct dashboards for Admins and Suppliers.

## 2. Tech Stack & Tooling

- **Backend:** Laravel (latest version)
- **Frontend / UI:** Tailwind CSS, Alpine.js
- **Database:** MySQL
- **Admin/Dashboard Framework:** Filament PHP (Recommended for rapid TALL-stack dashboard generation, including native Dark Mode and role management).
- **Other Packages:** \* `spatie/laravel-permission` (for roles/permissions)
  - `spatie/laravel-activitylog` (for audit trails)
  - A barcode generation package (e.g., `milon/barcode` or `picqer/php-barcode-generator`)

## 3. User Roles & Access

- **Admin:** Full CRUD access to all system models. Can view system-wide metrics, manage users, and configure settings.
- **Supplier:** Scoped access. Can only view, create, and edit products associated with their `supplier_id`. Can view dashboards specific to their own product metrics.

## 4. Database Schema (Eloquent Models)

### Users

- `id` (Primary Key)
- `name` (string)
- `email` (string, unique)
- `password` (string)
- `timestamps`

### Suppliers

- `id` (Primary Key)
- `user_id` (Foreign Key -> users.id)
- `company_name` (string)
- `contact_name` (string)
- `phone` (string, nullable)
- `address` (text, nullable)
- `timestamps`

### Categories

- `id` (Primary Key)
- `name` (string)
- `slug` (string, unique)
- `description` (text, nullable)
- `timestamps`

### Products

- `id` (Primary Key)
- `supplier_id` (Foreign Key -> suppliers.id)
- `category_id` (Foreign Key -> categories.id)
- `name` (string)
- `sku` (string, unique)
- `description` (text, nullable)
- `price` (decimal: 10,2)
- `stock_quantity` (integer, default 0)
- `min_stock_level` (integer, default 10)
- `timestamps`

### Stock_Logs

- `id` (Primary Key)
- `product_id` (Foreign Key -> products.id)
- `user_id` (Foreign Key -> users.id) // User who initiated the change
- `action` (enum: 'restock', 'deduction', 'adjustment')
- `quantity_changed` (integer)
- `balance_after` (integer)
- `remarks` (string, nullable)
- `timestamps`

## 5. Core Business Logic & Rules

- **Stock Mutation:** The `stock_quantity` on the `Products` table must NEVER be updated directly without creating a corresponding `Stock_Log` entry. Wrap these operations in DB Transactions.
- **Supplier Scoping:** Use Laravel Global Scopes or Filament Tenant/Policy logic to ensure Suppliers strictly cannot view or modify data belonging to other suppliers.
- **Dark Mode:** Implement a dark mode toggle (handled natively if using Filament, otherwise use Tailwind's `class` strategy storing preference in local storage).

## 6. MVP+ Features to Implement

1.  **Low Stock Alerts:** Fire a Laravel Event when a product's stock drops below `min_stock_level`. Create a listener that logs this and a conceptual webhook job setup for external notifications.
2.  **SKU/Barcode Generation:** Automatically generate a printable barcode on the Product View page based on the `sku`.
3.  **CSV Import/Export:** Allow Admins to export current inventory to CSV and bulk-import new products.
4.  **Audit Trail:** Implement `spatie/laravel-activitylog` to track changes (created/updated/deleted) on Products and Categories.

## 7. Execution Phases for AI Assistant

- [ ] **Phase 1: Setup & Auth:** Initialize Laravel, configure MySQL, set up Spatie Permissions, create Admin and Supplier roles. Install Filament PHP (or desired UI scaffolding).
- [ ] **Phase 2: Migrations & Models:** Create all migrations based on the schema above. Set up Eloquent relationships (HasMany, BelongsTo) and mass assignable properties (`$fillable`).
- [ ] **Phase 3: Core CRUD & UI:** Build the Filament Resources (or standard Laravel Controllers/Blade views) for Categories, Suppliers, and Products. Ensure Dark Mode is functional.
- [ ] **Phase 4: The Stock Engine:** Implement the Stock Log logic. Create an action/service class for adjusting stock that strictly uses DB transactions.
- [ ] **Phase 5: Permissions & Scoping:** Apply the policies so the Supplier dashboard is properly isolated from the Admin dashboard.
- [ ] **Phase 6: MVP+ Features:** Add CSV export/import, barcode generation, low stock event dispatchers, and activity logging.
