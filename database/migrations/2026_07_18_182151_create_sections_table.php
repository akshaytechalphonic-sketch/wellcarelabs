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
        // 1. Create sections table if not exists
        if (!Schema::hasTable('sections')) {
            Schema::create('sections', function (Blueprint $table) {
                $table->id();
                $table->string('sectionable_type');
                $table->unsignedBigInteger('sectionable_id');
                $table->string('type');
                $table->longText('content');
                $table->integer('sort_order')->default(0);
                $table->tinyInteger('status')->default(1);
                $table->timestamps();

                $table->index(['sectionable_type', 'sectionable_id']);
            });
        }

        // 2. Migrate temporary content from dynamic_pages.meta_tags to sections
        if (Schema::hasTable('dynamic_pages')) {
            $pages = DB::table('dynamic_pages')->get();
            foreach ($pages as $p) {
                // If meta_tags contains HTML content (our temporary holder for original content)
                if (!empty($p->meta_tags) && strpos($p->meta_tags, '<') !== false) {
                    // Check if it already has a text section to avoid duplicate migration
                    $exists = DB::table('sections')
                        ->where('sectionable_type', 'App\Models\Page')
                        ->where('sectionable_id', $p->id)
                        ->where('type', 'text')
                        ->exists();

                    if (!$exists) {
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

                        // Clean up the temporary meta_tags holder
                        DB::table('dynamic_pages')
                            ->where('id', $p->id)
                            ->update(['meta_tags' => null]);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop since it might contain pre-existing polymorphic data
    }
};
