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
