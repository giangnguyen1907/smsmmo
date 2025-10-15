<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnPostComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_post_comment', function (Blueprint $table) {
            //
            $table->string('image_comment')->nullable();
            $table->dropForeign('tb_post_comment_admin_created_id_foreign');
            $table->dropColumn('admin_created_id');
            $table->foreignId('user_id')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_post_comment', function (Blueprint $table) {
            //
        });
    }
}
