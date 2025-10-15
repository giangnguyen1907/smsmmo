<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbAdminMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_admin_menus', function (Blueprint $table) {
            $table->id();

            $table->integer('parent_id')->nullable();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('url_link')->nullable();
            $table->integer('iorder')->nullable();
            $table->string('status')->nullable()->default('active');
            $table->string('toolbar')->nullable();
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
        Schema::dropIfExists('tb_admin_menus');
    }
}