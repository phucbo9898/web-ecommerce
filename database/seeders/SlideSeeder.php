<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('slides')->insert(
            [
                [
                    'uuid' => Str::uuid(),
                    'name' => 'Slide 1',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/kltn-1dd42.appspot.com/o/image-slide%2Fbanner1.png?alt=media&token=de7ce92b-afbc-49d0-aa84-91333dc0c976',
                    'url' => '',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'uuid' => Str::uuid(),
                    'name' => 'Slide 2',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/kltn-1dd42.appspot.com/o/image-slide%2Fbanner2.png?alt=media&token=e1007095-a209-4129-a70f-5e43780ea897',
                    'url' => '',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'uuid' => Str::uuid(),
                    'name' => 'Slide 3',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/kltn-1dd42.appspot.com/o/image-slide%2Fbanner5.jpg?alt=media&token=e587033c-e39a-4001-9c33-294593cb2dba',
                    'url' => '',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'uuid' => Str::uuid(),
                    'name' => 'Slide 4',
                    'image' => 'https://firebasestorage.googleapis.com/v0/b/kltn-1dd42.appspot.com/o/image-slide%2Fbanner6.jpg?alt=media&token=0184d049-0283-4514-89bf-a2cb16a91bdc',
                    'url' => '',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]
        );
    }
}
