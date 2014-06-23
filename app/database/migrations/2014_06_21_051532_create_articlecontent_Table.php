<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlecontentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('article_content', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('article_id');
			$table->integer('content_id');
			$table->integer('ordinal');
			$table->timestamps();
			//$table->foreign('article_id')->references('id')->on('article');
			//$table->foreign('content_id')->references('id')->on('content');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('article_content');
	}

}
