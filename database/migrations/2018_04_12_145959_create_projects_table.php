<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');

            $table->string('name');
            $table->string('language');
            $table->string('type')->nullable();

            $table->string('compiler_version')->nullable();
            $table->longText('code')->nullable();

            $table->longText('wat')->nullable(); // 只有wasm中有的数据
            $table->longText('abi')->nullable();
            $table->longText('nvm_byte_code')->nullable(); // 在wasm中存储wasm

            $table->string('info_name')->nullable();
            $table->string('info_version')->nullable();
            $table->string('info_author')->nullable();
            $table->string('info_email')->nullable();
            $table->string('info_desc')->nullable();

            $table->string('contract_hash')->nullable();

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
        Schema::dropIfExists('projects');
    }
}
