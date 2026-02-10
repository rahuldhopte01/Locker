<?php

namespace Workdo\LockerAndSafeDeposit\Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class PermissionTableSeeder extends Seeder
{
     public function run()
    {
        Model::unguard();
        Artisan::call('cache:clear');
        $module = 'LockerAndSafeDeposit';

        $permissions  = [
            'locker_and_safe manage',
            'locker_customer manage',
            'locker_customer create',
            'locker_customer edit',
            'locker_customer delete',
            'locker_location manage',
            'locker_location create',
            'locker_location edit',
            'locker_location delete',
            'locker manage',
            'locker create',
            'locker edit',
            'locker delete',
            'locker_booking manage',
            'locker_booking create',
            'locker_booking edit',
            'locker_booking show',
            'locker_booking delete',
            'locker_booking_payment create',
            'locker_keys manage',
            'locker_keys create',
            'locker_keys edit',
            'locker_keys delete',
            'locker_renewal manage',
            'locker_renewal create',
            'locker_renewal delete',
            'locker_maintenance manage',
            'locker_maintenance create',
            'locker_maintenance edit',
            'locker_maintenance delete',
            'locker_membership manage',
            'locker_membership create',
            'locker_membership edit',
            'locker_membership delete',
        ];

        $company_role = Role::where('name','company')->first();
        foreach ($permissions as $key => $value)
        {
            $check = Permission::where('name',$value)->where('module',$module)->exists();
            if($check == false)
            {
                $permission = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => $module,
                        'created_by' => 0,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
                if(!$company_role->hasPermission($value))
                {
                    $company_role->givePermission($permission);
                }
            }
        }
    }
}
