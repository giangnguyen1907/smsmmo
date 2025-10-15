<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbBlockContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_block_contents', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->string('title');
            $table->text('brief')->nullable();
            $table->longText('content')->nullable();
            $table->string('block_code');
            $table->json('json_params')->nullable();
            $table->string('image')->nullable();
            $table->string('image_background')->nullable();
            $table->string('icon')->nullable();
            $table->string('url_link')->nullable();
            $table->string('url_link_title')->nullable();
            $table->string('position')->nullable();
            $table->string('system_code')->nullable();
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
        Schema::dropIfExists('tb_block_contents');
    }
}
