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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('value')->nullable();
            $table->integer('file_id')->nullable();
            $table->string('slug')->nullable();
            $table->string('link')->nullable();
            $table->string('alias')->nullable();
            $table->string('lang_hash')->nullable();
            $table->integer('sort')->nullable();
            $table->integer('lang')->nullable();
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
        Schema::dropIfExists('settings');
    }
};
