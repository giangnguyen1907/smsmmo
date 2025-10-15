<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_transaction', function (Blueprint $table) {
            $table->id();
			$table->string('order_code')->nullable();
			$table->string('guest')->nullable();
			$table->integer('guest_id')->nullable();
			$table->string('transaction_no')->nullable();
			$table->string('response_code')->nullable();
			$table->date('date_at')->nullable();
			$table->integer('is_type')->nullable();
			$table->integer('status')->nullable();
			$table->string('content')->nullable();
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
        Schema::dropIfExists('tb_transaction');
    }
}
