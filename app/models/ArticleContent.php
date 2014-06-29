<?php

class ArticleContent extends Eloquent {

    protected $table = 'article_content';

    //protected $fillable = array('title','path','data_level');

    public function Content(){
        return $this->hasOne('Content');
    }

}