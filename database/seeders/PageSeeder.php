<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Page::updateOrCreate(
            ['slug' => 'blood-test-in-pune'],
            [
                'title' => 'Blood Test in Pune',
                'content' => '<h2>Complete Blood Test Services in Pune</h2>
                             <p>At Wellcare Labs, we provide comprehensive, certified pathology and blood testing services across Pune. From routine examinations to specialized diagnostics, our state-of-the-art laboratory guarantees accurate and prompt analysis.</p>
                             <blockquote>Your health is our priority. Get clinical-grade diagnostics with seamless home sample collection.</blockquote>
                             <h3>Why Choose Wellcare Labs?</h3>
                             <ul>
                                 <li><strong>NABL Compliant Standards:</strong> Quality-assured results using advanced automated diagnostic machinery.</li>
                                 <li><strong>Home Sample Collection:</strong> Our certified phlebotomists collect samples right from your doorstep at your convenience.</li>
                                 <li><strong>Fast Digital Reports:</strong> Verified PDF reports are shared within 12 to 24 hours via email and WhatsApp.</li>
                             </ul>
                             <h3>Key Health Profiles Available</h3>
                             <p>You can choose from our individual tests or comprehensive wellness packages:</p>
                             <ol>
                                 <li>Complete Blood Count (CBC)</li>
                                 <li>Thyroid Profile (T3, T4, TSH)</li>
                                 <li>Diabetes Screening (HbA1c & Fasting Blood Sugar)</li>
                                 <li>Kidney & Liver Function Tests</li>
                             </ol>',
                'meta_title' => 'Reliable Blood Test in Pune - Wellcare Labs',
                'meta_description' => 'Get accurate, affordable, and certified blood test services in Pune at Wellcare Labs. Book home sample collection today.',
                'status' => 'Published',
            ]
        );
    }
}
