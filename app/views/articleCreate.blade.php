@extends('layouts.master')

@section('pageContent')

<div id="articleCreateModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Create Article</h4>
			</div>
			<div class="modal-body">
				
			<form id="createArticle" role="form" action="http://localhost/douglas/public/article/create" method="POST">
				<div class="form-group">
					<label for="inputArticleTitle">Title</label>
					<input type="text" class="form-control" id="inputArticleTitle" name="inputArticleTitle" placeholder="Article Title">
				</div>
				<div class="form-group">
					<label for="inputArticlePath">Path</label>
					<input type="text" class="form-control" id="inputArticlePath" name="inputArticlePath" placeholder="Article Path">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Create</button>
			</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@stop

@section('pageJSLoad')
@parent

<script>

$(document)
    .ready(function() {
		$('#articleCreateModal').modal('toggle');
	});
</script>
@stop