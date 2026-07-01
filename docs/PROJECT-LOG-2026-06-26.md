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

### Latest Restore Point Prepared

- Added a new restore-point document for plugin version 0.1.29.
- Archived the current plugin ZIP as a versioned restore snapshot.
- Captured the current source commit and SHA-256 checksum for reproducibility.
