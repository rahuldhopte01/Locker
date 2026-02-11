# Locker & Safe Deposit — Project Status

This document summarizes what has been achieved and what is still pending in the **LockerAndSafeDeposit** package.

---

## Achieved

### 1. Core data model & entities

| Entity | Purpose | Status |
|--------|---------|--------|
| **Locker** | Lockers with number, location, size, status, monthly rate, availability | Done (with `location_id`, `bookings()`, `rentals()`, `history()`) |
| **LockerCustomer** | Customers (no login); first/last name, email, phone, address, id_proof, is_active | Done |
| **LockerLocation** | Buildings, floors, sections, address | Done |
| **LockerBooking** | Bookings with start/end, amount, payment status, reservation fields | Done |
| **LockerRental** | Rentals: locker + customer, dates, payment status, monthly amount, method/type | Done (entity only) |
| **LockerRentalPayment** | Individual payments per rental (date, amount, method, type, receipt, notes) | Done (entity only) |
| **LockerLockerHistory** | Timeline per locker (rental started/ended, payment, reminders, status changes) | Done (entity only) |
| **LockerNotificationLog** | Log for sent notifications | Done (entity + migration) |
| **LockerAuditLog** | Audit trail | Done (entity + migration) |
| **LockerMaintenance** | Maintenance records | Done |
| **LockerKey** | Key management | Done |
| **LockerMembership** | Memberships | Done |
| **LockerRenewal** | Renewals | Done |

### 2. Database migrations

- **Lockers:** Restructure to spec, locations, alignment with locations and flags.
- **Customers:** Table and extra fields (first/last name, phone, is_active).
- **Locations:** `locker_locations` table.
- **Bookings:** Table plus reservation and rental-related fields.
- **Booking payments:** `locker_booking_payments` table.
- **Rentals (new):** Migrations created for:
  - `locker_rentals`
  - `locker_rental_payments`
  - `locker_locker_history`
- **Notifications & audit:** `locker_notification_logs`, `locker_audit_logs`.
- **Other:** Renewals, memberships, keys, maintenances.

*Note: Run `php artisan migrate` to apply any unrun migrations (including the new rental/history tables).*

### 3. Web UI & controllers (existing flows)

| Feature | Controller | Routes | Views | DataTable |
|---------|------------|--------|-------|-----------|
| Customers | CustomerController | `locker-customer` resource | create, edit, index, action | CustomerDataTable |
| Locations | LocationController | `locker-location` resource | create, edit, index, show, action | LocationDataTable |
| Lockers | LockerController | `locker` resource | create, edit, index, action | LockerDataTable |
| Bookings | LockerBookingController | `locker-booking` resource + payment routes | index, create, edit, show, payment, description, action | LockerBookingDataTable |
| Renewals | LockerRenewalController | `locker-renewal` resource | index, create, action | LockerRenewalDataTable |
| Memberships | LockerMembershipController | `locker-membership` resource | index, create, edit, action | LockerMembershipDataTable |
| Keys | LockerKeyController | `locker-key` resource | index, create, edit, action | — |
| Maintenance | LockerMaintenanceController | `locker-maintenance` resource | index, create, edit, description, action | — |

All under prefix `lockerandsafedeposit` with middleware: `web`, `auth`, `verified`, `PlanModuleCheck:LockerAndSafeDeposit`.

### 4. Permissions (seeder)

- Module, customer, location, locker: manage / create / edit / delete (and booking show).
- Booking: manage, create, edit, show, delete; `locker_booking_payment create`.
- Keys, renewal, maintenance, membership: manage and CRUD as applicable.

### 5. Locker entity behaviour

- Sizes and status enums defined.
- `location()`, `bookings()`, `rentals()`, `history()` relations.
- `getYearlyRateAttribute()` (monthly_rate × 12).

### 6. Rental entity behaviour (model only)

- Payment statuses: paid, unpaid, overdue, partial.
- Payment method/type: online/cash, full/partial.
- Relations: `locker()`, `customer()`, `payments()`, `historyEntries()`.
- `getIsOngoingAttribute()` for active rentals.

---

## Pending

### 1. Rental workflow (UI & application layer)

The **rental** model (LockerRental, LockerRentalPayment, LockerLockerHistory) is implemented only at entity and migration level. Still to do:

- [ ] **Run migrations** for `locker_rentals`, `locker_rental_payments`, `locker_locker_history` (if not already run).
- [ ] **RentalController** (or equivalent): index, create, store, edit, update, show, destroy.
- [ ] **Views:** rental index, create, edit, show (and optional list on locker/customer pages).
- [ ] **DataTable:** e.g. RentalDataTable for listing rentals with filters.
- [ ] **Recording rental payments:** form to add payment to a rental (amount, date, method, type, receipt, notes) and save as `LockerRentalPayment`; optionally update rental `payment_status`, `last_payment_date`, `next_payment_due`.
- [ ] **Routes:** e.g. `locker-rental` resource and any payment sub-routes.
- [ ] **Permissions:** e.g. `locker_rental manage/create/edit/delete/show` and add to PermissionTableSeeder.
- [ ] **Optional:** Link from booking to rental (e.g. “Convert to rental”) or clear separation of booking vs rental flows.

### 2. Locker history timeline

- [ ] **Populate** `locker_locker_history` when events occur (rental started/ended, payment received, reminder sent, overdue notification, status/customer change, note added).
- [ ] **UI** to show history for a locker (e.g. on locker show/detail or a “History” tab), using `LockerLockerHistory` and existing `event_type` / `description` / `metadata`.

### 3. Notifications (reminders & overdue)

- [ ] **Implementation** of payment reminder and overdue notification logic (scheduled or on-demand).
- [ ] **Use** `LockerNotificationLog` to record sent notifications.
- [ ] **Write** corresponding `LockerLockerHistory` entries (e.g. `reminder_sent`, `overdue_notification_sent`).

### 4. Audit logging

- [ ] **Use** `LockerAuditLog` (or equivalent) for important changes (locker, customer, rental, booking) and ensure it is filled where required.

### 5. Optional / future

- [ ] **Integration** between bookings and rentals (e.g. convert booking → rental, or single “agreement” that can be either).
- [ ] **Reporting:** overdue rentals, revenue, occupancy.
- [ ] **README** for the package (setup, env, migrations, main concepts).

---

## Summary

| Area | Achieved | Pending |
|------|----------|---------|
| Entities (Locker, Customer, Location, Booking, etc.) | Yes | — |
| New rental entities + migrations | Yes | Run migrations if needed |
| Booking flow (UI, payments) | Yes | — |
| Rental flow (UI, payments, permissions) | No | Full implementation |
| Locker history (data model) | Yes | Populating + UI |
| Notifications (logs + sending) | Table/entity only | Sending + history entries |
| Audit logs | Table/entity only | Usage in code |
| Permissions for rental | No | Add and seed |

---

*Last updated: February 2025*
