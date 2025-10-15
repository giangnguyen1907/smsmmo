<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDocument extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_document', function (Blueprint $table) {
            $table->id();
			$table->string('title')->nullable();
			$table->string('image')->nullable();
			$table->string('filepdf')->nullable();
			$table->string('description')->nullable();
			$table->string('authors')->nullable();
			$table->string('categorys')->nullable();
			$table->string('tags')->nullable();
			$table->integer('is_public')->default('1');
			$table->integer('status')->default('1');
			$table->integer('iorder')->default(0);
			$table->integer('view')->default(0);
			$table->integer('download')->default(0);
			$table->integer('number_page')->default(0);
			$table->integer('limit_page')->default(1);
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
        Schema::dropIfExists('tb_document');
    }
}
