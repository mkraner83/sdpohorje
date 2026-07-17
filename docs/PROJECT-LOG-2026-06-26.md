# SD Pohorje Accounts - Project Log (2026-06-26)

## Project Goal
Build a WordPress plugin for SD Pohorje with role-based account flows and branded frontend forms.

## Business Rules Confirmed
- Roles in project scope:
  - Athletes (Atleti)
  - Parents (Starsi)
  - Staff (Trenerji)
- Frontend must be in Slovenian.
- Admin/backend workflow can be in English.
- Self-registration allowed only for Athlete and Parent.
- Staff accounts are backend-created only.
- Parent-athlete linking must be admin-only.

## Repository Initialization and Git Setup Completed
- Git repository initialized.
- Remote origin configured to GitHub repository.
- Branch renamed to main.

## Plugin Implemented
Plugin folder:
- sd-pohorje-accounts/

Main bootstrap:
- sd-pohorje-accounts/sd-pohorje-accounts.php

Core logic class:
- sd-pohorje-accounts/includes/class-sdp-accounts-plugin.php

Assets:
- sd-pohorje-accounts/assets/css/sdp-accounts.css
- sd-pohorje-accounts/assets/js/sdp-accounts.js

## Functional Features Implemented
### 1) Roles
- sdp_athlete
- sdp_parent
- sdp_staff

### 2) Frontend Shortcodes (Slovenian UI)
- [sdp_register_athlete]
- [sdp_register_parent]
- [sdp_login]
- [sdp_forgot_password]
- [sdp_reset_password]

### 3) Registration Flow
- Athlete and Parent self-registration implemented.
- Required field validation implemented.
- Password validation and confirmation checks implemented.
- Nonce validation and sanitization implemented.
- Registrations saved with status: pending.
- Admin email notification on new pending registration implemented.

### 4) Access Control
- Pending users are blocked from login.
- Rejected users are blocked from login.
- Approved users can login.

### 5) Admin Workflows (English UI)
Admin menu:
- SDP Accounts

Subpages:
- Pending Accounts
  - Approve
  - Reject
- Parent-Athlete Links
  - Link
  - Unlink

### 6) Parent-Athlete Linking Constraint
- Linking/unlinking is only available in backend admin pages.
- No frontend linking endpoint was added.

## Design/Styling Work Completed
Initial version included:
- Blue gradient/wave section styling for form wrappers.

Latest update changed this to match user request:
- Removed backgrounds for all plugin form wrappers.
- Removed decorative wave pseudo-elements.
- Set form container width to 100%.
- Left internal form/card styling intact.
- Elementor now controls outer width and section design.

## Packaging and Delivery Workflow
Added build script:
- build-plugin-zip.sh

Build behavior:
- Auto-increments plugin patch version on each build (x.y.z -> x.y.z+1).
- Updates both plugin header version and constant.
- Rebuilds sd-pohorje-accounts.zip in repository root.

Output archive:
- sd-pohorje-accounts.zip

## Version Progress During Session
- 0.1.0 initial scaffold
- 0.1.1 first auto-bump test
- 0.1.2 intermediate build
- 0.1.3 validated stable bump logic
- 0.1.4 background removal + 100% width build

## Notes for Next Session
Recommended next steps:
1. Test full flow on staging with real email delivery.
2. Add admin filters/search in Pending Accounts.
3. Improve field-level validation messages per input.
4. Optional: map reset-password flow directly to custom page links from WP emails.
5. Optional: add i18n wrapping for Slovenian strings to support translation files.

## How to Rebuild Plugin ZIP
Run from repository root:
- ./build-plugin-zip.sh

Result:
- sd-pohorje-accounts.zip ready for WordPress upload update.

---

## Update Log (2026-07-01)

This section records all major upgrades completed after the original MVP log.

### Registration UX and Validation

- Username entry changed from free-text input to auto-generated suggestion list.
- Suggestions are generated from first name + last name patterns.
- Server-side validation enforces valid suggested usernames.
- Phone field is optional in both athlete and parent registration forms.
- After successful registration:
  - Success notice appears on same registration page.
  - Registration form is replaced by login form on that same page.

### Redirect and Flow Stability

- Added explicit per-form return URL posting to avoid homepage fallback redirects.
- Login, forgot-password, and reset-password forms now return to their own pages with notice messages.
- Login redirect set to portal dashboard page: /uporabniski-portal/

### Dashboard and Profile

- Added new frontend shortcode: [sdp_dashboard].
- Dashboard now has tab-style navigation:
  - Pregled
  - Uredi profil
- Default dashboard tab is overview, not profile edit.
- Profile form includes editable account/contact fields except username.

