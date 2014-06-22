/**
 * Backbone Models
 */

AppMain = (function(Backbone, $){

	return Backbone.Model.extend({

		initialize: function(){
			console.log("Initialize Douglas.");
        },

        public_path: "http://localhost/douglas/public/",
		
		//Tree Open and Closed Icons
		
		//octicon
		//closedIcon: "octicon octicon-chevron-right",
		//openIcon: "octicon octicon-chevron-down",

		//glyphicon
		closedIcon: "glyphicon glyphicon-chevron-right",
		openIcon: "glyphicon glyphicon-chevron-down",
		
		//Wait Indicator
		waitIcon: "octicon octicon-gear",
		//waitIcon: "glyphicon glyphicon-repeat",
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

		sanitizePath: function(path){
			if(path){
				var safePath = path.replace(new RegExp("/", 'g'), "_");
				safePath = safePath.replace(new RegExp("'", 'g'), "_");
				safePath = safePath.replace(new RegExp("\\.", 'g'), "_");
				return safePath;
			}else{
				return path;
			}
		},

		//SideBar
		addTreeSideBar: function(){
			//Needs work... throws JS errors if not on Article
			douglas.articleTreeView.render();
		},
		toggleTree: function(){
			douglas.articleTreeView.toggleTree();
		},
	    closeTree: function(){
	    	if($('.sidebar').hasClass('active')){
				this.toggleTree();
			}
	    },
		editArticleById: function(id){
			var continueOn = false;
			if(douglas.Article != undefined){
				if(douglas.Article.get('modified')==true){
					if( confirm( "Are you sure?" ) ) {
						continueOn = true;
					}else{
						continueOn = false;
					}
				}else{
					continueOn = true;
				}
			}else{
				continueOn = true;
			}

			if(continueOn){

				douglas.showWait();

				var currArticle = new Article({ id:id });
				currArticle.loadArticle();
				currArticle.loadContent();
				var articleView = new ArticleView({ model: currArticle });
				articleView.render();
				
				douglas.initializeTinyMCE();
				douglas.Article = currArticle;
				douglas.ArticleView = articleView;
				douglas.closeTree();
				
				douglas.hideWait();
			
			}
		},

		initializeTinyMCE: function(){
			tinymce.init({
		        selector: "div.editable",
		        theme: "modern",
		        plugins: [
		          ["advlist autolink link pineimage lists charmap print preview hr anchor pagebreak"],
		          ["searchreplace wordcount visualblocks visualchars codemirror fullscreen insertdatetime media nonbreaking"],
		          ["save table contextmenu directionality emoticons template paste textcolor pinelink pinecode"]
		        ],
		        contextmenu: "link pinelink image | inserttable | cell row column deletetable",
		        style_formats_merge: true,
		        add_unload_trigger: false,
		        schema: "html5",
		        inline: true,
		        toolbar1: "undo redo | styleselect | pinecode | bold underline italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image ",
		        toolbar2: "forecolor backcolor | print preview media",
		        statusbar: true,
		        image_list: [],
		        convert_urls: false,
		        browser_spellcheck : true,
		        codemirror: {
		          indentOnInit: true, // Whether or not to indent code on init. 
		          path: douglas.public_path+'deps/CodeMirror', // Path to CodeMirror distribution
		        },
		        setup: function(ed) {
		          ed.on('BeforeSetContent',function(e) {
		            if(e.initial === true){
		            }else if(e.selection === true){
		              e.content = e.content.replace(/    /g,"&nbsp;&nbsp;&nbsp;&nbsp;");
		              e.content = e.content.replace(/   /g,"&nbsp;&nbsp;&nbsp;");
		              e.content = e.content.replace(/  /g,"&nbsp;&nbsp;");
		            }
		          });
		          ed.on('KeyDown',function(e) {
		            if (e.keyCode == 9 && !e.altKey && !e.ctrlKey){
		              if (e.shiftKey){
		                tinymce.activeEditor.editorCommands.execCommand("outdent");
		              }else{
		                tinymce.activeEditor.editorCommands.execCommand("indent");
		              }
		              return tinymce.dom.Event.cancel(e); 
		            }else if (e.keyCode == 9 && !e.ctrlKey && e.altKey && !e.shiftKey){
		              tinymce.activeEditor.editorCommands.execCommand('mceInsertContent', false, "&nbsp;&nbsp;&nbsp;");
		              return tinymce.dom.Event.cancel(e); 
		            }
		          });
		        }
		      });
		},

		//User Preferences
		//Load User Preferences
		//Save User Preferences
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
		contentId: "",
		modified:false,

        initialize: function(){
        	//this.on("change",function(msg) { alert("Triggered " + msg); });
        },
        loadContent: function(){
        	console.log("...Loading Article Content");
			modelThis = this;
			return $.ajax({
				type: "GET",
				async: false,
				url: douglas.public_path+"article/content/"+this.id+"?base=AJAX",
				error: function(x, e) {
					var responseObj = $.parseJSON(x.responseText);
					douglas.setMessage("Error Status: "+x.status+"\n"+responseObj.error.message,"danger");
					douglas.showMessage();
        		},
			})
			.done(function( data ) {
				var articleContent = data.articleContents;
				modelThis.content = articleContent.html;
				modelThis.contentId = articleContent.id;
				console.log("Article Content Loaded")
				return modelThis;
			});
        },
        loadArticle: function(){
        	console.log("...Loading Article");
        	modelThis = this;
        	return $.ajax({
				type: "GET",
				async: false,
				url: douglas.public_path+"article/edit/"+this.id+"?base=AJAX",
				error: function(x, e) {
					var responseObj = $.parseJSON(x.responseText);
					douglas.setMessage("Error Status: "+x.status+"\n"+responseObj.error.message,"danger");
					douglas.showMessage();
        		},
			})
			.done(function( data ) {
				var article = data.selectedArticle;
				var safePath = douglas.sanitizePath(article.path);

				modelThis.id = article.id;
				modelThis.title = article.title;
				modelThis.path = article.path;
				modelThis.safePath = safePath;
				modelThis.data_level = article.data_level;

				console.log("Article Loaded");
				return modelThis;
			});
        },
        saveArticleContent: function(){
        	douglas.showWait();
        	console.log("...Saving Article Content");
        	modelThis = this;
        	return $.ajax({
				type: "POST",
				async: false,
				url: douglas.public_path+"article/save/"+this.id+"?base=AJAX",
				data: { contentId: this.contentId, content: this.content },
				error: function(x, e) {
					var responseObj = $.parseJSON(x.responseText);
					douglas.setMessage("Error Status: "+x.status+"\n"+responseObj.error.message,"danger");
					douglas.showMessage();
        		},
			})
			.done(function( data ) {
				if(data.error){
					douglas.setMessage("Error saving...","danger");
					douglas.showMessage();
				}else{
					var message = "";
					console.log(data);
					if(data.articleResults == true) message = message + "Article Saved \n";
					if(data.articleContentsResults == true) message = message + "Article Contents Saved \n";

					douglas.setMessage(message,"success");
					douglas.showMessage();
					
					modelThis.set('modified',false);

					douglas.hideWait();

				}
				return modelThis;
			});
        },
        publishArticle: function(){
        },
        unpublishArticle: function(){
        },
        destroyArticle: function(){
        },
	});
})(Backbone, jQuery);

