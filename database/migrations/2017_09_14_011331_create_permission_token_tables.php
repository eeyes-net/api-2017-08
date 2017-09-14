<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionTokenTables extends Migration
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

        $connection->create('tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token', 190)->unique()->comment('令牌');
            $table->string('name')->comment('名称');
            $table->text('description')->comment('说明');
            $table->dateTime('not_before')->comment('生效时间');
            $table->dateTime('not_after')->comment('过期时间');
            $table->timestamps();
        });

        $connection->create('role_token', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->integer('token_id')->unsigned();
            $table->foreign('token_id')->references('id')->on('tokens');
            $table->primary(['role_id', 'token_id']);
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
        $connection->dropIfExists('tokens');
        $connection->dropIfExists('role_token');
    }
}
