<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableImportBookDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_import_book_detail', function (Blueprint $table) {
            $table->id();
			$table->integer('import_id')->nullable();
			$table->integer('document_id')->nullable();
			$table->integer('quantity')->nullable();
			$table->integer('cost')->nullable();
			$table->integer('total')->nullable();
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
        Schema::dropIfExists('tb_import_book_detail');
    }
}
