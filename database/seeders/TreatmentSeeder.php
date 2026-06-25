<?php

namespace Database\Seeders;

use App\Models\Treatment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $treatments = [
            [
                'name' => 'Konsultasi Dokter Umum',
                'price' => 150000,
                'duration' => 30,
            ],
            [
                'name' => 'Bekam',
                'price' => 250000,
                'duration' => 60,
            ],
            [
                'name' => 'Akupunktur',
                'price' => 300000,
                'duration' => 60,
            ],
            [
                'name' => 'Infus Vitamin C',
                'price' => 450000,
                'duration' => 90,
            ],
            [
                'name' => 'Terapi Ozon',
                'price' => 750000,
                'duration' => 120,
            ],
            [
                'name' => 'Pijat Refleksi',
                'price' => 200000,
                'duration' => 60,
            ],
            [
                'name' => 'Terapi Herbal',
                'price' => 175000,
                'duration' => 45,
            ],
            [
                'name' => 'Nebulizer',
                'price' => 100000,
                'duration' => 20,
            ],
        ];

        foreach ($treatments as $treatment) {
            Treatment::updateOrCreate(
                ['name' => $treatment['name']],
                [
                    'price' => $treatment['price'],
                    'duration' => $treatment['duration'],
                    'created_by' => 1,
                ]
            );
        }
    }
}
