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
        Schema::create('post', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('description', 500)->nullable();
            $table->text('content')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->integer('popular')->default(0);
            $table->integer('type')->nullable();
            $table->integer('file_id')->nullable();
            $table->string('document_ids')->nullable();
            $table->string('category_ids')->nullable();
            $table->integer('video_id')->nullable();
            $table->integer('top')->default(0);
            $table->integer('views')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->integer('lang')->nullable();
            $table->string('lang_hash')->nullable();
            $table->integer('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post');
    }
};
