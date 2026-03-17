<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // ── Kategori ──
        $cuciStandar = ServiceCategory::create([
            'name' => 'Cuci Standar', 'description' => 'Layanan cuci standar',
            'icon' => 'bi-droplet', 'sort_order' => 1, 'is_active' => true,
        ]);

        $cuciPremium = ServiceCategory::create([
            'name' => 'Cuci Premium', 'description' => 'Layanan cuci premium dengan poles',
            'icon' => 'bi-stars', 'sort_order' => 2, 'is_active' => true,
        ]);

        $detailing = ServiceCategory::create([
            'name' => 'Detailing', 'description' => 'Layanan detailing kendaraan',
            'icon' => 'bi-gem', 'sort_order' => 3, 'is_active' => true,
        ]);

        $addon = ServiceCategory::create([
            'name' => 'Add-On', 'description' => 'Layanan tambahan',
            'icon' => 'bi-plus-circle', 'sort_order' => 4, 'is_active' => true,
        ]);

        // ── Services ──

        // Cuci Standar
        Service::create(['service_category_id' => $cuciStandar->id, 'name' => 'Cuci Motor Standar', 'price' => 15000, 'duration_minutes' => 15, 'vehicle_type' => 'motor', 'points_earned' => 1, 'is_active' => true, 'sort_order' => 1]);
        Service::create(['service_category_id' => $cuciStandar->id, 'name' => 'Cuci Mobil Standar', 'price' => 35000, 'duration_minutes' => 30, 'vehicle_type' => 'mobil', 'points_earned' => 1, 'is_active' => true, 'sort_order' => 2]);
        Service::create(['service_category_id' => $cuciStandar->id, 'name' => 'Cuci SUV/MPV Standar', 'price' => 45000, 'duration_minutes' => 35, 'vehicle_type' => 'suv', 'points_earned' => 1, 'is_active' => true, 'sort_order' => 3]);

        // Cuci Premium
        Service::create(['service_category_id' => $cuciPremium->id, 'name' => 'Cuci Motor Premium', 'price' => 30000, 'duration_minutes' => 25, 'vehicle_type' => 'motor', 'points_earned' => 2, 'is_active' => true, 'sort_order' => 1]);
        Service::create(['service_category_id' => $cuciPremium->id, 'name' => 'Cuci Mobil Premium', 'price' => 65000, 'duration_minutes' => 45, 'vehicle_type' => 'mobil', 'points_earned' => 2, 'is_active' => true, 'sort_order' => 2]);
        Service::create(['service_category_id' => $cuciPremium->id, 'name' => 'Cuci SUV/MPV Premium', 'price' => 85000, 'duration_minutes' => 50, 'vehicle_type' => 'suv', 'points_earned' => 2, 'is_active' => true, 'sort_order' => 3]);

        // Detailing
        Service::create(['service_category_id' => $detailing->id, 'name' => 'Interior Detailing Mobil', 'price' => 250000, 'duration_minutes' => 120, 'vehicle_type' => 'mobil', 'points_earned' => 5, 'is_active' => true, 'sort_order' => 1]);
        Service::create(['service_category_id' => $detailing->id, 'name' => 'Exterior Detailing + Wax', 'price' => 350000, 'duration_minutes' => 150, 'vehicle_type' => 'mobil', 'points_earned' => 7, 'is_active' => true, 'sort_order' => 2]);
        Service::create(['service_category_id' => $detailing->id, 'name' => 'Full Detailing Package', 'price' => 500000, 'duration_minutes' => 240, 'vehicle_type' => 'mobil', 'points_earned' => 10, 'is_active' => true, 'sort_order' => 3]);

        // Add-On
        Service::create(['service_category_id' => $addon->id, 'name' => 'Semir Ban', 'price' => 10000, 'duration_minutes' => 5, 'vehicle_type' => 'all', 'points_earned' => 0, 'is_active' => true, 'sort_order' => 1]);
        Service::create(['service_category_id' => $addon->id, 'name' => 'Pewangi Kabin', 'price' => 15000, 'duration_minutes' => 5, 'vehicle_type' => 'all', 'points_earned' => 0, 'is_active' => true, 'sort_order' => 2]);
        Service::create(['service_category_id' => $addon->id, 'name' => 'Vakum Interior', 'price' => 25000, 'duration_minutes' => 15, 'vehicle_type' => 'all', 'points_earned' => 1, 'is_active' => true, 'sort_order' => 3]);
    }
}