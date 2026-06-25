<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicines = [
            ['name' => 'Paracetamol 500mg', 'price' => 5000],
            ['name' => 'Amoxicillin 500mg', 'price' => 12000],
            ['name' => 'Cefixime 200mg', 'price' => 25000],
            ['name' => 'Omeprazole 20mg', 'price' => 10000],
            ['name' => 'Antasida DOEN', 'price' => 8000],
            ['name' => 'CTM 4mg', 'price' => 3000],
            ['name' => 'Ibuprofen 400mg', 'price' => 7000],
            ['name' => 'Vitamin C 500mg', 'price' => 6000],
            ['name' => 'Amlodipine 5mg', 'price' => 15000],
            ['name' => 'Metformin 500mg', 'price' => 12000],
        ];

        foreach ($medicines as $medicine) {
            Medicine::updateOrCreate(
                ['name' => $medicine['name']],
                [
                    'price' => $medicine['price'],
                    'created_by' => 1,
                ]
            );
        }
    }
}
