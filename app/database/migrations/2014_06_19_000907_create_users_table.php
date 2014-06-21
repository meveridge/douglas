<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		   Schema::create('users', function(Blueprint $table) {
                //$table->increments('id');
                $table->string('id', 36)->primary();
								$table->string('username', 16);
								$table->string('fullname', 36);
								$table->string('email', 24);
                $table->string('password', 64);
                $table->tinyInteger('isAdmin');
								$table->text('remember_token')->nullable();
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
		   Schema::drop('users');
	}

}
