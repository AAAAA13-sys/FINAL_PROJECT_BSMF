BSMF Garage Project Development Log

I. Project Scaffolding and Schema Definition (April 23, 2026)
*   Initialized the Laravel 11 framework environment and core directory structure.
*   Created 12 migrations to define the database schema, including Users, Categories, Products, Carts, Orders, OrderItems, Reviews, Wishlists, and Coupons.
*   Established foundational models with Eloquent relationships for order tracking and inventory management.
*   Integrated the initial public stylesheets and base layout templates for the storefront.
*   Developed initial controllers for authentication, home navigation, and basic product listing.

II. Infrastructure Migration and Configuration (April 25, 2026)
*   Migrated the database engine from SQLite to a production-grade MySQL 8.0 configuration.
*   Updated config/database.php to standardize connection strings and persistence settings.
*   Configured global charset and collation settings to ensure compatibility with localized data.
*   Optimized server-side caching and session management settings for the new database environment.

III. Administrative Interface and Branding Overhaul (April 25, 2026)
*   Redesigned the Admin Dashboard using a premium dark-glass aesthetic in resources/views/layouts/admin.blade.php.
*   Updated the AdminController to support complex administrative tasks such as multi-image product uploads.
*   Replaced placeholder assets with authentic die-cast model photography in the public/images directory.
*   Implemented a dynamic, filterable sales revenue chart on the administrative dashboard.
*   Enhanced the product management UI with modal-based forms for creating and editing models.

IV. Architectural Refactoring and Performance Optimization (April 27, 2026)
*   Unified Web and API controllers to consolidate business logic and reduce code redundancy.
*   Performed a comprehensive audit of routes/web.php to resolve naming collisions and dead-end routes.
*   Modularized the CSS and JavaScript assets into separate, cacheable components.
*   Fixed critical bugs related to scroll-position restoration and overlapping toast notifications.
*   Purged the repository of redundant folders, including the default Laravel tests directory, as per production requirements.

V. Feature Simplification and Database Integrity (April 29, 2026)
*   Decommissioned the Dispute Management system, removing the DisputeController, models, and associated migration files.
*   Verified and hardened all foreign key constraints between Products, Brands, and OrderItems.
*   Implemented cascading delete logic to ensure that orphan records are not left in the database when products are removed.
*   Validated the "Database Shenanigans" phase to confirm a stable state for the next phase of development.
*   Updated the Product model to handle specific brand and scale relationships more efficiently.

VI. Localization and Visual Design Finalization (April 30, 2026)
*   Localized the entire application for the Philippine market, implementing the Peso (₱) symbol and formatting for all price displays.
*   Standardized the global container padding at 40px across all administrative and storefront pages.
*   Introduced the high-fidelity red-to-yellow hover transition for all primary call-to-action buttons.
*   Refined the Admin Dashboard's color palette to improve readability and visual hierarchy.
*   Adjusted the storefront product cards to ensure consistent image aspect ratios and alignment.

VII. Security Hardening and Role-Based Access Control (May 01, 2026)
*   Replaced the legacy is_admin boolean with a comprehensive string-based role system (admin, staff, customer).
*   Integrated isAdmin() and isStaff() helper methods into the User model for streamlined authorization checks.
*   Locked down the User management and Coupon Vault interfaces to restrict deletion and creation privileges to the Admin role only.
*   Implemented role-based visibility in the sidebar to ensure staff only see relevant management tools.
*   Established a personalized account block in the sidebar displaying the logged-in user's name and role.

 VIII. Audit Logging and Operational Intelligence (May 01, 2026)
*   Created a custom Audit Logging system that records all administrative actions in a dedicated audit_logs table.
*   Refactored the audit log display to translate raw JSON data into human-readable "Before" and "After" state changes.
*   Implemented a metadata scrubbing layer to remove technical noise such as IP addresses and internal database IDs from the logs.
*   Added a detailed audit log viewer that allows owners to review the history of product, order, and coupon changes.

IX. Procedural Optimization and Sequential Numbering (May 01, 2026)
*   Updated the sp_ProcessOrder stored procedure to explicitly enforce utf8mb4_general_ci collation for all parameters.
*   Implemented a new daily sequential order numbering format: BSMF-YY/MM/DD-ID-000.
*   Developed logic within the stored procedure to ensure the sequence counter resets at midnight every day.
*   Removed unused database views (View_BestSellingProducts, View_CustomerSpending) to simplify the schema and improve performance.
*   Enforced a strict linear order status workflow to prevent skipping operational steps (e.g., jumping from Pending to Delivered).

X. System Integration and Final Sanitization (May 01, 2026)
*   Successfully integrated the team member's storefront UI components, including the Cart, Checkout, and Profile modules.
*   Corrected a significant data-binding mismatch where the new UI referenced "code" instead of the database's "coupon_code" field.
*   Synchronized the main application layouts to use the new role-based authorization methods.

    XI. Next Sprint Goals (Planned)
    *   Implementation of an Email Verification system using One-Time Passwords (OTP) sent via the local mail server to ensure collector authenticity.
    *   Enforcement of enhanced Password Security requirements, including minimum length, character complexity, and mandatory symbols.
    *   Profile/index.blade.php overhaul.
    *   Forgot Password functionality using email notification as a fallback since we are using local mail server, OTP is not applicable.
    *   Development of a comprehensive Order Cancellation system that allows users to cancel pending orders while providing specific reasons for the action.
    *   Implementation of an automated Email Notification engine to send confirmation receipts and itemized acquisition lists upon successful checkout.
    *   Integration of a user-facing Error Handling layer that intercepts SQL exceptions and displays thematic, non-technical notifications to prevent sensitive data leaks.
    *   Enhancement of the order cancellation logic to include automatic stock restoration and audit logging for every cancelled transaction.
    *   Implementation of an automated Email Notification engine to send confirmation receipts and itemized acquisition lists upon successful checkout.

