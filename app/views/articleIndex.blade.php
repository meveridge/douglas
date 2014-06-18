@extends('layouts.master')

@section('pageSidebar')
    @parent

    <?php

    $closedIcon = "glyphicon-chevron-right";
    $openIcon = "glyphicon-chevron-down";
    $lastId = 0;
    $lastLevel = 0;
    $parentId = 0;
    $parents = array();
    
    ?>

    <input type="hidden" id= "closedIcon" value="{{ $closedIcon }}" />
    <input type="hidden" id= "openIcon" value="{{ $openIcon }}" />

    <div id="sideBar">
    <button type="button" class="btn btn-primary btn-xs sideBarButton" data-toggle="sideBar"><span class="glyphicon {{$closedIcon}}"></span></button>
    <div id="articleTree" class="sidebar tree">
      <div id="articleTreeHeader"><h1><small>SugarCRM Support</small></h1></div>
      <div id="articleTreeContent">
    		
    </div>
    </div>

@stop

@section('pageContent')
    @parent
    <div id="articleContent" class="col-md-10">
    	@if (isset($selectedArticle))
    		<input type='hidden' id='currentPath' value='{{ $selectedArticle->path }}' />
    		{{ print_r($selectedArticle) }}<br>
            <div class="editable">{{$articleContent['html']}}</div>
		@endif
	</div>
@stop

@section('pageJSLoad')
    @parent
    
    {{ HTML::script('deps/tinymce/tinymce.min.js'); }}
@stop
