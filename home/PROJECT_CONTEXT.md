# Project Context

## TL;DR
This is a standard PHP-based web portal application acting as an official Ministry or Government agency website. It features dynamic content rendering (news, banners, announcements), dedicated information pages structured by category (About, Sectors, Emirates, eServices), and a set of API endpoints for handling data requests.

## Tech Stack
- **Backend:** Core PHP (no mainstream MVC framework like Laravel, relies on custom structuring).
- **Frontend:** HTML5, CSS3, Vanilla JavaScript, jQuery (implied by some legacy structures).
- **Database:** MySQL (managed via `includes/database.php` and `dataN.sql`).
- **PDF Generation:** FPDF/FPDI library (in `FPDI-2.6.4/`).

## How to run
- Requires a standard LAMP/WAMP/XAMPP stack.
- Serve the root directory `c:\xampp\htdocs\websoso` via Apache.
- Entry point is `http://localhost/websoso/index.php`.
- Ensure database is imported from `dataN.sql` and `includes/config.php` / `includes/database.php` points to the correct local DB credentials.

## Architecture
This project follows a component-based server-side rendering approach:
- **Presentation Layer:** `.php` files in `pages/`, `inquiries/`, and the root directory (like `index.php`) act as page controllers/views. They include modular components from the `includes/` directory to share styling and navigation.
- **Data/Logic Layer:** Functional logic is separated into `includes/functions.php`. Database connection is handled by `includes/database.php`.
- **API Layer:** The `api/` folder contains procedural PHP scripts that act as endpoints for AJAX calls. They handle CRUD operations for specific features (like requests, partners, marriage permits).

## Folder structure
- `/api`: Contains PHP scripts acting as REST-like endpoints.
- `/css`, `/js`, `/images`, `/fonts`: Static web assets.
- `/FPDI-2.6.4`: PDF generation library.
- `/includes`: Core shared files. Navigation (`navigation.php`, `data_nav.php`), configuration (`config.php`), database (`database.php`), headers/footers.
- `/inquiries`: Specific functional modules for public inquiries (labor, followup, etc.).
- `/libraries`: External dependencies.
- `/pages`: Static and dynamic content pages categorized into subfolders (`about`, `emirates`, `eservices`, `sectors`, `media`).
- `/public`: Frontend assets tailored for specific modules.
- `/views`: Administrative or isolated layout views.

## Key files
- `index.php`: The main entry point and home page.
- `includes/config.php`: Global constants and configurations (like `BASE_URL`).
- `includes/database.php`: PDO/MySQLi database connection setup.
- `includes/data_nav.php`: The central data array dictating the top navigation menu structure.
- `includes/navigation.php`: The UI template for the top menu.
- `dataN.sql`: The primary database schema and content dump.

## Routes/Endpoints
Routing is file-based (e.g., `/pages/about/about2.php?tab=news`).
The `api/` folder provides the following key endpoints:
- `search_requests.php`
- `update_request.php`
- `delete_request.php`
- `add_partner.php`, `update_partner.php`
- `get_cities.php`, `get_countries.php`

## Business rules
- The "About Ministry" section has been consolidated into a multi-tab SPA-like container (`pages/about/about2.php`) that switches content via `?tab=` query parameter.
- Dropdown menus in the navigation are heavily customized via `is_complex_about` and CSS rules in `css/menu-custom.css`.
- RTL (Right-to-Left) alignment is strictly enforced for Arabic content readability.

## Coding rules
- **Consistency:** Use absolute paths or `BASE_URL` defined in `config.php` for linking assets.
- **Includes:** Always rely on `includes/header.php` and `includes/footer.php` for creating new pages.
- **Separation of Data:** UI loop structures (like navigation or sliders) should pull data from structured arrays (e.g., `data_nav.php`, `data_index.php`) rather than hardcoding HTML.

## "When you modify code" checklist
- [ ] Verify if the change impacts RTL directionality or layout alignment.
- [ ] Update `PROJECT_CONTEXT.md` or `PROJECT_MAP.json` if adding new folders, entry points, or major layout structures.
- [ ] Ensure any newly added `.php` files have the `id="printable-area"` attribute if they require PDF generation capabilities.
- [ ] Verify if navigation data logic in `includes/data_nav.php` needs updating.
