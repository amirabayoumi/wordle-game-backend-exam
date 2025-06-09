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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->date('date');
            $table->string('try1')->nullable();
            $table->string('try2')->nullable();
            $table->string('try3')->nullable();
            $table->string('try4')->nullable();
            $table->string('try5')->nullable();
            $table->string('try6')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
