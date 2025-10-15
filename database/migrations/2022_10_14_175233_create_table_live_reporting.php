<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLiveReporting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_live_reporting', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taxonomy_id')->nullable()->constrained('tb_cms_taxonomys');
            $table->string('title');
            $table->string('url_part');
            $table->string('image')->nullable();
            $table->string('image_thumb')->nullable();
            $table->string('brief')->nullable();
            $table->longText('content')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('member')->nullable()->comment('Thành viên viết bài');
            $table->string('manage')->nullable()->comment('Người duyệt');
            $table->json('json_params')->nullable();
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
        Schema::dropIfExists('tb_live_reporting');
    }
}
