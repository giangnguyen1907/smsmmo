<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_cms_posts', function (Blueprint $table) {
            $table->string('author')->nullable();
            $table->string('url_coppy')->nullable();
            $table->string('url_part')->nullable();
            $table->integer('torder')->nullable();
            $table->integer('number_like')->nullable();
            $table->integer('number_comment')->nullable();
            $table->integer('number_view')->nullable();
            $table->integer('view_ngay')->nullable();
            $table->integer('view_tuan')->nullable();
            $table->integer('view_thang')->nullable();
            $table->integer('nhuanbut')->nullable();
            $table->integer('aproved_date')->nullable();
            $table->integer('rating')->nullable();
            $table->integer('comment_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_cms_posts', function (Blueprint $table) {
            //
        });
    }
}
