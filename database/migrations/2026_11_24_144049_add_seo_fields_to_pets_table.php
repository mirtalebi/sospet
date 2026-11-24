<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->string('seo_canonical')->nullable();
            $table->boolean('seo_noindex')->default(false);
            $table->string('og_image')->nullable();

            $table->index(['type', 'is_resolved', 'is_hidden']);
            $table->index('city');
        });

        DB::table('pets')
            ->select(['id', 'title'])
            ->orderBy('id')
            ->chunkById(200, function ($pets): void {
                foreach ($pets as $pet) {
                    $base = Str::slug((string) $pet->title) ?: 'pet';

                    DB::table('pets')
                        ->where('id', $pet->id)
                        ->update(['slug' => $base.'-'.$pet->id]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropIndex(['type', 'is_resolved', 'is_hidden']);
            $table->dropIndex(['city']);
            $table->dropColumn(['slug', 'seo_title', 'seo_description', 'seo_canonical', 'seo_noindex', 'og_image']);
        });
    }
};
