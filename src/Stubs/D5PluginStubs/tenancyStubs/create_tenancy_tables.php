<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenancyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('tenancy.database.connection') ?: config('database.default');

        Schema::connection($connection)->create(config('tenancy.database.users_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 190)->unique();
            $table->string('password', 60);
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });
//
//        Schema::connection($connection)->create(config('tenancy.database.roles_table'), function (Blueprint $table) {
//            $table->increments('id');
//            $table->string('name', 50)->unique();
//            $table->string('slug', 50);
//            $table->timestamps();
//        });
//
//        Schema::connection($connection)->create(config('tenancy.database.permissions_table'), function (Blueprint $table) {
//            $table->increments('id');
//            $table->string('name', 50)->unique();
//            $table->string('slug', 50);
//            $table->string('http_method')->nullable();
//            $table->text('http_path');
//            $table->timestamps();
//        });
//
//        Schema::connection($connection)->create(config('tenancy.database.menu_table'), function (Blueprint $table) {
//            $table->increments('id');
//            $table->integer('parent_id')->default(0);
//            $table->integer('order')->default(0);
//            $table->string('title', 50);
//            $table->string('icon', 50);
//            $table->string('uri', 50)->nullable();
//            $table->string('permission')->nullable();
//            $table->timestamps();
//        });
//
//        Schema::connection($connection)->create(config('tenancy.database.role_users_table'), function (Blueprint $table) {
//            $table->integer('tenancy_role_id');
//            $table->integer('tenancy_user_id');
//            $table->index(['tenancy_role_id', 'tenancy_user_id'],"tenancy_role_user");
//            $table->timestamps();
//        });
//
//        Schema::connection($connection)->create(config('tenancy.database.role_permissions_table'), function (Blueprint $table) {
//            $table->integer('tenancy_role_id');
//            $table->integer('tenancy_permission_id');
//            $table->index(['tenancy_role_id', 'tenancy_permission_id'],"tenancy_role_permission");
//            $table->timestamps();
//        });
//
//        Schema::connection($connection)->create(config('tenancy.database.user_permissions_table'), function (Blueprint $table) {
//            $table->integer('tenancy_user_id');
//            $table->integer('tenancy_permission_id');
//            $table->index(['tenancy_user_id', 'tenancy_permission_id'],"tenancy_user_permission");
//            $table->timestamps();
//        });
//
//        Schema::connection($connection)->create(config('tenancy.database.role_menu_table'), function (Blueprint $table) {
//            $table->integer('tenancy_role_id');
//            $table->integer('tenancy_menu_id');
//            $table->index(['tenancy_role_id', 'tenancy_menu_id'],"tenancy_role_menu");
//            $table->timestamps();
//        });
//
//        Schema::connection($connection)->create(config('tenancy.database.operation_log_table'), function (Blueprint $table) {
//            $table->increments('id');
//            $table->integer('tenancy_user_id');
//            $table->string('path');
//            $table->string('method', 10);
//            $table->string('ip');
//            $table->text('input');
//            $table->index('tenancy_user_id');
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $connection = config('tenancy.database.connection') ?: config('database.default');

        Schema::connection($connection)->dropIfExists(config('tenancy.database.users_table'));
//        Schema::connection($connection)->dropIfExists(config('tenancy.database.roles_table'));
//        Schema::connection($connection)->dropIfExists(config('tenancy.database.permissions_table'));
//        Schema::connection($connection)->dropIfExists(config('tenancy.database.menu_table'));
//        Schema::connection($connection)->dropIfExists(config('tenancy.database.user_permissions_table'));
//        Schema::connection($connection)->dropIfExists(config('tenancy.database.role_users_table'));
//        Schema::connection($connection)->dropIfExists(config('tenancy.database.role_permissions_table'));
//        Schema::connection($connection)->dropIfExists(config('tenancy.database.role_menu_table'));
//        Schema::connection($connection)->dropIfExists(config('tenancy.database.operation_log_table'));
    }
}
