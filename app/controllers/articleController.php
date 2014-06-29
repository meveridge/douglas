<?php

class articleController extends \BaseController {

	public $masterDir = "/Library/WebServer/Documents/pine/pineneedles/contents/";

	/**
	 * Display a listing of the resource
	 * and render the article index view
	 *
	 * @param JSON $articleRecords
	 * @return render articleIndex view
	 */
	public function index($articleRecords)
	{
		return View::make('articleIndex', array(
	    	'pageTitle' => 'SugarCRM Douglas -- Article Index',
	    	'activeLink' => 'article',
	    	'articleRecords' => $articleRecords,
	    	)
	    );
	}

	/**
	 * Get Method for the Index
	 * Return list of articles from db to JSON
	 *
	 * @param string base 
	 * @return $articleRecords or Render View
	 */
	public function getIndex($base)
	{

		$currentPath = Input::get('currentPath');
		$dataLevel = Input::get('dataLevel');

		if(!isset($base)) $base = null;
		if(!isset($currentPath)) $currentPath = "/";
		if(!isset($dataLevel)) $dataLevel = "2";

		$translatedPath = str_replace("|", "/", $currentPath) . "%";
		$articleRecords = DB::table('articles')
			->where('path', 'like', $translatedPath)
			->where('data_level', $dataLevel)
			->orderBy('path', 'asc')
			->get();

		if($base=="web"){
			return $this->index($articleRecords);
		}else{
			//return $articleRecords;
			//var_dump($articleRecords);
			return Response::json(array(
        		'error' => false,
        		'articleRecords' => $articleRecords),
        		200
    		);
		}
		
	}

	/**
	 * Get Method for the Index
	 * Return list of articles from db to JSON
	 *
	 * @param string base 
	 * @return $articleRecords or Render View
	 */
	public function postIndex()
	{
		$name = Input::get('name');
		echo"Base = $base : Path = $currentPath : Data Level = $dataLevel";
		$translatedPath = str_replace("|", "/", $currentPath) . "%";
		$articleRecords = DB::table('articles')
			->where('path', 'like', $translatedPath)
			->where('data_level', $dataLevel)
			->orderBy('path', 'asc')
			->get();

		if($base=="web"){
			return $this->index($articleRecords);
		}else{
			//return $articleRecords;
			//var_dump($articleRecords);
			return Response::json(array(
        		'error' => false,
        		'articleRecords' => $articleRecords),
        		200
    		);
		}
		
	}

	/**
	 * Display from to edit the article
	 * and render the article index view
	 *
	 * @param JSON $selectedArticle
	 * @return render articleIndex view
	 */
	public function edit($selectedArticle)
	{
		//echo"Article ID: $selectedArticle->id";
		$articleContent = $this->getContent($selectedArticle->id);
		return View::make('articleIndex', array(
	    	'pageTitle' => 'SugarCRM Douglas -- Article Index',
	    	'activeLink' => 'article',
	    	'selectedArticle' => $selectedArticle,
	    	'articleContent' => $articleContent,
	    	)
	    );
	}

	/**
	 * Get Method for editing an articlde
	 * Return JSON data result of edit
	 *
	 * @return Response
	 */
	public function getEdit($id)
	{
		$base = Input::get('base');

		$selectedArticle = DB::table('articles')
			->where('id', $id)
			->first();

		if($base=="web"){
			return $this->edit($selectedArticle);
		}else{
			//return $selectedArticle;
			//var_dump($selectedArticle);

			return Response::json(
				array(
        			'error' => false,
        			'selectedArticle' => $selectedArticle,
        		),
        		200
    		);
		}
		
	}

	/**
	 * Get Method for retrieving an article's content
	 * Return JSON data
	 *
	 * @return Response
	 */
	public function getContent($id)
	{
		$base = Input::get('base');

		$articleContents = DB::table('article_content')
			->select('article_content.content_id')
			->where('article_id', $id)
			->orderBy('ordinal', 'asc')
			->get();

		$articleHTML = "";

		foreach($articleContents as $index=>$contentRecord){
			$articleContents = DB::table('content')
				->where('id', $contentRecord->content_id)
				->first();
			$articleHTML .= $articleContents->html;
		}
		if($base=="web"){
			return array(
				"contentData"=>$articleContents,
				"html"=>$articleHTML
			);
		}else{
			return Response::json(array(
	    		'error' => false,
	    		'articleContents' => $articleContents,
				'html' => $articleHTML),
	    		200
			);
		}
	}

