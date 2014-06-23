<script type="text/template" id="treeTrunk">
	<% _.each(articleList, function(article) { %>
		<tr>
		<td><%= article.title %></td>
		<td><%= article.safePath %></td>
		<td><%= article.data_level %></td>
		</tr>
	<% }); %>
</script>

<script type="text/template" id="pageMessageAlert">
 	<%= title %>
 	<p class="bg-success"><%= message %></p>
</script>