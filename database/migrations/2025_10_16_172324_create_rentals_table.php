<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
       Schema::create('rentals', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');
    $table->unsignedBigInteger('phone_number_id');
    $table->unsignedBigInteger('service_id');
    $table->timestamp('start_time')->useCurrent();
    $table->timestamp('end_time')->nullable();
    $table->enum('status', ['pending', 'active', 'expired', 'error'])->default('pending');
    $table->decimal('cost', 15, 2);
    $table->text('error_message')->nullable();
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
}