ArticleTree = (function(Backbone, $){
	return Backbone.Model.extend({

        initialize: function(){
        	//douglas.articleList = new ArticleCollection();
        },
        getChildren: function(path,data_level){
        	douglas.articleList = new ArticleCollection();
	    	douglas.showWait();
			//var parentSafePath = path.replace(new RegExp("/", 'g'), "_");
			var parentSafePath = douglas.sanitizePath(path);
			$.ajax({
				type: "GET",
				url: douglas.public_path+"article/index/AJAX",
				data: { currentPath: path, dataLevel: data_level },
				error: function(x, e) {
					var responseObj = $.parseJSON(x.responseText);
					douglas.setMessage("Error Status: "+x.status+"\n"+responseObj.error.message,"danger");
					douglas.showMessage();
        		},
			})
			.done(function( data ) {
				$.each(data.articleRecords,function(index,article){
					var safePath = article.path;
					//safePath = safePath.replace(new RegExp("/", 'g'), "_");
					safePath = douglas.sanitizePath(safePath);
					var currArticle = new Article({ 
						id: article.id,
						title: article.title,
						path: article.path,
						safePath: safePath,
						data_level: article.data_level,
					});
					douglas.articleList.add(currArticle);
				});
				douglas.articleTreeView.render(path);
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
	    	douglas.articleTree.getChildren("/","2");
	    	this.render("/");
		},
	    render: function(parentBranch){

			//Using JS and JQuery
			resultHTML = "";
			var mainDiv = document.createElement('span');
			
			$.each(douglas.articleList.toJSON(),function(index,article){
				var safePath = article.path;
				safePath = douglas.sanitizePath(safePath);
				//safePath = safePath.replace(new RegExp("/", 'g'), "_");
				
				var outerDiv = document.createElement('div');

				var treeIcon = document.createElement('span');
				treeIcon.className = douglas.closedIcon+" treeBranchToggle";
				treeIcon.setAttribute('data-toggle', 'collapse');
				treeIcon.setAttribute('data-target', '#'+safePath);
				treeIcon.setAttribute('data-level', article.data_level);
				treeIcon.setAttribute('path', article.path);
				treeIcon.setAttribute('onClick','douglas.articleTreeView.toggleBranch(\"'+article.path+'\",\"'+article.data_level+'\")');
				
				outerDiv.appendChild(treeIcon);

				//var titleText = document.createTextNodearticle.title;
				var readablePath = article.path.substring(1,article.path.length -1);
				var pathArray = readablePath.split("/");
				readablePath = pathArray.pop();

				var titleText = document.createTextNode(readablePath);
				//outerDiv.appendChild(titleText);

				var treeLink = document.createElement('a');
				treeLink.setAttribute('href', '#');
				treeLink.setAttribute('title', article.title);
				treeLink.appendChild(titleText);
				treeLink.setAttribute('onClick', 'douglas.editArticleById(\"'+article.id+'\")');
				
				outerDiv.appendChild(treeLink);

                var innerDiv = document.createElement('div');
                innerDiv.setAttribute('id', safePath);
                innerDiv.className = "collapse";

                mainDiv.appendChild(outerDiv);
                mainDiv.appendChild(innerDiv);
			});
			
			if(parentBranch == "/"){
				$("#articleTreeContent").html(mainDiv);	
			}else{
				var safePathParent = parentBranch;
				safePathParent = douglas.sanitizePath(safePathParent);

				var clickedIcon = $("span[data-target=#"+safePathParent+"]");

				if(clickedIcon.hasClass(douglas.closedIcon)){
				    clickedIcon.removeClass(douglas.closedIcon).addClass(douglas.openIcon);
				}else{
				    clickedIcon.removeClass(douglas.openIcon).addClass(douglas.closedIcon);
				}

				//safePathParent = safePathParent.replace(new RegExp("/", 'g'), "_");
				$("#"+safePathParent).html(mainDiv);
			}
			

	        return this;
	    },
	    events: {
            "click [data-toggle=sideBar]": "toggleTree"
        },
	    toggleTree: function(){
	    	//console.log("Toggle Tree for root.");
	    	//douglas.articleTree.getChildren("/","2");
	    	//this.render("/");
			$('.sidebar').toggleClass('active');
			$('.sideBarButton').toggleClass('active');
	    },
	    toggleBranch: function(branch,level){
	    	level = parseInt(level)+1;
	    	console.log("Toggle Tree Branch:"+branch+", level:"+level);
	    	var safePath = douglas.sanitizePath(branch);
	    	douglas.articleTree.getChildren(branch,level);
	    	//console.log(douglas.articleList);
	    	//this.render(branch);
	    }
	});
})(Backbone, jQuery);

ArticleView = (function(Backbone, $){
	return Backbone.View.extend({

		el: "#articleContent",
		events: {
			"click": function () {
				douglas.closeTree();
			},
			"keypress": function () {
				this.model.content = tinyMCE.activeEditor.getContent();
				this.model.set('modified',true);
			},
        },
		initialize: function(){
	    	//this.model.view = this;

		},
	    render: function(parentBranch){
	    	//this.$el.empty().append(this.model.message);

	    	console.log("Rendering Article");

	    	var article = {
	    			id: this.model.id,
	    			title: this.model.title,
	    			path: this.model.path,
					content: this.model.content,
					contentId: this.model.contentId,
	    		};

	    	var templateSrc = "<input type='hidden' id='currentArticleId' value='<%= article.id %>' />";
	    	templateSrc = templateSrc + "<input type='hidden' id='currentArticleContentsId' value='<%= article.contentId %>' />";
	    	templateSrc = templateSrc + "<input type='hidden' id='currentPath' value='<%= article.path %>' />";
	    	templateSrc = templateSrc + "<div style='margin:20px;'>&nbsp;</div>";
	    	templateSrc = templateSrc + "<h1><%= article.title %></h1>";
	    	templateSrc = templateSrc + "<div class='editable'><%= article.content %></div>";

			var template = _.template(templateSrc, {
				article: article
			});
			this.$el.html(template);
			douglas.actionPane.initialize();
			douglas.actionPane.addButton("icon",{class:"octicon octicon-sync",title:"Refresh",text:""},"douglas.editArticleById(douglas.Article.id)");
			douglas.actionPane.addButton("button",{class:"btn btn-primary btn-xs", title:"Save Article Contents",text:"Save"},"douglas.Article.saveArticleContent()");
			douglas.actionPane.addButton("button",{class:"btn btn-success btn-xs", title:"Publish Article",text:"Publish"},"douglas.Article.publishArticle()");
			douglas.actionPane.addButton("button",{class:"btn btn-warning btn-xs", title:"Unpublish Article",text:"Unpublish"},"douglas.Article.unpublishArticle()");
			douglas.actionPane.addButton("button",{class:"btn btn-danger btn-xs", title:"Destroy Article",text:"Destroy"},"douglas.Article.destroyArticle");
	    },
	});
})(Backbone, jQuery);

ActionView = (function(Backbone, $){
	return Backbone.View.extend({

		el: "#actionsPane",
		initialize: function(){
			this.$el.empty();
		},
	    render: function(){
	    },
	    addButton: function(type,data,action){
	    	if(type=="icon"){
	    		var templateSrc = "<span class=\"actionButton <%= data.class %>\" style=\"\" title=\"<%= data.title %>\" onClick=\"<%= action %>\"><%= data.text %></span>";
	    	}else{
	    		var templateSrc = "<button type=\"button\" class=\"actionButton <%= data.class %>\" style=\"margin:5px;\" title=\"<%= data.title %>\" onClick=\"<%= action %>\"><%= data.text %></button>";
	    	}
	    	
	    	var template = _.template(templateSrc, {
				data:data,
				action:action,
			});
			this.$el.append(template);
	    },
	});
})(Backbone, jQuery);

/**
 * Run Douglas
 */

var douglas = new AppMain();
_.extend(douglas, Backbone.Events); 
douglas.on("click", function(msg) { alert("Triggered " + msg); });

$(function(){
	
	douglas.message = new Message();
	douglas.messageView = new MessageView({
		model : douglas.message
	});
	douglas.waitIndicator = new WaitView();
	douglas.articleTree = new ArticleTree();

	douglas.articleTreeView = new ArticleTreeView();
	douglas.actionPane = new ActionView();

	//douglas.initializeTinyMCE();

});