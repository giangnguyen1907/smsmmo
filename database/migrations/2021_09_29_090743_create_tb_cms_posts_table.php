<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCmsPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_cms_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taxonomy_id')->nullable()->constrained('tb_cms_taxonomys');
            $table->string('is_type')->nullable()->default('post');
            $table->string('title');
            $table->json('json_params')->nullable();
            $table->string('image')->nullable();
            $table->string('image_thumb')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('count_visited')->default(0);
            $table->integer('iorder')->nullable();
            $table->string('status')->default('active');
			$table->string('meta_title')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->string('meta_description')->nullable();
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
        Schema::dropIfExists('tb_cms_posts');
    }

}
