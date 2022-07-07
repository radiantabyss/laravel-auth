<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(env('RA_AUTH_TABLE_NAME'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');
            $table->string('type');
            $table->string('name');
            $table->string('email', 100)->index()->nullable();
            $table->string('password')->nullable();
            $table->string('activation_code')->nullable();
            $table->string('reset_code')->nullable();
            $table->timestamp('reset_code_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
			$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
