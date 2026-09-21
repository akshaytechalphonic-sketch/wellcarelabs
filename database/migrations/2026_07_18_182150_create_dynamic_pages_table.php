<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Capture existing pages data safely
        $existingPages = [];
        if (Schema::hasTable('pages')) {
            $existingPages = DB::table('pages')->get();
        }

        // 2. Ensure dynamic_pages exists and has required columns
        if (!Schema::hasTable('dynamic_pages')) {
            Schema::create('dynamic_pages', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->string('banner_image')->nullable();
                $table->string('image_alt')->nullable();
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->text('meta_tags')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        } else {
            try {
                DB::statement("ALTER TABLE `dynamic_pages` ADD PRIMARY KEY (`id`)");
            } catch (\Exception $e) {}
            try {
                DB::statement("ALTER TABLE `dynamic_pages` MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT");
            } catch (\Exception $e) {}

            Schema::table('dynamic_pages', function (Blueprint $table) {
                if (!Schema::hasColumn('dynamic_pages', 'banner_image')) {
                    $table->string('banner_image')->nullable()->after('slug');
                }
                if (!Schema::hasColumn('dynamic_pages', 'image_alt')) {
                    $table->string('image_alt')->nullable()->after('banner_image');
                }
                if (!Schema::hasColumn('dynamic_pages', 'meta_tags')) {
                    $table->text('meta_tags')->nullable()->after('meta_description');
                }
            });
        }

        // 3. Drop foreign key constraint on lab_tests pointing to pages
        if (Schema::hasTable('lab_tests')) {
            try {
                Schema::table('lab_tests', function (Blueprint $table) {
                    $table->dropForeign(['page_id']);
                });
            } catch (\Exception $e) {
                // Ignore if doesn't exist
            }
        }

        // 4. Copy pages table records to dynamic_pages
        foreach ($existingPages as $ep) {
            $exists = DB::table('dynamic_pages')->where('id', $ep->id)->exists();
            if (!$exists) {
                DB::table('dynamic_pages')->insert([
                    'id' => $ep->id,
                    'title' => $ep->title,
                    'slug' => $ep->slug,
                    'banner_image' => $ep->banner_image ?? null,
                    'image_alt' => $ep->image_alt ?? null,
                    'meta_title' => $ep->meta_title ?? null,
                    'meta_description' => $ep->meta_description ?? null,
                    'meta_tags' => $ep->meta_tags ?? $ep->content ?? null, // preserve content temporarily inside meta_tags
                    'status' => ($ep->status === 'Published') ? 1 : 0,
                    'created_at' => $ep->created_at,
                    'updated_at' => $ep->updated_at,
                ]);
            }
        }

        // If pages was already dropped and dynamic_pages doesn't have ID 3, seed it manually
        if (!DB::table('dynamic_pages')->where('id', 3)->exists()) {
            DB::table('dynamic_pages')->insert([
                'id' => 3,
                'title' => 'Blood Test in Pune',
                'slug' => 'blood-test-in-pune',
                'banner_image' => 'pages/TXWPaC0mick1Z3UHXg66oS87Y0nnW3yh6ocjrK0I.jpg',
                'image_alt' => 'image text',
                'meta_title' => 'Reliable Blood Test in Pune - Wellcare Labs',
                'meta_description' => 'Get accurate, affordable, and certified blood test services in Pune at Wellcare Labs. Book home sample collection today.',
                'meta_tags' => '<h2>Complete Blood Test Services in Pune</h2><p>At Wellcare Labs, we provide comprehensive, certified pathology and blood testing services across Pune. From routine examinations to specialized diagnostics, our state-of-the-art laboratory guarantees accurate and prompt analysis.</p><blockquote><p>Your health is our priority. Get clinical-grade diagnostics with seamless home sample collection.</p></blockquote><h3>&nbsp;Why Choose Wellcare Labs?</h3><ul><li><strong>NABL Compliant Standards:</strong> Quality-assured results using advanced automated diagnostic machinery.</li><li><strong>Home Sample Collection:</strong> Our certified phlebotomists collect samples right from your doorstep at your convenience.</li><li><strong>Fast Digital Reports:</strong> Verified PDF reports are shared within 12 to 24 hours via email and WhatsApp.</li></ul><h3>Key Health Profiles Available</h3><p>You can choose from our individual tests or comprehensive wellness packages:</p><ol><li>Complete Blood Count (CBC)</li><li>Thyroid Profile (T3, T4, TSH)</li><li>Diabetes Screening (HbA1c &amp; Fasting Blood Sugar)</li><li>Kidney &amp; Liver Function Tests</li></ol>',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4.5. Synchronize dynamic page contents into sections table
        $dynamicPages = DB::table('dynamic_pages')->get();
        foreach ($dynamicPages as $p) {
            if (!empty($p->meta_tags) && strpos($p->meta_tags, '<') !== false) {
                $sectionExists = DB::table('sections')
                    ->where('sectionable_type', 'App\Models\Page')
                    ->where('sectionable_id', $p->id)
                    ->where('type', 'text')
                    ->exists();

                if (!$sectionExists) {
                    DB::table('sections')->insert([
                        'sectionable_type' => 'App\Models\Page',
                        'sectionable_id' => $p->id,
                        'type' => 'text',
                        'content' => json_encode([
                            'title' => 'Main Content',
                            'body' => $p->meta_tags
                        ]),
                        'sort_order' => 0,
                        'status' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Clean up the temporary column holder
                    DB::table('dynamic_pages')
                        ->where('id', $p->id)
                        ->update(['meta_tags' => null]);
                }
            }
        }

        // 5. Drop old pages table
        Schema::dropIfExists('pages');

        // 6. Map foreign key constraint to dynamic_pages.id
        if (Schema::hasTable('lab_tests')) {
            Schema::table('lab_tests', function (Blueprint $table) {
                $table->foreign('page_id')->references('id')->on('dynamic_pages')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse mapping back to pages table if needed
        if (Schema::hasTable('lab_tests')) {
            Schema::table('lab_tests', function (Blueprint $table) {
                try {
                    $table->dropForeign(['page_id']);
                } catch (\Exception $e) {}
            });
        }

        Schema::dropIfExists('dynamic_pages');
    }
};
