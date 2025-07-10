<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivityCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Academic',
            ],
            [
                'name' => 'Cultural',
            ],
            [
                'name' => 'Sports',
            ],
            [
                'name' => 'Social',
            ],
            [
                'name' => 'Environmental',
            ],
            [
                'name' => 'Health & Wellness',
            ],
        ];
        ActivityCategory::insert($data);
    }
}
