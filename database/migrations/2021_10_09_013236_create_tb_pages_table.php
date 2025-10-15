<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_pages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable();;
            $table->text('keyword')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('route_name');
            $table->string('alias');
            $table->json('json_params')->nullable();
            $table->integer('iorder')->nullable();
            $table->string('status')->default('active');
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
        Schema::dropIfExists('tb_pages');
    }
}