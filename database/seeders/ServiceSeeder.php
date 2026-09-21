<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            'General Health',
            'Blood Tests',
            'Pathology',
            'Cardiology',
            'Diabetes Clinic',
            'Full Body Checkup',
        ];

        foreach ($departments as $dept) {
            Service::firstOrCreate(
                ['title' => $dept],
                [
                    'description' => $dept . ' department',
                    'status' => 'Published',
                    'slug' => Str::slug($dept),
                ]
            );
        }
    }
}
