/**
 * Backbone Models
 */

AppMain = (function(Backbone, $){

	return Backbone.Model.extend({

		initialize: function(){
			console.log("Initialize DOuglas.");
        },
		
		//Tree Open and Closed Icons
		closedIcon: "glyphicon-chevron-right",
		openIcon: "glyphicon-chevron-down",
		
		//Wait Indicator
		waitIcon: "glyphicon-repeat",
		waitText: "Loading...",
		showWait: function(){
			this.waitIndicator.activate();
		},
		hideWait: function(){
			this.waitIndicator.deactivate();
		},

		//Messages
		showMessage: function () {
			this.messageView.render();
		},
		setMessage: function(message,severity){
			this.message.severity = severity;
			this.message.message = message;
		},

		//SideBar
		//Add SideBar
		//Toggle SideBar

		//Tree
		//Open Branch
		//Load Branch

		//User Preferences
		//Load User Preferences
		//Save User Preferences
		age: function () {
				return (new Date() - this.get('dob'))
		},
	});
})(Backbone, jQuery);

Message = (function(Backbone, $){
	return Backbone.Model.extend({
				
		title: "Message title",
		message: "Message...",
		severity: "primary", //primary,success,info,warning,danger

        initialize: function(){
        }
	});
})(Backbone, jQuery);

Article = (function(Backbone, $){
	return Backbone.Model.extend({

		id: "",	
		title: "",
		path: "",
		safePath: "",
		data_level: "",
		content: "",

        initialize: function(){
        },
        setContent: function(){
        },
	});
})(Backbone, jQuery);

ArticleTree = (function(Backbone, $){
	return Backbone.Model.extend({

        initialize: function(){
        	douglas.articleList = new ArticleCollection();
        },
        getChildren: function(path,data_level){
	    	douglas.showWait();
			var parentSafePath = path.replace(new RegExp("/", 'g'), "_");
			$.ajax({
				type: "GET",
				url: "article/index/AJAX",
				data: { currentPath: path, dataLevel: data_level }
			})
			.done(function( data ) {
				$.each(data.articleRecords,function(index,article){
					var safePath = article.path;
					safePath = safePath.replace(new RegExp("/", 'g'), "_");
					var currArticle = new Article({ 
						id: article.id,
						title: article.title,
						path: article.path,
						safePath: safePath,
						data_level: article.data_level,
					});
					douglas.articleList.add(currArticle);
				});
				douglas.hideWait();
				return data;
			});
	    }
	});
})(Backbone, jQuery);

/**
 * Backbone Collections
 */

var ArticleCollection = Backbone.Collection.extend({
  model: Article,
});

/**
 * Backbone Views
 */

MessageView = (function(Backbone, $){
	return Backbone.View.extend({

		el: "#pageAlert",
	    initialize: function(){
		},
	    render: function(){
	        this.$el.attr("class", "");
 			this.$el.empty().append(this.model.message);
 			this.$el.addClass("bg-"+this.model.severity);

 			this.$el.fadeIn("fast", function() {
      			// Animation complete.
      		});
 			this.$el.fadeOut(3600, function() {
      			// Animation complete.
      		});
	        return this;
	    }
	});
})(Backbone, jQuery);

WaitView = (function(Backbone, $){
	return Backbone.View.extend({

		el: "#pageFooterWaitIcon",

	    initialize: function(){
		},
	    activate: function(){
	        this.$el.addClass(douglas.waitIcon).toggleClass('hidden');
	        $("#pageFooterWaitText").text(douglas.waitText).toggleClass('hidden');
	        return this;
	    },
	    deactivate: function(){
	    	this.$el.toggleClass('hidden');
	    	$("#pageFooterWaitText").toggleClass('hidden');
	    	return this;
	    }
	});
})(Backbone, jQuery);

ArticleTreeView = (function(Backbone, $){
	return Backbone.View.extend({

		el: "#sideBar",
	    initialize: function(){
	    	//Get Root Elements from DB
	    	//douglas.artliceList = new ArticleCollection();
	    	//douglas.articleTree.getChildren("/","2");
		},
	    render: function(){

	    	/* //Using Templates
	    	var templateSrc = "<% _.each(articleList, function(article) { %><tr><td><%= article.title %></td><td><%= article.safePath %></td><td><%= article.data_level %></td></tr><% }); %>";

			var template = _.template(templateSrc, {
				articleList: douglas.artliceList.toJSON()
			});
			this.$el.html(template);
			*/

			//Using JS and JQuery
			resultHTML = "";
			var mainDiv = document.createElement('span');
			
			$.each(douglas.articleList.toJSON(),function(index,article){
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

                mainDiv.appendChild(outerDiv);
                mainDiv.appendChild(innerDiv);
			});
			$("#articleTreeContent").html(mainDiv);

	        return this;
	    },
	    events: {
            "click [data-toggle=sideBar]": "toggleTree"
        },
	    toggleTree: function(){
	    	this.render();
			$('.sidebar').toggleClass('active');
			$('.sideBarButton').toggleClass('active');

	    }
	});
})(Backbone, jQuery);

/**
 * Run Douglas
 */

var douglas = new AppMain();

$(function(){
	
	douglas.message = new Message();
	douglas.messageView = new MessageView({
		model : douglas.message
	});
	douglas.waitIndicator = new WaitView();
	douglas.articleTree = new ArticleTree();
	douglas.articleTree.getChildren("/","2");

	douglas.articleTreeView = new ArticleTreeView();

});