<?php

namespace Workdo\LockerAndSafeDeposit\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'LockerAndSafeDeposit';
        $menu = $event->menu;
        $menu->add([
            'category' => 'Operations',
            'title' => __('Locker & Safe Deposit'),
            'icon' => 'shield-lock',
            'name' => 'lockerandsafedeposit',
            'parent' => null,
            'order' => 588,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'locker_and_safe manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Locations'),
            'icon' => '',
            'name' => 'locations',
            'parent' => 'lockerandsafedeposit',
            'order' => 3,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'locker-location.index',
            'module' => $module,
            'permission' => 'locker_location manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Lockers'),
            'icon' => '',
            'name' => 'lockers',
            'parent' => 'lockerandsafedeposit',
            'order' => 5,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'locker.index',
            'module' => $module,
            'permission' => 'locker manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Customer'),
            'icon' => '',
            'name' => 'customer',
            'parent' => 'lockerandsafedeposit',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'locker-customer.index',
            'module' => $module,
            'permission' => 'locker_customer manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Bookings & Assignments'),
            'icon' => '',
            'name' => 'bookingsandassignments',
            'parent' => 'lockerandsafedeposit',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'locker-booking.index',
            'module' => $module,
            'permission' => 'locker_booking manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Deposit Keys & Access Cards'),
            'icon' => '',
            'name' => 'keysandcard',
            'parent' => 'lockerandsafedeposit',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'locker-key.index',
            'module' => $module,
            'permission' => 'locker_keys manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Renewal Management'),
            'icon' => '',
            'name' => 'renewal',
            'parent' => 'lockerandsafedeposit',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'locker-renewal.index',
            'module' => $module,
            'permission' => 'locker_renewal manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Maintenance & Repairs'),
            'icon' => '',
            'name' => 'maintenance&repairs',
            'parent' => 'lockerandsafedeposit',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'locker-maintenance.index',
            'module' => $module,
            'permission' => 'locker_maintenance manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Membership'),
            'icon' => '',
            'name' => 'membership',
            'parent' => 'lockerandsafedeposit',
            'order' => 35,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'locker-membership.index',
            'module' => $module,
            'permission' => 'locker_membership manage'
        ]);
    }
}
