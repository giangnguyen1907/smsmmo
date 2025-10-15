<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTbUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_type')->nullable();
            $table->boolean('email_verified')->default(false);
            $table->string('email_verification_code')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('is_super_user')->default(false);
            $table->string('avatar')->nullable();
            $table->date('birthday')->nullable();
            $table->string('sex')->nullable();
            $table->string('phone')->nullable();
            $table->integer('count_view_info')->default(0);
            $table->integer('country_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->json('json_params')->nullable();
            $table->json('json_profiles')->nullable();
            $table->foreignId('admin_updated_id')->nullable()->constrained('admins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'user_type',
                'email_verified',
                'email_verification_code',
                'status',
                'is_super_user',
                'avatar',
                'birthday',
                'sex',
                'phone',
                'count_view_info',
                'country_id',
                'city_id',
                'district_id',
                'json_params',
                'json_profiles'
            ]);
        });
    }
}
