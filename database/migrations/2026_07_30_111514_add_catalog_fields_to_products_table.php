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
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->after('id')->constrained()->restrictOnDelete();
            $table->string('slug')->unique()->index()->after('name');
            $table->text('description')->nullable()->after('slug');
            $table->unsignedInteger('price_original')->nullable()->after('price');
            $table->string('sku', 50)->unique()->nullable()->after('price_original');
            $table->smallInteger('stock')->default(-1)->after('sku');
            $table->smallInteger('sort_order')->default(0)->after('stock');
            $table->boolean('is_featured')->default(false)->index()->after('is_active');
            $table->json('tags')->nullable()->after('is_featured');
            $table->json('allergens')->nullable()->after('tags');
            $table->smallInteger('calories')->nullable()->after('allergens');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
            $table->dropColumn([
                'slug',
                'description',
                'price_original',
                'sku',
                'stock',
                'sort_order',
                'is_featured',
                'tags',
                'allergens',
                'calories',
                'deleted_at',
            ]);
        });
    }
};
