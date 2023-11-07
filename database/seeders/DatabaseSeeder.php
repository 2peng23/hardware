<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'User',
            'status' => 'active',
            'usertype' => 0,
            'designation' => 'Main Office',
            'email' => 'user@user.com',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'status' => 'active',
            'usertype' => 1,
            'email' => 'admin@admin.com',
        ]);
        \App\Models\Product::create([
            'item_id' => 0001,
            'name' => 'Paint',
            'price' => 50,
            'category' => 'Electrical',
        ]);
        \App\Models\Category::create([
            'name' => 'Electrical',
            'image' => '1699357859.jfif',
        ]);
    }
}
