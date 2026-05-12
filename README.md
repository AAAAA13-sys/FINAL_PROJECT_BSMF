# BSMF Garage - Premium Die-Cast Collector Series

## Development Log & Roadmap

### I. Project Scaffolding and Schema Definition (April 23, 2026)
*   Initialized the Laravel 11 framework environment and core directory structure.
*   Created 12 migrations to define the database schema, including Users, Categories, Products, Carts, Orders, OrderItems, Reviews, and Coupons.
*   Established foundational models with Eloquent relationships for order tracking and inventory management.
*   Integrated the initial public stylesheets and base layout templates for the storefront.

### II. Infrastructure Migration and Configuration (April 25, 2026)
*   Migrated the database engine from SQLite to a production-grade MySQL 8.0 configuration.
*   Updated config/database.php to standardize connection strings and persistence settings.
*   Configured global charset and collation settings to ensure compatibility with localized data.

### III. Administrative Interface and Branding Overhaul (April 25, 2026)
*   Redesigned the Admin Dashboard using a premium dark-glass aesthetic.
*   Updated the AdminController to support complex administrative tasks such as multi-image product uploads.
*   Implemented a dynamic, filterable sales revenue chart on the administrative dashboard.

### IV. Architectural Refactoring and Performance Optimization (April 27, 2026)
*   Unified Web and API controllers to consolidate business logic.
*   Performed a comprehensive audit of routes/web.php to resolve naming collisions.
*   Modularized the CSS and JavaScript assets into separate, cacheable components.

### V. Feature Simplification and Database Integrity (April 29, 2026)
*   Decommissioned the Dispute Management system.
*   Verified and hardened all foreign key constraints between Products, Brands, and OrderItems.
*   Implemented cascading delete logic for inventory safety.

### VI. Localization and Visual Design Finalization (April 30, 2026)
*   Localized the application for the Philippine market (₱ symbol and formatting).
*   Standardized global container padding at 40px across all pages.
*   Introduced high-fidelity red-to-yellow hover transitions.

### VII. Security Hardening and Role-Based Access Control (May 01, 2026)
*   Replaced legacy is_admin boolean with a comprehensive role system (admin, staff, customer).
*   Integrated isAdmin() and isStaff() helper methods into the User model.
*   Locked down User management and Coupon Vault interfaces.

### VIII. Audit Logging and Operational Intelligence (May 01, 2026)
*   Created a custom Audit Logging system recording all administrative actions.
*   Refactored log display to show "Before" and "After" state changes.
*   Implemented a metadata scrubbing layer for technical data privacy.

### IX. Procedural Optimization and Sequential Numbering (May 01, 2026)
*   Implemented new daily sequential order numbering format: BSMF-YY/MM/DD-ID-000.
*   Developed midnight reset logic for the order sequence counter.
*   Enforced a strict linear order status workflow.

### X. OTP Security and Identity Recovery (May 05, 2026) - [COMPLETED]
*   **OTP Security Engine**: Implemented 6-digit One-Time Password (OTP) verification for both Registration (Join) and Password Recovery.
*   **Gmail SMTP Integration**: Established a secure connection with Google SMTP using App Passwords for reliable local mail delivery.
*   **Multi-Step Recovery UI**: Overhauled the Forgot Password flow into a high-fidelity, single-page 3-step AJAX transition (Email -> OTP -> New Password).
*   **Administrative Bypass**: Implemented an automated verification bypass for Admin and Staff roles to streamline development.
*   **Branding Synchronization**: Rebranded the entire application from "FINAL_PROJECT" to **BSMF Garage** across all emails and system notifications.
*   **Queue Optimization**: Configured a Database Queue system to ensure instant UI responsiveness during email dispatch.
*   **UX Polishing**: Fixed OTP input visibility, added success toast notifications on the login screen, and implemented automated URL cleaning.

### XI. Collection Expansion and Logistics Optimization (May 07, 2026) - [COMPLETED]
*   **Bulk Collection Import**: Successfully migrated 15 premium models from the "BSMF GARAGE" source, including the MSCH x Hotwheels collab and various STH/RLC pieces.
*   **Logistics Architecture**: Overhauled the shipping engine to support dynamic regional fees (NCR, Luzon, VisMin) and integrated carrier switching (Lalamove for NCR, LBC Express for others).
*   **Nomenclature Hardening**: Refactored the entire frontend CSS architecture from generic naming to semantic UI-focused nomenclature (`admin-ui.css`, `garage-main.css`, etc.).
*   **Quantity Control UI**: Implemented a "Stupid-Proof" quantity selector with readonly inputs and incremental `+/-` buttons to prevent manual entry errors.
*   **Mail Latency Optimization**: Integrated Laravel's `defer()` helper and background queues for Order Confirmations and Shipment notifications, achieving sub-second UI responsiveness.
*   **Persistence Hardening**: Integrated all 15 new products directly into `DatabaseSeeder.php` to ensure catalog integrity across environment resets.
*   **Workflow Completion**: Finalized the **Order Cancellation** logic for both Collectors and Staff, including automated stock restoration and audit logging.

### XII. Future Roadmap (Planned)
*   **Support & FAQ System**: Implementation of a dedicated collector support portal and a comprehensive FAQ section regarding card conditions and shipping.