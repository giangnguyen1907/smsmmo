<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_products', function (Blueprint $table) {
            $table->id();
			$table->foreignId('taxonomy_id')->nullable()->constrained('tb_cms_taxonomys');
			$table->string('title')->nullable();
			$table->string('alias')->nullable();
			$table->string('gia')->nullable();
			$table->string('giakm')->nullable();
			$table->string('mota')->nullable()->comment('Mô tả');
			$table->longText('chitiet')->nullable()->comment('Nội dung');
			$table->longText('diemban')->nullable()->comment('Điểm bán');
			$table->string('giayto')->nullable()->comment('Giấy tờ sản phẩm');
			$table->string('hienthi')->nullable()->comment('Vị trí hiển thị sản phẩm');
			$table->string('image')->nullable();
			$table->string('image_thumb')->nullable();
			$table->integer('view')->default(0)->comment('Lượt xem');
            $table->integer('iorder')->nullable();
			$table->integer('tinhtrang')->default('1');
			$table->integer('status')->default('1');
			$table->string('meta_title')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->string('meta_description')->nullable();
			$table->foreignId('admin_created_id')->nullable()->constrained('admins');
            $table->foreignId('admin_updated_id')->nullable()->constrained('admins');
			$table->json('json_params')->nullable();
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
        Schema::dropIfExists('tb_products');
    }
}
