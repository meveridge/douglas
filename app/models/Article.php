<?php

class Article extends Eloquent {

    protected $table = 'articles';

    protected $fillable = array('title','path','data_level');

}