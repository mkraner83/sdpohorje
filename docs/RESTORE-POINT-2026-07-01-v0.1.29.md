# Restore Point - 2026-07-01 - v0.1.29

## Summary

This restore point captures the SD Pohorje Accounts plugin after the marketplace expansion, branded email work, portal login enhancements, and dashboard polish.

## Release Identifier

- Plugin version: 0.1.29
- Restore date: 2026-07-01
- Source commit reference: b2f7395
- Local git tag: restore-point-2026-07-01-v0.1.29

## Restore Artifacts

- Primary deploy ZIP:
  - sd-pohorje-accounts.zip
- Versioned restore snapshot:
  - docs/restore-points/sd-pohorje-accounts-v0.1.29-2026-07-01.zip

## Checksum (SHA-256)

- ae4dc5d13fe4d4a219d0cd1efb54d2720e037e1944d2f0173d27a08c46c33708

## Included Functional Scope

### Frontend Registration and Login

- Athlete and parent registration forms remain in Slovenian.
- Username is auto-suggested from first and last name, with no free-text username entry.
- Phone fields are optional on both registration forms.
- Successful registration keeps the user on the same form page and shows the inline login state.
- Login page includes two branded registration CTAs for:
  - Registracija starša
  - Registracija atleta
- Registration CTAs are responsive:
  - One row on desktop.
  - Two stacked rows on mobile.

### Dashboard and Portal UX

- Frontend portal shortcode remains [sdp_dashboard].
- Dashboard overview now includes:
  - A welcoming hero section.
  - Guidance for profile editing.
  - Guidance for selling used items.
- Dashboard tabs include:
  - Pregled
  - Uredi profil
  - Moji oglasi
  - Dodaj oglas
- The raw role label is no longer shown on the dashboard overview.
- Logged-in portal styling has been refined with hero cards and highlighted callouts.

### Marketplace

- Custom post type marketplace is active for used-item listings.
- Seller submission, edit, delete, and sold-state actions remain available.
- Ownership checks remain enforced for seller-side actions.
- Listing detail pages remain public for active listings.
- Contact-seller email flow uses a branded HTML message with plain-text fallback.
- Search, filters, and sorting remain available for listings.
- Marketplace detail title colors are forced to the blue brand color #366B84.

### Email Delivery

- User registration confirmation email remains branded and styled.
- Marketplace inquiry emails now reuse the same branded HTML wrapper.
- Both email flows include plain-text fallback behavior.
- Both email flows use explicit From headers.
- Mail failure logging remains in place for registration emails.

### Admin

- SD Portal admin menu remains available.
- SD Portal Settings now includes a shortcode reference for current pages.
- Parent and athlete users remain hidden from the default WordPress users list.
- SD Portal Users remains filtered to parent and athlete accounts.

## Restore Procedure

1. In WordPress admin, go to Plugins -> Add New -> Upload Plugin.
2. Upload docs/restore-points/sd-pohorje-accounts-v0.1.29-2026-07-01.zip or sd-pohorje-accounts.zip.
3. Confirm replace/update existing plugin.
4. Verify plugin version shows 0.1.29.

## Verification Checklist After Restore

- Login page shows the two registration buttons below the form for logged-out users.
- Parent and athlete registration pages still render correctly.
- Dashboard overview shows the new intro and no raw role label.
- Dashboard tabs for overview, profile, listings, and add-listing appear correctly.
- Marketplace inquiry emails use the branded layout.
- Marketplace detail titles render in #366B84.
- SD Portal -> Settings still shows the shortcode reference.

## Documentation Updated Alongside This Restore Point

- README.md
- docs/PROJECT-LOG-2026-06-26.md
- docs/RESTORE-POINT-2026-07-01-v0.1.29.md