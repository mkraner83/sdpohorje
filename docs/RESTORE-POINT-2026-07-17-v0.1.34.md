# Restore Point - 2026-07-17 - v0.1.34

## Summary

This restore point captures the SD Pohorje Accounts plugin after the dashboard summary enhancement release and the new athlete training-documents module rollout.

## Release Identifier

- Plugin version: 0.1.34
- Restore date: 2026-07-17
- Source commit reference: ec775c4
- Local git tag: restore-point-2026-07-17-v0.1.34

## Restore Artifacts

- Primary deploy ZIP:
  - sd-pohorje-accounts.zip
- Versioned restore snapshot:
  - docs/restore-points/sd-pohorje-accounts-v0.1.34-2026-07-17.zip

## Checksum (SHA-256)

- 94cdfafeda0939703a0c26ef39350ee7880b47c30484704c66c38f2dbf425649

## Included Functional Scope

### Dashboard Overview UX

- Dashboard `Pregled` section now includes icon-based cards for:
  - Uredi profil
  - Prodaja rabljenih predmetov
  - Klubska oprema
- Profile card includes quick account context.
- Marketplace card now shows user-specific listing summary:
  - total listings
  - active listings
  - sold listings
- Club-shop card now shows latest order context:
  - order status
  - item summary
  - quantity snapshot

### Athlete Training Documents

- Added athlete-only dashboard tab:
  - Trening načrti
- Athletes can view a personal list of assigned training documents.
- Documents include:
  - title
  - optional short description
  - publish date
  - direct open/download link

### Training Document Admin Workflow

- Added new SD Portal admin module:
  - Training Plans
- Admin can create/edit training documents with:
  - athlete assignment
  - document file URL
  - Media Library picker integration
  - short description
- Admin list view shows assigned athlete and file link per entry.

### Existing Modules Preserved

- Registration/login/password reset flows remain unchanged.
- Marketplace creation/manage/detail/contact flows remain unchanged.
- Club shop product management, cart ordering, order-history, and order-status flows remain unchanged.
- Existing branded customer/admin email behavior remains unchanged.

## Restore Procedure

1. In WordPress admin, go to Plugins -> Add New -> Upload Plugin.
2. Upload docs/restore-points/sd-pohorje-accounts-v0.1.34-2026-07-17.zip or sd-pohorje-accounts.zip.
3. Confirm replace/update existing plugin.
4. Verify plugin version shows 0.1.34.

## Verification Checklist After Restore

- Dashboard `Pregled` shows icon-based cards for profile, marketplace, and club shop.
- Marketplace summary card reflects actual listing state (including no-posted-items case).
- Club-shop summary card reflects latest order status and summary if orders exist.
- Athlete users see `Trening načrti` as a dashboard tab.
- Athlete training-documents page lists assigned files with open links.
- SD Portal shows `Training Plans` in admin menu.
- Admin can assign a training document to an athlete and select file from Media Library.
- Updated document appears immediately in the assigned athlete dashboard view.

## Documentation Updated Alongside This Restore Point

- README.md
- docs/PROJECT-LOG-2026-06-26.md
- docs/RELEASE-NOTES-2026-07-17-v0.1.34.md
- docs/RESTORE-POINT-2026-07-17-v0.1.34.md
