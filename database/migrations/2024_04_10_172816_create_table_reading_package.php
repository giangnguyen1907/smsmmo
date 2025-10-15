<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableReadingPackage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_reading_package', function (Blueprint $table) {
            $table->id();
			$table->string('title')->nullable();
			$table->integer('is_type')->nullable();
			$table->integer('category')->nullable();
			$table->string('image')->nullable();
			$table->integer('status')->nullable();
			$table->integer('price')->nullable();
			$table->boolean('is_checkip')->nullable();
			$table->boolean('is_ebook')->nullable();
			$table->boolean('is_audio')->nullable();
			$table->boolean('is_video')->nullable();
			$table->integer('number_day')->nullable();
			$table->integer('total_price')->nullable();
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
        Schema::dropIfExists('tb_reading_package');
    }
}
