<?php

namespace Database\Seeders;

use App\Models\CustomerProfile;
use App\Models\PointHistory;
use App\Models\RewardClaim;
use App\Models\Service;
use App\Models\TransactionDetail;
use App\Models\TransactionHead;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // ══════════════════════════════════════
        // CUSTOMER 1: Budi - 12x cuci (punya reward)
        // ══════════════════════════════════════
        $user1 = User::updateOrCreate(
            ['email' => 'budi@washhub.id'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'phone' => '081234567001',
                'is_active' => true,
            ]
        );

        $profile1 = CustomerProfile::updateOrCreate(
            ['user_id' => $user1->id],
            [
                'phone' => '081234567001',
                'address' => 'Jl. Kartini No. 45, Cirebon',
                'gender' => 'male',
                'birth_date' => '1990-05-15',
                'member_code' => 'WH26030001',
                'total_points' => 18,
                'total_washes' => 12,
                'lifetime_points' => 22,
            ]
        );

        // Kendaraan Budi
        Vehicle::updateOrCreate(
            ['customer_profile_id' => $profile1->id, 'plate_number' => 'E 1234 AB'],
            ['vehicle_type' => 'mobil', 'brand' => 'Toyota', 'model' => 'Avanza', 'color' => 'Hitam', 'year' => 2022]
        );
        Vehicle::updateOrCreate(
            ['customer_profile_id' => $profile1->id, 'plate_number' => 'E 5678 CD'],
            ['vehicle_type' => 'motor', 'brand' => 'Honda', 'model' => 'Vario 160', 'color' => 'Merah', 'year' => 2023]
        );

        $cashier = User::where('role', 'admin')->first() ?? User::first();
        $services = Service::where('is_active', true)->get();

        if ($services->isEmpty()) {
            $this->command->warn('⚠ Service kosong! Jalankan ServiceSeeder dulu.');
            return;
        }

        // Transaksi Budi (12 transaksi)
        $this->createTransactions($profile1, $cashier, $services, 12);

        // Reward claim (10x cuci = 1 reward, sudah claim 1)
        RewardClaim::updateOrCreate(
            ['customer_profile_id' => $profile1->id, 'washes_at_claim' => 10],
            [
                'reward_type' => 'free_wash',
                'washes_required' => 10,
                'status' => 'claimed',
                'claimed_at' => now()->subDays(3),
            ]
        );

        // ══════════════════════════════════════
        // CUSTOMER 2: Sari - 7x cuci (belum dapat reward)
        // ══════════════════════════════════════
        $user2 = User::updateOrCreate(
            ['email' => 'sari@washhub.id'],
            [
                'name' => 'Sari Dewi',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'phone' => '081234567002',
                'is_active' => true,
            ]
        );

        $profile2 = CustomerProfile::updateOrCreate(
            ['user_id' => $user2->id],
            [
                'phone' => '081234567002',
                'address' => 'Jl. Siliwangi No. 88, Cirebon',
                'gender' => 'female',
                'birth_date' => '1995-08-22',
                'member_code' => 'WH26030002',
                'total_points' => 10,
                'total_washes' => 7,
                'lifetime_points' => 10,
            ]
        );

        Vehicle::updateOrCreate(
            ['customer_profile_id' => $profile2->id, 'plate_number' => 'E 9012 EF'],
            ['vehicle_type' => 'mobil', 'brand' => 'Honda', 'model' => 'Brio', 'color' => 'Putih', 'year' => 2023]
        );

        $this->createTransactions($profile2, $cashier, $services, 7);

        // ══════════════════════════════════════
        // CUSTOMER 3: Agus - 22x cuci (punya 2 reward, 1 sudah dipakai)
        // ══════════════════════════════════════
        $user3 = User::updateOrCreate(
            ['email' => 'agus@washhub.id'],
            [
                'name' => 'Agus Prasetyo',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'phone' => '081234567003',
                'is_active' => true,
            ]
        );

        $profile3 = CustomerProfile::updateOrCreate(
            ['user_id' => $user3->id],
            [
                'phone' => '081234567003',
                'address' => 'Jl. Pemuda No. 12, Cirebon',
                'gender' => 'male',
                'birth_date' => '1988-01-10',
                'member_code' => 'WH26030003',
                'total_points' => 35,
                'total_washes' => 22,
                'lifetime_points' => 45,
            ]
        );

        Vehicle::updateOrCreate(
            ['customer_profile_id' => $profile3->id, 'plate_number' => 'E 3456 GH'],
            ['vehicle_type' => 'suv', 'brand' => 'Toyota', 'model' => 'Fortuner', 'color' => 'Silver', 'year' => 2021]
        );
        Vehicle::updateOrCreate(
            ['customer_profile_id' => $profile3->id, 'plate_number' => 'E 7890 IJ'],
            ['vehicle_type' => 'mobil', 'brand' => 'Daihatsu', 'model' => 'Xenia', 'color' => 'Abu-Abu', 'year' => 2020]
        );

        $this->createTransactions($profile3, $cashier, $services, 22);

        // Reward Agus - 1 sudah dipakai, 1 masih available
        RewardClaim::updateOrCreate(
            ['customer_profile_id' => $profile3->id, 'washes_at_claim' => 10],
            [
                'reward_type' => 'free_wash',
                'washes_required' => 10,
                'status' => 'used',
                'claimed_at' => now()->subDays(30),
                'used_at' => now()->subDays(25),
            ]
        );
        RewardClaim::updateOrCreate(
            ['customer_profile_id' => $profile3->id, 'washes_at_claim' => 20],
            [
                'reward_type' => 'free_wash',
                'washes_required' => 10,
                'status' => 'claimed',
                'claimed_at' => now()->subDays(5),
            ]
        );

        // ══════════════════════════════════════
        // CUSTOMER 4: Rina - 3x cuci (baru daftar)
        // ══════════════════════════════════════
        $user4 = User::updateOrCreate(
            ['email' => 'rina@washhub.id'],
            [
                'name' => 'Rina Kartika',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'phone' => '081234567004',
                'is_active' => true,
            ]
        );

        $profile4 = CustomerProfile::updateOrCreate(
            ['user_id' => $user4->id],
            [
                'phone' => '081234567004',
                'address' => 'Jl. Sukalila No. 33, Cirebon',
                'gender' => 'female',
                'birth_date' => '1998-12-01',
                'member_code' => 'WH26030004',
                'total_points' => 4,
                'total_washes' => 3,
                'lifetime_points' => 4,
            ]
        );

        Vehicle::updateOrCreate(
            ['customer_profile_id' => $profile4->id, 'plate_number' => 'E 4321 KL'],
            ['vehicle_type' => 'motor', 'brand' => 'Yamaha', 'model' => 'NMAX', 'color' => 'Biru', 'year' => 2024]
        );

        $this->createTransactions($profile4, $cashier, $services, 3);

        // ══════════════════════════════════════
        // CUSTOMER 5: Dedi - 10x cuci (pas bisa claim)
        // ══════════════════════════════════════
        $user5 = User::updateOrCreate(
            ['email' => 'dedi@washhub.id'],
            [
                'name' => 'Dedi Kurniawan',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'phone' => '081234567005',
                'is_active' => true,
            ]
        );

        $profile5 = CustomerProfile::updateOrCreate(
            ['user_id' => $user5->id],
            [
                'phone' => '081234567005',
                'address' => 'Jl. Kesambi No. 77, Cirebon',
                'gender' => 'male',
                'birth_date' => '1992-03-20',
                'member_code' => 'WH26030005',
                'total_points' => 15,
                'total_washes' => 10,
                'lifetime_points' => 15,
            ]
        );

        Vehicle::updateOrCreate(
            ['customer_profile_id' => $profile5->id, 'plate_number' => 'E 6789 MN'],
            ['vehicle_type' => 'mobil', 'brand' => 'Suzuki', 'model' => 'Ertiga', 'color' => 'Putih', 'year' => 2022]
        );

        $this->createTransactions($profile5, $cashier, $services, 10);

        $this->command->info('✅ 5 Customer berhasil dibuat dengan data lengkap!');
        $this->command->newLine();
        $this->command->table(
            ['Email', 'Password', 'Nama', 'Cuci', 'Poin', 'Reward'],
            [
                ['budi@washhub.id', 'password123', 'Budi Santoso', '12x', '18', '1 claimed'],
                ['sari@washhub.id', 'password123', 'Sari Dewi', '7x', '10', '-'],
                ['agus@washhub.id', 'password123', 'Agus Prasetyo', '22x', '35', '1 used, 1 claimed'],
                ['rina@washhub.id', 'password123', 'Rina Kartika', '3x', '4', '-'],
                ['dedi@washhub.id', 'password123', 'Dedi Kurniawan', '10x', '15', 'Bisa claim!'],
            ]
        );
    }

    private function createTransactions(CustomerProfile $profile, User $cashier, $services, int $count): void
    {
        $vehicle = $profile->vehicles()->first();
        $paymentMethods = ['cash', 'debit', 'ewallet', 'transfer'];
        $washStatuses = ['done', 'picked_up'];

        for ($i = 0; $i < $count; $i++) {
            $date = now()->subDays($count - $i + rand(0, 2));
            $service = $services->random();
            $invoiceNum = 'INV-' . $date->format('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            // Cek apakah invoice sudah ada
            if (TransactionHead::where('invoice_number', $invoiceNum)->exists()) {
                $invoiceNum .= '-' . rand(10, 99);
            }

            $head = TransactionHead::create([
                'invoice_number' => $invoiceNum,
                'customer_profile_id' => $profile->id,
                'vehicle_id' => $vehicle?->id,
                'cashier_id' => $cashier->id,
                'plate_number' => $vehicle?->plate_number ?? 'E 0000 XX',
                'vehicle_type' => $vehicle?->vehicle_type ?? 'mobil',
                'transaction_date' => $date->toDateString(),
                'subtotal' => $service->price,
                'discount_amount' => 0,
                'discount_percent' => 0,
                'tax_amount' => 0,
                'grand_total' => $service->price,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'payment_status' => 'paid',
                'payment_amount' => $service->price,
                'change_amount' => 0,
                'points_earned' => $service->points_earned,
                'wash_status' => $washStatuses[array_rand($washStatuses)],
            ]);

            TransactionDetail::create([
                'transaction_head_id' => $head->id,
                'service_id' => $service->id,
                'service_name' => $service->name,
                'service_category' => $service->category->name ?? '',
                'unit_price' => $service->price,
                'qty' => 1,
                'discount' => 0,
                'subtotal' => $service->price,
            ]);

            PointHistory::create([
                'customer_profile_id' => $profile->id,
                'transaction_head_id' => $head->id,
                'type' => 'earned',
                'points' => $service->points_earned,
                'balance_after' => $profile->total_points,
                'description' => "Points dari {$service->name} #{$invoiceNum}",
            ]);
        }
    }
}