### Email Enhancements

- Added user-facing registration confirmation emails in Slovenian.
- Email templates use branded HTML styling and plain-text fallback.
- Branding updated to ŠD Pohorje terminology.
- Added mail delivery hardening:
  - Explicit From header.
  - HTML + plain fallback send attempt.
  - Failure logging via PHP error_log if both sends fail.

### Admin Area Refactor (SD Portal)

- New WordPress admin menu:
  - SD Portal
    - Users
    - Settings
- SD Portal -> Users:
  - Reuses native WordPress users screen.
  - Filtered to athlete and parent accounts only.
- Default WordPress users list:
  - Athlete and parent roles are hidden from the general list.
- SD Portal -> Settings:
  - Added Admin Email setting for registration notifications.
  - Admin registration notifications now use this setting instead of global WordPress admin_email.

### Access Control Change (Temporary)

- Pending approval accounts are currently allowed to log in.
- Rejected accounts remain blocked.
- This was a project-requested temporary behavior change and may be re-enabled later.

### Styling Updates

- Fixed select/dropdown clipping with improved select control height/line-height/padding.
- Added dashboard navigation and profile helper styles.

### Version Progress (2026-07-01 Session)

- 0.1.5 username suggestions baseline release
- 0.1.6 phone optional update release
- 0.1.7 dropdown clipping fix release
- 0.1.8 compatibility fix for wp_strtolower fatal
- 0.1.9 registration redirect + login CTA improvements
- 0.1.10 dashboard shortcode + profile update flow
- 0.1.11 user registration email template
- 0.1.12 login-after-registration + pending-login bypass behavior
- 0.1.13 email delivery fallback and diagnostics hardening
- 0.1.14 branding update to ŠD + dashboard tab navigation
- 0.1.15 SD Portal admin menu, filtered users, admin email settings

### Additional Updates After 0.1.15

- 0.1.16 marketplace custom post type baseline.
- 0.1.17 marketplace seller submission form and public listing page.
- 0.1.18 marketplace contact-seller flow with inquiry email.
- 0.1.19 marketplace image upload support.
- 0.1.20 "Moji oglasi" management for seller-owned listings.
- 0.1.21 edit/delete/mark-sold marketplace actions with ownership checks.
- 0.1.22 marketplace listing detail page and public active-only visibility.
- 0.1.23 marketplace search, filters, and sorting controls.
- 0.1.24 SD Portal settings expanded with shortcode reference.
- 0.1.25 marketplace detail title color fix and shared email template refactor.
- 0.1.26 branded HTML marketplace inquiry emails with plain-text fallback.
- 0.1.27 login page registration CTAs for parent and athlete.
- 0.1.28 desktop row layout for the dual registration buttons.
- 0.1.29 portal dashboard polish with a friendlier overview, profile-edit guidance, and used-item selling intro.
- 0.1.30 club shop baseline with admin product management, order post type, dashboard tabs, and branded order emails.
- 0.1.31 club shop polish with transparent product-image wrappers and branded admin order notifications.
- 0.1.32 multi-item club-shop cart ordering with required child/athlete recipient field, itemized orders, and updated admin/email views.
- 0.1.33 cart UI refinement with smaller remove buttons and live cart total calculation.

### Latest Restore Point Prepared

- Added a new restore-point document for plugin version 0.1.33.
- Archived the current plugin ZIP as a versioned restore snapshot.
- Captured the current source commit and SHA-256 checksum for reproducibility.

---

## Update Log (2026-07-13)

This section records the club-shop release and restore-point work completed on 2026-07-13.

### Club Shop Module

- Added a new club-shop catalog for official ŠD Pohorje products.
- Added new frontend shortcodes:
  - [sdp_club_shop]
  - [sdp_club_orders]
- Added new dashboard tabs:
  - Klubska oprema
  - Moja naročila
- Added admin-managed Club Shop Products with:
  - title
  - description
  - featured image
  - price
  - active/inactive state
  - optional comma-separated sizes

### Club Shop Ordering Flow

- Initial single-item order flow was replaced by a multi-item cart flow.
- Logged-in parents and athletes can now:
  - add multiple products to cart
  - choose quantity per product
  - choose size where configured
  - enter a required child/athlete recipient field before checkout
  - submit one grouped no-payment order
- Cart UI now includes:
  - live item count
  - live total amount
  - smaller remove buttons in cart rows

### Order Storage and Admin Visibility

- Club shop orders are stored in the dedicated Club Shop Orders post type.
- Each order now stores:
  - customer identity
  - child/athlete recipient
  - itemized cart snapshot
  - total quantity
  - total amount
  - shared customer note
  - order status
  - admin note
