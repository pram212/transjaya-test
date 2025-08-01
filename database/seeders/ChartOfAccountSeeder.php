<?php

namespace Database\Seeders;

use App\Models\CategoryCoa;
use App\Models\ChartOfAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChartOfAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'type' => 'Pendapatan',
                'code' => '401',
                'name' => 'Gaji Karyawan',
                'category_coa_id' => CategoryCoa::where('name', 'Salary')->first()->id,
            ],
            [
                'type' => 'Pendapatan',
                'code' => '402',
                'name' => 'Gaji Ketua MPR',
                'category_coa_id' => CategoryCoa::where('name', 'Salary')->first()->id,
            ],
            [
                'type' => 'Pendapatan',
                'code' => '403',
                'name' => 'Profit Trading',
                'category_coa_id' => CategoryCoa::where('name', 'Other Income')->first()->id,
            ],
            [
                'type' => 'Beban',
                'code' => '601',
                'name' => 'Biaya Sekolah',
                'category_coa_id' => CategoryCoa::where('name', 'Family Expense')->first()->id,
            ],
            [
                'type' => 'Beban',
                'code' => '602',
                'name' => 'Bensin',
                'category_coa_id' => CategoryCoa::where('name', 'Transport Expense')->first()->id,
            ],
            [
                'type' => 'Beban',
                'code' => '603',
                'name' => 'Parkir',
                'category_coa_id' => CategoryCoa::where('name', 'Transport Expense')->first()->id,
            ],
            [
                'type' => 'Beban',
                'code' => '604',
                'name' => 'Makan Siang',
                'category_coa_id' => CategoryCoa::where('name', 'Meal Expense')->first()->id,
            ],
            [
                'type' => 'Beban',
                'code' => '605',
                'name' => 'Makanan Pokok Bulanan',
                'category_coa_id' => CategoryCoa::where('name', 'Meal Expense')->first()->id,
            ],
        ];

        ChartOfAccount::insert($data);
    }
}
