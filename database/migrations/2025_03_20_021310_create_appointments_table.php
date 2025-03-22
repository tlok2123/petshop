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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('pet_id')->constrained('pets');
            $table->foreignId('services_id')->constrained('services');
            $table->dateTime('date');
            $table->unsignedSmallInteger('status')->comment('1: Đang xử lý; 2: Đã liên hệ; 3: Đã xác nhận; 4: Đã hoàn thành');
            $table->text('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
