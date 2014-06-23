<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlemetadataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('article_metadata', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('article_id');
			$table->integer('metadata_id');
			$table->timestamps();
			//$table->foreign('article_id')->references('id')->on('article');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('article_metadata');
	}

}
