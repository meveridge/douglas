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

	<div class="col-md-2">
		<ul class="list-unstyled" id="navigationTree">
			@foreach ($articleRecords as $article)

				<?php
				    //default this li element to show 
				    //and have a plus sign
				    //determine if it should be collapsed and minus later 
				    $visible = true;
				    $sign = $closedIcon;

				    //hide any level that is not the root by default
				    if($article->data_level <> 2) $visible = false;
				    if($visible == false){
				    	$liClass = "collapse";
				    }else{
				    	$liClass = "";
				    }

				    $readablePath = trim($article->path,"/");
				    $readablePath = substr($readablePath,strripos($readablePath,"/"));
				    $readablePath = trim($readablePath,"/");

				    //if this level is higher than last level, last id is this level's parent
				    if($article->data_level > $lastLevel){
				    	$parents[] = $article->id;
				    	$parentId = $lastId;
				    }elseif($article->data_level < $lastLevel){
				    	//if this level is lower than last level, this parent id is the difference of the level's position in the array
						while($article->data_level <= $lastLevel){
							$parentId = array_pop($parents);
							$lastLevel--;
						}
						$parentId = end($parents);
						$parents[] = $article->id;
				    }else{
				    	//if this level is the same as last level, parent id is the same as last record
				    }

				    $lastLevel = $article->data_level;
				    $lastId = $article->id;

					$i = 2;
    				$levelSpacingContents = "";
    				while($i < $article->data_level){
        				$levelSpacingContents = $levelSpacingContents."&nbsp;&nbsp;&nbsp;";
        				$i++;
        			}

    				$levelSpacingCode = "<span>$levelSpacingContents</span>";
				    $iconCode = "<span class='glyphicon $sign navTreeSpan' data_level='$article->data_level' id='$article->id'></span>";
				    $liCode = "<li class='$liClass' id='item_$article->id' parentId='$parentId'>$levelSpacingCode $iconCode ".HTML::linkAction('articleController@getEdit',$readablePath,array($article->id))."</li>";
				?>
				
				{{ $liCode }}
				
			@endforeach
		</ul>
	</div>

@stop

@section('pageContent')
    @parent
    <div class="col-md-10">
    {{ print_r($selectedArticle) }}
	</div>
@stop

@section('pageJSLoad')
    @parent
    <script>
    	$(document).ready(function(){
    		$("#navigationTree li span.navTreeSpan").click(function() {
    			var parentId = $(this).attr('id');
    			var closedIcon = $("#closedIcon").val();
    			var openIcon = $("#openIcon").val();

    			if($(this).hasClass(closedIcon)){
    				$("#navigationTree li[parentId*='"+parentId+"']").removeClass("collapse");
    				$(this).removeClass(closedIcon).addClass(openIcon);
    			}else{
    				$("#navigationTree li[parentId*='"+parentId+"']").addClass("collapse");
    				$(this).removeClass(openIcon).addClass(closedIcon);
    			}

    		});
		});

    </script>
@stop
