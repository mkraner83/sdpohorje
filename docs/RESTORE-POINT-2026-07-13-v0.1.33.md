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
