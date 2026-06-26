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
