<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionRbacTables extends Migration
{
    protected $connection = 'mysql_permission';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = Schema::connection($this->connection);

        $connection->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 190)->unique()->comment('用户名');
            $table->string('password', 255)->comment('密码');
            $table->string('name')->comment('姓名');
            $table->timestamps();
        });

        $connection->create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug', 190)->unique()->comment('代号');
            $table->text('name')->comment('名称');
            $table->timestamps();
        });

        $connection->create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug', 190)->unique()->comment('代号');
            $table->text('name')->comment('名称');
            $table->timestamps();
        });

        $connection->create('role_user', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->primary(['role_id', 'user_id']);
            $table->timestamps();
        });

        $connection->create('role_role', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->integer('extend_id')->unsigned();
            $table->foreign('extend_id')->references('id')->on('roles');
            $table->primary(['role_id', 'extend_id']);
            $table->timestamps();
        });

        $connection->create('permission_role', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->primary(['permission_id', 'role_id']);
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
        $connection = Schema::connection($this->connection);
        $connection->dropIfExists('users');
        $connection->dropIfExists('roles');
        $connection->dropIfExists('permissions');
        $connection->dropIfExists('role_user');
        $connection->dropIfExists('role_role');
        $connection->dropIfExists('permission_role');
    }
}
