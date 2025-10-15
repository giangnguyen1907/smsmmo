<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPostCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_post_comment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->nullable()->constrained('tb_cms_posts');
            $table->string('member_name')->nullable();
            $table->string('email_user')->nullable();
            $table->text('content')->nullable();
            $table->integer('status')->nullable()->default('1')->comment('1: chờ duyệt');
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
        Schema::dropIfExists('tb_post_comment');
    }
}
