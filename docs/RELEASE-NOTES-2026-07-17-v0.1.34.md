# Release Notes - 2026-07-17 - v0.1.34

## Highlights

- Upgraded the dashboard overview with icon-based quick sections and live status summaries.
- Added marketplace listing insight directly on the dashboard landing view (total, active, sold).
- Added latest club-shop order status and short summary directly on the dashboard landing view.
- Added a new athlete-only `Trening načrti` section with downloadable documents.
- Added SD Portal admin management for athlete training documents under a new `Training Plans` menu.
- Added media-library file selection support for training-document upload assignment.
- Documented the release with a new restore point and versioned ZIP snapshot.

## Dashboard UX Updates

- `Pregled` now includes icon-marked summary blocks for:
  - Uredi profil
  - Prodaja rabljenih predmetov
  - Klubska oprema
- Marketplace block now indicates whether the user has posted items and shows:
  - total listings
  - active listings
  - sold listings
- Club-shop block now shows:
  - latest order status
  - short item summary
  - total quantity in the latest order

## Athlete Training Documents

- Added a new athlete-only dashboard tab:
  - Trening načrti
- Athletes can open assigned documents directly from their dashboard.
- Each item can include:
  - document title
  - optional short description
  - publish date
  - direct file link (PDF or similar format)

## Admin Updates (SD Portal)

- Added `Training Plans` custom post type in admin.
- Training-document editor now supports:
  - athlete selection
  - document file URL
  - one-click file selection from WordPress Media Library
  - short description field for athlete-facing context
- Training-document list table now includes:
  - assigned athlete
  - quick file link

## Styling/UI Updates

- Added dashboard icon styles for section headers.
- Added inline status-badge style for order status summary.
- Added training-document card styles for improved readability in athlete portal.

## Packaging

- Plugin version: 0.1.34
- Source commit reference: ec775c4
- Restore ZIP snapshot: `docs/restore-points/sd-pohorje-accounts-v0.1.34-2026-07-17.zip`
- Restore point document: `docs/RESTORE-POINT-2026-07-17-v0.1.34.md`
