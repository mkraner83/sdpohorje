# SD Pohorje Accounts Plugin

WordPress plugin for ŠD Pohorje account flows, user dashboard, and SD Portal admin management.

## Current Version

- Plugin version: 0.1.33
- Plugin slug: sd-pohorje-accounts

## Frontend Shortcodes

Use these shortcodes on dedicated WordPress pages.

- [sdp_register_athlete]
- [sdp_register_parent]
- [sdp_login]
- [sdp_forgot_password]
- [sdp_reset_password]
- [sdp_dashboard]
- [sdp_club_shop]
- [sdp_club_orders]

## Frontend Features (Slovenian UI)

- Athlete and Parent registration forms.
- Username is auto-suggested from first/last name (select list, not manual free-text input).
- Optional phone fields for both registration forms.
- Registration success flow:
  - Success notice stays on same page.
  - Registration form is replaced with login form directly below notice.
- Login redirects to portal dashboard page.
- Dashboard includes top navigation tabs:
  - Pregled
  - Uredi profil
  - Klubska oprema
  - Moja naročila
  - Moji oglasi
  - Dodaj oglas
- Dashboard overview includes a friendly intro plus guidance for profile editing and used-item selling.
- Club shop lets logged-in Parents/Athletes browse official club products, add multiple items to cart, and submit one no-payment order.
- Club-shop orders support quantity, optional size selection, a required child/athlete recipient field, and customer notes.
- Logged-in users can review their submitted club-shop orders and current order status in the portal.
- Profile editing for logged-in users (username is read-only).
- Login page shows quick registration buttons for Parent and Athlete when the user is not logged in.

## Email Notifications

- User registration confirmation email (HTML + plain text fallback) is sent in Slovenian.
- Marketplace inquiry emails use the same branded HTML wrapper and plain-text fallback.
- Club-shop order confirmation emails are sent to the customer in Slovenian.
- Club-shop order notification emails are sent to the SD Portal admin email.
- Email branding and copy use ŠD Pohorje.
- Admin new-registration notifications are sent to SD Portal settings email.

## Admin Features (English UI)

Main menu:

- SD Portal

Submenus:

- Users
  - Opens native WordPress users table with full built-in functionality.
  - Filtered to Parent and Athlete roles only.
  - Parent/Athlete roles are hidden from the default WordPress users view.
- Club Shop Products
  - Native admin management for club-shop items.
  - Supports product title, description, featured image, price, active/inactive state, and optional sizes.
- Club Shop Orders
  - Native admin order queue for submitted customer orders.
  - Includes customer details, child/athlete recipient, ordered product snapshots, itemized quantities/sizes, status, and admin note.
- Settings
  - Admin Email option for notification destination.
  - Shortcode reference for current frontend pages.

## Install

1. Copy sd-pohorje-accounts into wp-content/plugins/.
2. Activate SD Pohorje Accounts in WordPress admin.
3. Create pages and place shortcodes listed above.
4. Ensure portal page exists at /uporabniski-portal/ and contains [sdp_dashboard].

## Build ZIP (for upload)

1. Run chmod +x build-plugin-zip.sh (once).
2. Build package with ./build-plugin-zip.sh.
3. Upload sd-pohorje-accounts.zip via Plugins -> Add New -> Upload Plugin.
4. Build script auto-bumps patch version each run.

## Restore Guidance

- Latest restore-point documentation is in docs/RESTORE-POINT-2026-07-13-v0.1.33.md.
- Versioned restore ZIP snapshots are stored in docs/restore-points/.

## Notes

- Frontend is Slovenian.
- Admin is English.
- Approval blocking is currently bypassed for pending users (temporary project decision).