- Admin order views were updated to show:
  - recipient field
  - itemized order contents
  - summary totals

### Email Delivery

- Customer club-shop order confirmation email now supports itemized multi-product orders.
- Admin club-shop notification email now uses the branded HTML wrapper plus plain-text fallback.
- Both order email flows now include the child/athlete recipient and itemized order contents.

### Version Progress (2026-07-13 Session)

- 0.1.30 club shop baseline release
- 0.1.31 admin email styling and image-wrapper polish release
- 0.1.32 multi-item cart and recipient-field release
- 0.1.33 cart total and remove-button refinement release

### Restore Point Prepared

- Added release notes for v0.1.33.
- Added a restore-point document for v0.1.33.
- Archived docs/restore-points/sd-pohorje-accounts-v0.1.33-2026-07-13.zip.
- Captured source commit 0266628 and SHA-256 checksum for reproducibility.

### Documentation Updated Alongside This Session

- README.md
- docs/PROJECT-LOG-2026-06-26.md
- docs/RELEASE-NOTES-2026-07-13-v0.1.33.md
- docs/RESTORE-POINT-2026-07-13-v0.1.33.md

---

## Update Log (2026-07-17)

This section records dashboard summary UX improvements and the new athlete training-document module completed on 2026-07-17.

### Dashboard Overview Enhancements

- Added icon-based visual cards on `Pregled` for:
  - Uredi profil
  - Prodaja rabljenih predmetov
  - Klubska oprema
- Added live marketplace status summary for logged-in user:
  - total listings
  - active listings
  - sold listings
- Added live latest-order summary for club shop:
  - status label
  - item summary
  - quantity snapshot

### Athlete Training Documents Module

- Added new athlete-only dashboard tab:
  - Trening načrti
- Added athlete-facing training-document list with:
  - title
  - short description
  - publish date
  - file open/download link

### SD Portal Admin Additions

- Added new admin post type:
  - Training Plans
- Added training-document admin meta box with:
  - athlete selector
  - document URL field
  - Media Library file picker button
  - short description field
- Added admin table columns for assigned athlete and file link.

### Styling and Asset Updates

- Added dashboard card icon and status-badge styles.
- Added training-document card styles for athlete dashboard panel.
- Added new admin-side JS helper for Media Library file selection.

### Version Progress (2026-07-17 Session)

- 0.1.34 dashboard summary cards and athlete training-documents release

### Restore Point Prepared

- Added release notes for v0.1.34.
- Added a restore-point document for v0.1.34.
- Archived docs/restore-points/sd-pohorje-accounts-v0.1.34-2026-07-17.zip.
- Captured source commit ec775c4 and SHA-256 checksum for reproducibility.

### Documentation Updated Alongside This Session

- README.md
- docs/PROJECT-LOG-2026-06-26.md
- docs/RELEASE-NOTES-2026-07-17-v0.1.34.md
- docs/RESTORE-POINT-2026-07-17-v0.1.34.md
*** Add File: /Users/matjazkraner/sd-pohorje-wordpress-project/docs/RELEASE-NOTES-2026-07-13-v0.1.33.md
# Release Notes - 2026-07-13 - v0.1.33

## Highlights

- Added the new `Naročilo klubske opreme` module inside the user portal.
- Added admin-managed Club Shop Products and Club Shop Orders inside SD Portal.
- Added branded customer and admin club-shop order emails.
- Reworked club-shop ordering from single-product submit into a multi-item cart flow.
- Added a required child/athlete recipient field before order submission.
- Polished the cart UI with live totals and smaller remove buttons.
- Documented the release with a new restore point and versioned ZIP snapshot.

## User Experience Updates

- Dashboard now includes two additional tabs:
  - Klubska oprema
  - Moja naročila
- Logged-in parents and athletes can browse official club products, add multiple items to cart, and submit one grouped order.
- Cart rows show quantity, size, price, item count, and a live total amount.
- Before sending the order, the user must enter who the order is for.
- Submitted orders are visible in the portal with itemized contents and status.

## Club Shop Admin Updates

- SD Portal now includes native admin management for Club Shop Products.
- Products support:
  - name
  - description
  - featured image
  - price
  - active/inactive toggle
  - optional sizes
- SD Portal now includes Club Shop Orders with:
  - customer details
  - child/athlete recipient
  - itemized order contents
  - total quantity and total amount
  - order status
  - admin note

## Email Updates

- Customer order confirmations use the branded ŠD Pohorje HTML wrapper with plain-text fallback.
- Admin club-shop notifications now use the same branded styling with plain-text fallback.
- Both emails include the child/athlete recipient and itemized order contents.

