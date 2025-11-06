<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AttributeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'id' => 1,
                'uuid' => Str::uuid(),
                'name' => 'Bộ nhớ Ram',
                'type' => 'select',
                'value' => '2GB; 4GB; 6GB; 8GB; 16GB; 32GB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'uuid' => Str::uuid(),
                'name' => 'Độ phân giải',
                'type' => 'text',
                'value' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'uuid' => Str::uuid(),
                'name' => 'Kích thước',
                'type' => 'text',
                'value' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'uuid' => Str::uuid(),
                'name' => 'Socket',
                'type' => 'text',
                'value' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'uuid' => Str::uuid(),
                'name' => 'Khe cắm ram',
                'type' => 'text',
                'value' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'uuid' => Str::uuid(),
                'name' => 'Nhà sản xuất',
                'type' => 'text',
                'value' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'uuid' => Str::uuid(),
                'name' => 'Tốc độ xử lý',
                'type' => 'text',
                'value' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'uuid' => Str::uuid(),
                'name' => 'Số luồng',
                'type' => 'number',
                'value' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'uuid' => Str::uuid(),
                'name' => 'Số nhân',
                'type' => 'number',
                'value' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Attribute::insert($attributes);
    }
}
