<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::insert("INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
        (1, 'jabung', 'dsjabung@gmail.com', NULL, '\$2y\$12\$DHbNZtmYPxv4jDkY9VlDGuKs99dMC56GQrGb47W4EEJXbMK2/57aa', NULL, '2024-09-23 19:12:54', '2024-09-23 19:12:54');");
    }
}
