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
    		<!--
            @foreach ($articleRecords as $article)
                <?php $safePath = str_replace("/", "_", $article->path); ?>
    			<div>
                    <span class="glyphicon {{$closedIcon}} treeBranchToggle" data-toggle="collapse" data-target="#{{$safePath}}" path="{{$article->path}}" data-level="{{$article->data_level}}"></span>
                        {{$article->title}}
                </div>
                <div id="{{$safePath}}" class="collapse"></div>
    		@endforeach
            -->
    </div>
    </div>

@stop

@section('pageContent')
    @parent
    <div id="articleContent" class="col-md-10">
    	@if (isset($selectedArticle))
    		<input type='hidden' id='currentPath' value='{{ $selectedArticle->path }}' />
    		{{ print_r($selectedArticle) }}
		@endif
	</div>
@stop

@section('pageJSLoad')
    @parent
    <script>
    /*
        function registerTreeBranchToggle(){

            $(".treeBranchToggle").click(douglas.clickEvent());
            */
/*
            $(".treeBranchToggle").click(function(){

                var path = $(this).attr('path');
                var data_level = parseInt($(this).attr('data-level')) + 1;

                if($(this).hasClass(douglas.closedIcon)){
                    $(this).removeClass(douglas.closedIcon).addClass(douglas.openIcon);
                    douglas.expandBranch(path,data_level);
                }else{
                    $(this).removeClass(douglas.openIcon).addClass(douglas.closedIcon);
                }
            })
*/
/*        }
    	$(document).ready(function(){
            registerTreeBranchToggle();
		});
*/
    </script>
    {{ HTML::script('deps/tinymce/tinymce.min.js'); }}
@stop
