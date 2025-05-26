<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('ali_id')->index();
            $table->integer('alicat_id')->nullable();
            $table->text('ali_description')->nullable();
            $table->text('ali_properties')->nullable();
            $table->text('ali_chars')->nullable();
            $table->longText('ali_reviews')->nullable();

            $table->ulid('ulid')->index();

            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('slug')->index();

            $table->string('store_name')->nullable();
            $table->string('store_url')->nullable();
            $table->string('store_chat_url')->nullable();
            $table->string('store_rating')->nullable();
            $table->string('store_image')->nullable();

            $table->text('images')->nullable();

            $table->integer('price')->default(0)->nullable();
            $table->integer('price_old')->default(0)->nullable();
            $table->string('rating')->default(0)->nullable();
            $table->integer('sales')->default(0)->nullable();
            $table->integer('reviews')->default(0)->nullable();

            $table->integer('shows')->default(0);
            $table->integer('published')->index()->default(0);
            $table->integer('status')->index()->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
