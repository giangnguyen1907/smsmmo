<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentHistoriesTable  extends Migration
{
    public function up(): void
    {
        // Bảng quản lý sim đang cho thuê
        Schema::create('sims', function (Blueprint $table) {
            $table->id();
            $table->string('network')->index();      // Viettel, Mobifone,...
            $table->string('service')->index();      // Facebook, Zalo,...
            $table->string('number')->unique();      // Số thuê
            $table->unsignedInteger('price')->default(0);
            $table->enum('status', ['available', 'rented'])->default('available');
            $table->timestamps();
        });

        // Bảng thuê lại số cũ
        Schema::create('old_sims', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('network')->index();
            $table->string('service')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->enum('status', ['available', 'rented'])->default('available');
            $table->timestamps();
        });

        // Bảng lịch sử thuê sim
        Schema::create('rent_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('sim_id')->nullable()->constrained('sims')->onDelete('set null');
            $table->string('number');
            $table->string('network');
            $table->string('service')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->timestamp('rented_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->string('otp_code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rent_histories');
        Schema::dropIfExists('old_sims');
        Schema::dropIfExists('sims');
    }
};
