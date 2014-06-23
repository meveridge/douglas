<?php

class ArticleMetadata extends Eloquent {

    protected $table = 'article_metadata';

    //protected $fillable = array('title','path','data_level');

    public function Metadata(){
        return $this->hasOne('Metadata');
    }

}