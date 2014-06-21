<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ArticleContent extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ArticleContent', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('article_id');
			$table->integer('content_id');
			$table->integer('ordinal');
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
		Schema::drop('ArticleContent');
	}

}
