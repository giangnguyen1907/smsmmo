<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatedTableTbPostHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::create('tb_post_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->nullable()->constrained('tb_cms_posts');
            $table->foreignId('taxonomy_id')->nullable()->constrained('tb_cms_taxonomys');
            $table->string('title');
            $table->string('is_type')->nullable()->default('post');
            $table->json('json_params')->nullable();
            $table->string('image')->nullable();
            $table->string('image_thumb')->nullable();
            $table->integer('count_visited')->default(0);
            $table->integer('iorder')->nullable();
            $table->string('status')->default('active');
            $table->string('author')->nullable();
            $table->string('url_coppy')->nullable();
            $table->string('url_part')->nullable();
            $table->string('torder')->nullable();
            $table->string('number_comment')->nullable();
            $table->string('number_like')->nullable();
            $table->string('number_view')->nullable();
            $table->string('view_ngay')->nullable();
            $table->string('view_tuan')->nullable();
            $table->string('view_thang')->nullable();
            $table->string('nhuanbut')->nullable();
            $table->string('aproved_date')->nullable();
            $table->string('rating')->nullable();
            $table->string('comment_count')->nullable();   
            $table->string('category')->nullable();   
            $table->string('cms_tag')->nullable();
            $table->string('new_position')->nullable();
            $table->string('relation')->nullable();
            $table->string('comment_status')->nullable();
            $table->string('user_aproved')->nullable();
            $table->string('aproved_comment')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('visited_count')->nullable();
            $table->foreignId('admin_created_id')->nullable()->constrained('admins');
            $table->foreignId('admin_updated_id')->nullable()->constrained('admins');
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_post_history');
    }
}
