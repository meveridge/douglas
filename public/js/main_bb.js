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

		sanitizePath: function(path){
			var safePath = path.replace(new RegExp("/", 'g'), "_");
			safePath = safePath.replace(new RegExp("'", 'g'), "_");
			return safePath;
		},

		//SideBar
		addTreeSideBar: function(){
			//Needs work... throws JS errors if not on Article
			douglas.articleTreeView.render();
		},
		toggleTree: function(){
			douglas.articleTreeView.toggleTree();
		},

		initializeTinyMCE: function(){
			tinymce.init({
			    selector: "div.editable",
			    theme: "modern",
			    plugins: [
			        ["advlist autolink link pineimage lists charmap print preview hr anchor pagebreak spellchecker"],
			        ["searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking"],
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
			    setup : function(ed) {
			        ed.on('KeyDown',function(e) {
			            if (e.keyCode == 9 && !e.altKey && !e.ctrlKey){
			                if (e.shiftKey){
			                    tinyMCE.activeEditor.editorCommands.execCommand("outdent");
			                }else{
			                    tinyMCE.activeEditor.editorCommands.execCommand("indent");
			                }
			                return tinymce.dom.Event.cancel(e); 
			            }else if (e.keyCode == 9 && !e.ctrlKey && e.altKey && !e.shiftKey){
			                tinyMCE.activeEditor.editorCommands.execCommand('mceInsertContent', false, "&nbsp;&nbsp;&nbsp;");
			                return tinymce.dom.Event.cancel(e); 
			            }
			            
			        });
			    },
			    setup : function(ed) {
			        ed.on('BeforeSetContent',function(e) {
			            if(e.initial === true){
			            }else{
			                e.content = e.content.replace(/    /g,"&nbsp;&nbsp;&nbsp;&nbsp;");
			                e.content = e.content.replace(/   /g,"&nbsp;&nbsp;&nbsp;");
			                e.content = e.content.replace(/  /g,"&nbsp;&nbsp;");
			            }
			        });
			    },
			    paste_preprocess : function(pl, o) {
			            //alert(o.content);
			            //o.content = o.content.replace(/\>\s+\</g,"&nbsp;");
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

        initialize: function(){
        },
        setContent: function(){
        },
        loadArticle: function(){
        	$.ajax({
				type: "GET",
				url: douglas.public_path+"article/edit/"+this.id+"?base=AJAX"
			})
			.done(function( data ) {
				$.each(data.selectedArticle,function(index,article){
					//start here...
					console.log(article);
					var safePath = douglas.sanitizePath(article.path);
					this.id = article.id;
					this.title = article.title;
					this.path = article.path;
					this.safePath = safePath;
					this.data_level = article.data_level;
				});
				return data;
			});
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
				data: { currentPath: path, dataLevel: data_level }
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
				safePath = douglas.sanitizePath(safePath);
				//safePath = safePath.replace(new RegExp("/", 'g'), "_");
				
				var outerDiv = document.createElement('div');

				var treeIcon = document.createElement('span');
				treeIcon.className = "glyphicon "+douglas.closedIcon+" treeBranchToggle";
				treeIcon.setAttribute('data-toggle', 'collapse');
				treeIcon.setAttribute('data-target', '#'+safePath);
				treeIcon.setAttribute('data-level', article.data_level);
				treeIcon.setAttribute('path', article.path);
				treeIcon.setAttribute('onClick','douglas.articleTreeView.toggleBranch(\"'+article.path+'\",\"'+article.data_level+'\")');
				
				outerDiv.appendChild(treeIcon);

				var titleText = document.createTextNode(article.title);
				outerDiv.appendChild(titleText);

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

	douglas.articleTreeView = new ArticleTreeView();

	douglas.initializeTinyMCE();

});