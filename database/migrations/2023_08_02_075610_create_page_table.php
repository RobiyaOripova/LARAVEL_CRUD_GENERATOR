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
        Schema::create('page', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->text('description')->nullable();
            $table->integer('type')->nullable();
            $table->integer('file_id')->nullable();
            $table->integer('sort')->nullable();
            $table->string('documents')->nullable();
            $table->string('anons')->nullable();
            $table->text('content')->nullable();
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
        Schema::dropIfExists('page');
    }
};
