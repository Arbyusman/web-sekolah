<?php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Teknik Jaringan Komputer dan Telekomunikasi'],
            ['name' => 'Rekayasa Perangkat Lunak'],
            ['name' => 'Desain Komunikasi Visual'],
        ];

        Major::insert($data);
    }
}
