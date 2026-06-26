# SD Pohorje Accounts Plugin

WordPress plugin MVP for role-based registrations and branded authentication forms.

## Features

- Slovenian frontend forms for:
  - Athlete registration
  - Parent registration
  - Login
  - Forgot password
  - Password reset
- English admin pages for:
  - Pending registration approvals
  - Parent-athlete linking (admin-only)
- Staff role intended for backend-only creation
- Pending approval flow blocks login until account is approved

## Install

1. Copy the `sd-pohorje-accounts` folder into `wp-content/plugins/`.
2. Activate **SD Pohorje Accounts** in WordPress admin.
3. Create pages and add shortcodes:
   - `[sdp_register_athlete]`
   - `[sdp_register_parent]`
   - `[sdp_login]`
   - `[sdp_forgot_password]`
   - `[sdp_reset_password]`
4. Manage approvals and links in admin menu: **SDP Accounts**.

## Build ZIP (for upload)

1. Run `chmod +x build-plugin-zip.sh` (only once).
2. Build the plugin package with `./build-plugin-zip.sh`.
3. Upload `sd-pohorje-accounts.zip` in WordPress via Plugins -> Add New -> Upload Plugin.
4. Each build automatically bumps the plugin patch version (for example `0.1.0` -> `0.1.1`).

The ZIP always contains the same plugin folder slug (`sd-pohorje-accounts`), so updates target the same installed plugin. WordPress will treat it as the same plugin during upload.

## Notes

- Parent-athlete linking is admin-only by design.
- Frontend text is Slovenian.
- Backend labels and workflows are English.
