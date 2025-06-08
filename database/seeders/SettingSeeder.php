<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::insert([
            'phone' => '+6281234567890',
            'email' => 'smkn@gmail.com',
            'maps' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d995.0679800875802!2d122.43708636961333!3d-3.9643091618544566!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2d98f500765fda6d%3A0x338dd27ceed96fe4!2sSMKN%206%20KONAWE!5e0!3m2!1sen!2sid!4v1749391390530!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
            'address' => 'Lasoso, Sampara, Konawe Regency, South East Sulawesi',
        ]);
    }
}
