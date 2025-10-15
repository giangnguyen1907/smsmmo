<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableExportBook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_export_book', function (Blueprint $table) {
            $table->id();
			$table->string('code')->nullable();
			$table->integer('bill_id')->nullable();
			$table->integer('bookshop')->nullable();
			$table->integer('workshop')->nullable();
			$table->integer('customer_id')->nullable();
			$table->date('date_at')->nullable();
			$table->integer('payment')->nullable();
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
        Schema::dropIfExists('tb_export_book');
    }
}