## Packaging

- Plugin version: 0.1.33
- Source commit reference: 0266628
- Restore ZIP snapshot: `docs/restore-points/sd-pohorje-accounts-v0.1.33-2026-07-13.zip`
- Restore point document: `docs/RESTORE-POINT-2026-07-13-v0.1.33.md`
*** Add File: /Users/matjazkraner/sd-pohorje-wordpress-project/docs/RESTORE-POINT-2026-07-13-v0.1.33.md
# Restore Point - 2026-07-13 - v0.1.33

## Summary

This restore point captures the SD Pohorje Accounts plugin after the club-shop release, branded order-email rollout, multi-item cart ordering flow, and recipient-field improvements.

## Release Identifier

- Plugin version: 0.1.33
- Restore date: 2026-07-13
- Source commit reference: 0266628
- Local git tag: restore-point-2026-07-13-v0.1.33

## Restore Artifacts

- Primary deploy ZIP:
  - sd-pohorje-accounts.zip
- Versioned restore snapshot:
  - docs/restore-points/sd-pohorje-accounts-v0.1.33-2026-07-13.zip

## Checksum (SHA-256)

- 99e7f2c89beffa582c42c98f4104c074141883181be7e6c63203ca1ef16664d1

## Included Functional Scope

### Frontend Registration and Login

- Athlete and parent registration forms remain in Slovenian.
- Username is auto-suggested from first and last name, with no free-text username entry.
- Phone fields are optional on both registration forms.
- Successful registration keeps the user on the same form page and shows the inline login state.
- Login page includes two branded registration CTAs for:
  - Registracija starša
  - Registracija atleta

### Dashboard and Portal UX

- Frontend portal shortcode remains [sdp_dashboard].
- Dashboard tabs now include:
  - Pregled
  - Uredi profil
  - Klubska oprema
  - Moja naročila
  - Moji oglasi
  - Dodaj oglas
- Dashboard overview continues to include profile and marketplace guidance.
- Club shop is now available directly inside the portal.

### Club Shop

- Frontend shortcodes are available for:
  - [sdp_club_shop]
  - [sdp_club_orders]
- Club Shop Products are admin-managed and support:
  - title
  - description
  - featured image
  - price
  - active/inactive toggle
  - optional sizes
- Logged-in parents and athletes can:
  - browse official club products
  - add multiple products to a cart
  - choose quantity per item
  - choose size where configured
  - enter a required child/athlete recipient before submitting
  - send one grouped no-payment order
- Cart UI includes:
  - live item count
  - live total amount
  - remove-item actions
- Portal order history shows itemized contents and order status.

### Club Shop Orders and Admin

- Club Shop Orders are stored in a dedicated custom post type.
- Admin views include:
  - customer details
  - child/athlete recipient
  - itemized order contents
  - total quantity
  - total amount
  - status selector
  - admin note

### Marketplace

- Marketplace remains active for used-item listings.
- Seller submission, edit, delete, sold-state actions, detail pages, and search/filter/sort behavior remain available.

### Email Delivery

- User registration confirmation email remains branded and styled.
- Marketplace inquiry emails remain branded with plain-text fallback.
- Club-shop customer confirmation emails now include itemized multi-product orders.
- Club-shop admin notification emails now use the branded HTML wrapper with plain-text fallback.
- Club-shop emails include the child/athlete recipient field.

## Restore Procedure

1. In WordPress admin, go to Plugins -> Add New -> Upload Plugin.
2. Upload docs/restore-points/sd-pohorje-accounts-v0.1.33-2026-07-13.zip or sd-pohorje-accounts.zip.
3. Confirm replace/update existing plugin.
4. Verify plugin version shows 0.1.33.

## Verification Checklist After Restore

- Dashboard shows tabs for Klubska oprema and Moja naročila.
- Club shop products render correctly in the portal.
- Users can add multiple products to the cart.
- Cart shows live total amount and smaller remove buttons.
- Order submission requires the child/athlete recipient field.
- Submitted orders appear in Moja naročila with itemized contents.
- SD Portal shows Club Shop Products and Club Shop Orders.
- Admin order detail view shows recipient, itemized contents, total quantity, and total amount.
- Customer receives branded club-shop confirmation email.
- Admin receives branded club-shop notification email.

## Documentation Updated Alongside This Restore Point

- README.md
- docs/PROJECT-LOG-2026-06-26.md
- docs/RELEASE-NOTES-2026-07-13-v0.1.33.md
- docs/RESTORE-POINT-2026-07-13-v0.1.33.md
