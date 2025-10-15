<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('users');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->text('customer_note')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('tb_cms_taxonomys');
            $table->foreignId('doctor_id')->nullable()->constrained('tb_cms_posts');
            $table->date('booking_date')->nullable();
            $table->string('booking_time')->nullable();
            $table->text('admin_note')->nullable();
            $table->json('json_params')->nullable();
            $table->string('status')->default('new');
            $table->foreignId('admin_created_id')->nullable()->constrained('admins');
            $table->foreignId('admin_updated_id')->nullable()->constrained('admins');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_bookings');
    }
}
