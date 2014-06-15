$(document)
	.ready(function() {
		$("#pageErrorAlert").fadeOut(3600, function() {
			// Animation complete.
			});
			$("#pageMessageAlert").fadeOut(3600, function() {
			// Animation complete.
			});
			$('[data-toggle=sideBar]').click(function () {
          $('.sidebar').toggleClass('active');
          $('.sideBarButton').toggleClass('active');
        });
		});

var AppMain = Backbone.Model.extend({
	
	closedIcon: "glyphicon-chevron-right",
	openIcon: "glyphicon-chevron-down",
	
	age: function () {
			return (new Date() - this.get('dob'))
	},

	clickEvent: function(){
		var path = $(this).attr('path');
        var data_level = parseInt($(this).attr('data-level')) + 1;

        if($(this).hasClass(douglas.closedIcon)){
            $(this).removeClass(douglas.closedIcon).addClass(douglas.openIcon);
            this.expandBranch(path,data_level);
        }else{
            $(this).removeClass(douglas.openIcon).addClass(douglas.closedIcon);
        }
	},

	expandBranch: function(path,data_level){
		$("#pageFooterSpinner").toggleClass('hidden');
		console.log("Path:"+path+", Level:"+data_level);
		var parentSafePath = path.replace(new RegExp("/", 'g'), "_");
		$.ajax({
				type: "GET",
				url: "http://localhost/douglas/public/article/index/AJAX",
				data: { currentPath: path, dataLevel: data_level }
		})

			.done(function( data ) {
			resultHTML = "";
			$.each(data.articleRecords,function(index,article){
				var safePath = article.path;
				safePath = safePath.replace(new RegExp("/", 'g'), "_");
				
				var outerDiv = document.createElement('div');

				var treeIcon = document.createElement('span');
				treeIcon.className = "glyphicon "+douglas.closedIcon+" treeBranchToggle";
				treeIcon.setAttribute('data-toggle', 'collapse');
				treeIcon.setAttribute('data-target', '#'+safePath);
				treeIcon.setAttribute('data-level', article.data_level);
				treeIcon.setAttribute('path', article.path);
				treeIcon.setAttribute('click','douglas.clickEvent()');
				
				outerDiv.appendChild(treeIcon);

				var titleText = document.createTextNode(article.title);
				outerDiv.appendChild(titleText);

                var innerDiv = document.createElement('div');
                innerDiv.setAttribute('id', safePath);
                innerDiv.className = "collapse";

                $("#"+parentSafePath).append(outerDiv);
                $("#"+parentSafePath).append(innerDiv);
			});
			//registerTreeBranchToggle();
			$("#pageFooterSpinner").toggleClass('hidden');
			});
	}
	})

	var douglas = new AppMain({ 
		name: 'Bob', 
		dob: new Date('21 Sept 1988')
	})

// Access the data
douglas.get('name')
//-> returns 'Bob'

// Observe the data for changes
// using the event methods that
// the model has inherited
douglas.on('change', function () {
  console.log('Something about this person changed')
})

// Mutate the data
douglas.set('name', 'Robert')
//-> outputs 'Something about this person changed'