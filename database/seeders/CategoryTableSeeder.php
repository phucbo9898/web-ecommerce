<?php

namespace Database\Seeders;

use App\Enums\PublishEnum;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'id' => 1,
                'uuid' => Str::uuid(),
                'name' => 'CPU - Bộ vi xử lý',
                'status' => PublishEnum::PUBLISHED,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'uuid' => Str::uuid(),
                'name' => 'VGA - Card màn hình',
                'status' => PublishEnum::PUBLISHED,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'uuid' => Str::uuid(),
                'name' => 'Mainbroad - Bo mạch chủ',
                'status' => PublishEnum::PUBLISHED,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 4,
                'uuid' => Str::uuid(),
                'name' => 'RAM - Bộ nhớ',
                'status' => PublishEnum::PUBLISHED,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 5,
                'uuid' => Str::uuid(),
                'name' => 'PSU - Nguồn máy tính',
                'status' => PublishEnum::PUBLISHED,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 6,
                'uuid' => Str::uuid(),
                'name' => 'CPU - Bộ vi xử lý',
                'status' => PublishEnum::PUBLISHED,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 7,
                'uuid' => Str::uuid(),
                'name' => 'Tai nghe',
                'status' => PublishEnum::PUBLISHED,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 8,
                'uuid' => Str::uuid(),
                'name' => 'Chuột - Bàn phím',
                'status' => PublishEnum::PUBLISHED,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        Category::insert($categories);
    }
}
