<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_clicks', function (Blueprint $table) {
            $table->id();

            $table->integer('product_id')->nullable();
            $table->string('ali_id')->nullable();

            $table->timestamp('transition_at');
            $table->text('user_agent')->nullable();
            $table->string('user_ip')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_content')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('referer')->nullable();
            $table->string('erid')->nullable();
            $table->string('user_ulid')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_clicks');
    }
};
