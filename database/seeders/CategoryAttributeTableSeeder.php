<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryAttributeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category_attributes = [
            [
                'id' => 1,
                'category_id' => 1,
                'attribute_id' => 6,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'category_id' => 1,
                'attribute_id' => 7,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'category_id' => 1,
                'attribute_id' => 8,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 4,
                'category_id' => 1,
                'attribute_id' => 9,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 5,
                'category_id' => 2,
                'attribute_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 6,
                'category_id' => 2,
                'attribute_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 7,
                'category_id' => 2,
                'attribute_id' => 6,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 8,
                'category_id' => 3,
                'attribute_id' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 9,
                'category_id' => 3,
                'attribute_id' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('attribute_category')->insert($category_attributes);
    }
}
