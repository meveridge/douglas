<?php

class ArticleTableSeeder extends Seeder {

    public function run()
    {
        DB::table('articles')->delete();

        Article::create(array(
            'title' => 'SugarCRM Support',
            'path' => '/',
            'data_level' => '1',
        ));
    }
}
