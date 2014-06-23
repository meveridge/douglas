<?php

class Article extends Eloquent {

    protected $table = 'articles';

    protected $fillable = array('title','path','data_level');

	public function ArticleContent(){
        return $this->hasMany('ArticleContent');
    }

    public function ArticleMetadata(){
        return $this->hasMany('ArticleMetadata');
    }

}