	/**
	 * Get Method for publishing an article's content
	 * Return JSON data
	 *
	 * @return Response
	 */
	public function getPublish($id)
	{
		$base = Input::get('base');

		//Retreive Article header 
		$article = Article::find($id);

		//Retreive Article MetaData not in header
		$articleMetadata = ArticleMetadata::where('article_id', $article->id)
			->get();
		$metadata = array();
		foreach($articleMetadata as $index=>$data){
			$metadata[] = Metadata::find($data->metadata_id);
		}
		
		//Retreive Article Content
		$articleContent = ArticleContent::where('article_id', $article->id)
			->get();
		$content = array();
		foreach($articleContent as $index=>$data){
			$content[] = Content::find($data->content_id);
		}

		//Build index.md from all sources

		$articleFileContents = "---\n";

		foreach($metadata as $key=>$value){
			$articleFileContents .= $value->key.":".$value->value."\n";
			//echo $value->key.":".$value->value."<br>";
		}
		$articleFileContents .= "---\n\n";

		foreach($content as $key=>$value){
			$articleFileContents .= $value->html;
		}
		echo "<textarea>$articleFileContents</textarea>";

		//Create index.md file in the correct path
		//$fileResults = file_put_contents($this->masterDir.$article->path."index.md", $articleFileContents);
		$fileResults = File::put($this->masterDir.$article->path."index.md", $articleFileContents);
		
		$result = "";
		if($fileResults===true){
			$result = "File(".$this->masterDir.$article->path."index.md) written successfully.";
			$error = false;
		}else{
			$result = "File Write Failure!";
			$error = true;
		}

		if($base=="web"){
			return $result;
		}else{
			return Response::json(
				array(
		    		'error' => $error,
		    		'result' => $result
				),
	    		200
			);
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function postCreate()
	{
		$method = Request::method();

		if (Request::isMethod('post'))
		{
			$title = Input::get('inputArticleTitle');
			$path = Input::get('inputArticlePath');

			$articleId = $this->createArticleRecord(
				array(
					'title'=>$title,
					'path'=>$path,
				)
			);

			if($articleId){
				return Redirect::to('article')->with('message', 'Article Id '.$articleId.' created.');
			}else{
				return Redirect::to('article')->with('error', 'Article Failed to create.');
			}
		}
	}

	/**
	 * Save an Article by Id
	 *
	 * @return Response
	 */
	public function postSave($id)
	{
		$method = Request::method();

		if (Request::isMethod('post'))
		{
			$results = array();
			$error = false;

			$articleId = Input::get('articleId');
			$title = Input::get('inputArticleTitle');
			$path = Input::get('inputArticlePath');
			//$path = Input::get('inputArticlePath');

			//If we are saving the article
			if(isset($articleId)){
				$article = Article::find($articleId);
				$article->title = $title;
				$article->path = $path;
				$articleResults = $article->save();
			}

			$contentId = Input::get('contentId');
			$content = Input::get('content');

			//If we are saving the content
			if(isset($contentId)){
				$articleContent = Content::find($contentId);
				$articleContent->html = $content;
				$articleContentsResults = $articleContent->save();
			}

			if(isset($articleResults) && $articleResults===false){
				$error = true;
			}elseif(isset($articleContentsResults) && $articleContentsResults===false){
				$error = true;
			}
			return Response::json(
				array(
	    			'error' => $error,
	    			'articleResults' => (isset($articleResults) ? $articleResults : 0),
	    			'articleContentsResults' => (isset($articleContentsResults) ? $articleContentsResults : 0),
				),
	    		200
			); 
		}
	}

	/**
	 * Create article db record
	 *
	 * @return Response
	 */
	public function createArticleRecord($data = array())
	{
		//$date = new \DateTime;
		//$data['date_entered'] = $date;
		//$data['date_modified'] = $date;
		
		$article = Article::create($data);
	    
	    //$articleId = DB::table('articles')->insertGetId($data);
		return $article->id;
	}

	/**
	 * Save article db record
	 *
	 * @return Response
	 */
	public function saveArticleRecord($data = array())
	{
		$article = Article::find($data['id']);

		$article->email = 'john@foo.com';

		$article->save();
	}

	/**
	 * Create article content db record
	 *
	 * @return Response
	 */
	public function createArticleContentRecord($data = array())
	{
		$date = new \DateTime;
		$data['date_entered'] = $date;
		$data['date_modified'] = $date;

		//$contentData['date_entered'] = $data['date_entered'];
		//$contentData['date_modified'] = $data['date_modified'];
		$contentData['html'] = $data['html'];

		echo"String Length #3:".strlen($data['html']);

		//$contentId = DB::table('content')->insertGetId($contentData);

		$Content = new Content;
		$Content->html = $data['html'];
		$Content->save();

		$queries = DB::getQueryLog();
		$last_query = end($queries);
		echo"String Length #4:".strlen($queries['5']['bindings']['0']);

		$contentId = $Content->id;


		$articleContentData['ordinal'] = $data['ordinal'];
		$articleContentData['article_id'] = $data['article_id'];
		$articleContentData['content_id'] = $contentId;

	    $articleContentId = DB::table('article_content')->insertGetId($articleContentData);

		return $contentId;
	}

	/**
	 * Save article content db record
	 *
	 * @return Response
	 */
	public function saveArticleContentRecord($data = array())
	{
		$article = Article::find($data['id']);

		$article->email = 'john@foo.com';

		$article->save();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function createArticleMetadataRecord($data = array(),$articleId = false)
	{
		$date = new \DateTime;
	    $metaId = DB::table('metadata')->insertGetId(
				array(
					'key' => $data['key'], 
					'value' => $data['value'],
				)
		);
		if($articleId && $metaId){
			$amId = DB::table('article_metadata')->insertGetId(
					array(
						'article_id' => $articleId, 
						'metadata_id' => $metaId,
					)
			);
		}
		return $metaId;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		return View::make('articleCreate', array(
	    	'pageTitle' => 'SugarCRM Douglas -- Article Index',
	    	'activeLink' => 'article',
	    	)
	    );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function editArticle($path)
	{
		//Pull article contents from index.md
		echo $_SERVER['DOCUMENT_ROOT'];
		$article = file_get_contents($_SERVER['DOCUMENT_ROOT'].'pine/pineneedles/contents/'.$path, FILE_USE_INCLUDE_PATH);
		$articlePieces = explode("---\n\n", $article);

		echo "<br><br><hr><br>";
		$parsedArticlePieces = yaml_parse($articlePieces['0']);
		var_dump($parsedArticlePieces);

		//Parese Out Header and Body Data
		echo"Path=$path<br><hr><br>";

		$pageHeader['articleTitle'] = $parsedArticlePieces['title'];
      	echo View::make('partials.pageHeader', $pageHeader);

		$articleHeader['articleMetadata'] = "title:My awesome website,path:".$path; 
		$articleHeader['dateModified'] = $parsedArticlePieces['dateModified'];
		$articleHeader['articleImages'] = "img.png"; 
      	echo View::make('partials.articleHeader', $articleHeader);

      	$articleBody['articleContent'] = "test content for my article";
      	echo View::make('editArticle', $articleBody);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	public function getFile(){
		$articleString = file_get_contents("/Library/WebServer/Documents/pine/pineneedles/contents/02_Documentation/01_Sugar_Editions/01_Sugar_Ultimate/Sugar_Ultimate_7.1/Administration_Guide/07_Developer_Tools/01_Studio/index.md");
		echo"<textarea>$articleString</textarea>";
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getImport()
	{	
		$path = Input::get('path', $this->masterDir);
		//$this->masterDir = "/Library/WebServer/Documents/pine/pineneedles/contents/02_Documentation/01_Sugar_Editions/01_Sugar_Ultimate/Sugar_Ultimate_7.1/Administration_Guide/07_Developer_Tools/01_Studio/";
		$this->loadDirectory($path);
	}

	public function loadDirectory($dir){

		// Open a known directory, and proceed to read its contents
		if (is_dir($dir)) {
		    if ($dh = opendir($dir)) {
		        while (($file = readdir($dh)) !== false) {
		        	if($file[0]!="."){
		        		//echo "filename: $file : filetype: " . filetype($dir . $file) . "<br>";
		        		
		        		if(filetype($dir . $file)=="dir"){
		        			//Currentrly on a dir, must go deeper
		        			$this->loadDirectory($dir . $file . "/");
		        		}elseif($file=="index.md"){
		        			$files[] = $dir . $file;
		        		}
		        	}
		        }
		        closedir($dh);
		    }
		}

		if(count($files)>0){
			foreach($files as $filename){

				//Found an article! Load it!
				//$articleString = file_get_contents($dir . $file);
				$articleString = File::get($filename, "File Not Found ($filename)");

				echo"String Len #1:".strlen($articleString);

				//TODO: PHP is hating putting this into a variable.... 

				echo"<textarea>$articleString</textarea>";

				$articleArray = explode("---\n\n",$articleString);

				// explode failed to separate the article from the yaml
				// try again with windows line endings...
				//echo"<br>Array Count: ".count($articleArray);
				if(count($articleArray) == 1){
					$articleArray = explode("---\r\n\r\n",$articleString);
				}

				(isset($articleArray['0']) ? $headerString = trim($articleArray['0']) : $headerString = "");
				(isset($articleArray['1']) ? $contentString = trim($articleArray['1']) : $contentString = "");
				echo"String Len #2:".strlen($contentString);

				//Read Headers and parse metadata
				try{
					(($headerString!=="") ? $parsedMetaData = yaml_parse($headerString) : $parsedMetaData = "");
				} catch (Exception $e) {
					echo '<hr>Caught exception on YAML: ',  $e->getMessage(), "\n";
					echo "filename: $dir . $file <br> $headerString<hr>";
					$parsedMetaData = "";
				}

				//var_dump($parsedMetaData);
				if(is_array($parsedMetaData)){

					$parsedDir = substr($dir,strlen($this->masterDir)-1);
					$newArticleData['path'] = $parsedDir;

					$newArticleData['data_level'] = substr_count($parsedDir,"/");

					//Previous MetaData, now stored on article record
					if(isset($parsedMetaData['title'])) $newArticleData['title'] = $parsedMetaData['title'];
					//if(isset($parsedMetaData['template'])) $newArticleData['template'] = $parsedMetaData['template'];
					//if(isset($parsedMetaData['css'])) $newArticleData['css'] = $parsedMetaData['css'];
					//if(isset($parsedMetaData['bodyclass'])) $newArticleData['bodyclass'] = $parsedMetaData['bodyclass'];
					//if(isset($parsedMetaData['widgets'])) $newArticleData['widgets'] = $parsedMetaData['widgets'];
					//if(isset($parsedMetaData['dateModified'])) $newArticleData['date_modified'] = $parsedMetaData['dateModified'];
					
					//Create Article Record
					try{
						$articleId = $this->createArticleRecord($newArticleData);
					} catch (Exception $e) {
						echo '<hr>Caught exception on Create Article: ';
						print_r($e);
						echo "filename: $dir . $file <hr>";
						$articleId = false;
					}
					
					
					//Store Article Metadata
					foreach($parsedMetaData as $key=>$value){
						if(!in_array($key, $newArticleData)){
							//Only create metadata for things not on the Article record
							try{
								$metaId = $this->createArticleMetadataRecord(
									array(
										'key'=>$key,
										'value'=>$value,
									),
									$articleId
								);
							} catch (Exception $e) {
								echo '<hr>Caught exception on Metadata Create: ',  $e->getMessage(), "\n";
								echo "filename: $dir . $file <hr>";
								$metaId = "";
							}
							
							
						}
					}

					//Store Article Content
					$newArticleContentData['article_id'] = $articleId;
					$newArticleContentData['html'] = $contentString;
					$newArticleContentData['ordinal'] = "1";
					$articleContentId = $this->createArticleContentRecord($newArticleContentData);

					if($articleId) echo"Article id : $articleId created<br>";
				}else{
					echo "<hr>Error parsing article metadata. Skipping... <br>";
					echo "filename: $dir . $file <hr>";
				}

			}
		}
	}

}
