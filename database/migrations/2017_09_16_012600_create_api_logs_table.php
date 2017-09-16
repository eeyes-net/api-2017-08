<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path')->comment('路径');
            $table->string('method', 10)->comment('HTTP方法');
            $table->string('ip', 15)->comment('IP');
            $table->text('user_agent')->comment('User Agent');
            $table->text('query')->comment('Query string');
            $table->text('body')->comment('POST body');
            $table->integer('response_length')->comment('返回内容总长度');
            $table->integer('response_code')->comment('返回代码');
            $table->text('response_msg')->comment('返回提示信息');
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
        Schema::dropIfExists('api_logs');
    }
}
