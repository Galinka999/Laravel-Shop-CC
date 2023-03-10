<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('text')->nullable()->fulltext();

            $table->fullText('title');
        });
    }

    public function down(): void
    {
        if(!app()->isProduction()) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('text');
                $table->dropFullText('products_title_fulltext');
            });
        }
    }
};
