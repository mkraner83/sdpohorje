# Restore Point - 2026-07-01 - v0.1.15

## Summary

This restore point captures the SD Pohorje Accounts plugin state after the SD Portal admin module rollout and dashboard/navigation updates.

## Release Identifier

- Plugin version: 0.1.15
- Restore date: 2026-07-01
- Source commit reference: b2f7395
- Local git tag: restore-point-2026-07-01-v0.1.15

## Restore Artifacts

- Primary deploy ZIP:
  - sd-pohorje-accounts.zip
- Versioned restore snapshot:
  - docs/restore-points/sd-pohorje-accounts-v0.1.15-2026-07-01.zip

## Checksum (SHA-256)

- a63af41dfb0af470098562b0e0b3aa9b150e816084dce6899e54e777fec2de96

## Included Functional Scope

### Frontend

- Athlete and parent registration forms in Slovenian.
- Auto-suggested username selection (no manual username typing).
- Optional phone field in registration.
- Post-registration inline login form on same page.
- Login and auth form redirects stabilized with return URL handling.
- Dashboard shortcode with tab navigation:
  - Pregled
  - Uredi profil
- Profile editing with username locked (read-only).

### Emails

- User registration confirmation email in Slovenian.
- HTML branded template + plain-text fallback.
- Branding uses ŠD Pohorje.
- Mail send failure logging included.

### Admin

- SD Portal admin menu.
- Submenus:
  - Users
  - Settings
- SD Portal Users uses native WordPress users table filtered to parents + athletes.
- Parent/athlete users hidden from default WordPress users view.
- SD Portal Settings includes Admin Email setting for registration notification destination.

## Restore Procedure

1. In WordPress admin, go to Plugins -> Add New -> Upload Plugin.
2. Upload docs/restore-points/sd-pohorje-accounts-v0.1.15-2026-07-01.zip (or sd-pohorje-accounts.zip).
3. Confirm replace/update existing plugin.
4. Verify plugin version shows 0.1.15.

## Verification Checklist After Restore

- Registration pages load and render correctly.
- Registration success keeps user on same page and shows login form.
- Login redirects to /uporabniski-portal/.
- Dashboard tabs (Pregled/Uredi profil) are visible.
- SD Portal -> Users and SD Portal -> Settings are present.
- Admin Email setting can be saved and is used for registration notifications.
- User registration email is received.

## Documentation Updated Alongside This Restore Point

- README.md
- docs/PROJECT-LOG-2026-06-26.md
- docs/RESTORE-POINT-2026-07-01-v0.1.15.md
