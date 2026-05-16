<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Permissions according to sidebar groups
        |--------------------------------------------------------------------------
        | Format: module_action
        | Example: truck_create, container_create
        |--------------------------------------------------------------------------
        */

        $modules = [
            'Dashboard' => [
                'dashboard_access' => 'Access Dashboard',
            ],

            'SOS' => [
                'sos_list' => 'List SOS Alerts',
                'sos_view' => 'View SOS Alert',
                'sos_update' => 'Resolve / Update SOS Alert',
            ],

            'Chat' => [
                'chat_list' => 'List Chats',
                'chat_view' => 'View Chat Messages',
                'chat_send' => 'Send Chat Message',
            ],

            'Trucks' => [
                'truck_list' => 'List Trucks',
                'truck_view' => 'View Truck',
                'truck_create' => 'Create Truck',
                'truck_update' => 'Update Truck',
                'truck_delete' => 'Delete Truck',
            ],

            'Containers' => [
                'container_list' => 'List Containers',
                'container_view' => 'View Container',
                'container_create' => 'Create Container',
                'container_update' => 'Update Container',
                'container_delete' => 'Delete Container',
            ],

            'Drivers' => [
                'driver_list' => 'List Drivers',
                'driver_view' => 'View Driver',
                'driver_create' => 'Create Driver',
                'driver_update' => 'Update Driver',
                'driver_delete' => 'Delete Driver',
            ],

            'Trips' => [
                'trip_list' => 'List Trips',
                'trip_view' => 'View Trip',
                'trip_create' => 'Create Trip',
                'trip_update' => 'Update Trip',
                'trip_delete' => 'Delete Trip',
            ],

            'Settings' => [
                'setting_view' => 'View Settings',
                'setting_update' => 'Update Settings',
            ],

            'Activity Logs' => [
                'activity_log_list' => 'List Activity Logs',
                'activity_log_view' => 'View Activity Log',
            ],

            'Leaderboard' => [
                'leaderboard_view' => 'View Leaderboard',
            ],

            'Fuel' => [
                'fuel_dashboard_view' => 'View Fuel Dashboard',
                'fuel_alert_list' => 'List Fuel Alerts',
                'fuel_alert_view' => 'View Fuel Alert',
            ],

            'Fleet' => [
                'fleet_alert_view' => 'View Fleet Alert',
            ],

            'Users' => [
                'user_view' => 'View User',
                'user_update' => 'Update User',
                'user_delete' => 'Delete User',
            ],
        ];

        foreach ($modules as $group => $permissions) {
            foreach ($permissions as $permissionName => $label) {
                Permission::updateOrCreate(
                    [
                        'name' => $permissionName,
                        'guard_name' => 'web',
                    ],
                    [
                        'group' => $group,
                    ]
                );
            }
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}