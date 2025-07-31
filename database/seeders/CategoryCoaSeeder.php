<?php

namespace Database\Seeders;

use App\Models\CategoryCoa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryCoaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
          ['name' => 'Salary'],
          ['name' => 'Other Income'],
          ['name' => 'Family Expense'],
          ['name' => 'Transport Expense'],
          ['name' => 'Meal Expense'],
        ];

        CategoryCoa::insert($data);
    }
}
