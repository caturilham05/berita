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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('tag_ids')->nullable();
            $table->string('cat_ids')->nullable();
            $table->string('title')->nullable();
            $table->string('intro')->nullable();
            $table->text('image')->nullable();
            $table->text('image_thumb')->nullable();
            $table->text('images')->nullable();
            $table->text('content')->nullable();
            $table->tinyInteger('is_active')->nullable();
            $table->text('url')->nullable();
            $table->date('ondate');
            $table->bigInteger('timestamp')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('contents');
    }
};
