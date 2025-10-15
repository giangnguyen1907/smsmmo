<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEbookPackage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_ebook_package', function (Blueprint $table) {
            $table->id();
			$table->string('title')->nullable();
			$table->integer('book_type')->default(0);
			$table->integer('time')->default(1);
			$table->integer('recipe')->default(1);
			$table->integer('price')->default(0);
			$table->integer('percent')->default(0);
			$table->integer('rounding')->default(0);
			$table->integer('min_price')->default(0);
			$table->integer('status')->default(1);
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
        Schema::dropIfExists('tb_ebook_package');
    }
}
