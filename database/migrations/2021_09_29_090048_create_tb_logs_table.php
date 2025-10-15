<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_created_id')->nullable()->constrained('admins');
            $table->foreignId('admin_created_id')->nullable()->constrained('users');
            $table->text('url_referer')->nullable();
            $table->text('url')->nullable();
            $table->string('method')->nullable();
            $table->text('params')->nullable();
            $table->timestamp('logged_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_logs');
    }
}
