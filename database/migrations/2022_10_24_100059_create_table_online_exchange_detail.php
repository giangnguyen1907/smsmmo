<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOnlineExchangeDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_online_exchange_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exchange_id')->nullable()->constrained('tb_online_exchange');
            $table->foreignId('experts_id')->nullable()->constrained('tb_experts')->comment('Chuyên gia trả lời');
            $table->integer('parent_id')->nullable()->comment('Trả lời bình luận');
            $table->longText('content')->nullable();
            $table->string('status')->default('waiting');
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
        Schema::dropIfExists('tb_online_exchange_detail');
    }
}
