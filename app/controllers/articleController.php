<?php

class articleController extends \BaseController {

	public $masterDir = "";

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return 'other index...';
	}

	/**
	 * Get Method for the Index
	 * Return list of articles from db
	 *
	 * @return Response
	 */
	public function getIndex()
	{

		$articleRecords = DB::table('articles')
			//->orderBy('data_level', 'asc')
			->orderBy('path', 'asc')
			->get();

		return View::make('articleIndex', array(
	    	'pageTitle' => 'SugarCRM Douglas -- Article Index',
	    	'activeLink' => 'article',
	    	'articleRecords' => $articleRecords,
	    	)
	    );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getEdit($id)
	{
		$articleRecords = DB::table('articles')
			//->orderBy('data_level', 'asc')
			->orderBy('path', 'asc')
			->get();
		$selectedArticle = DB::table('articles')->where('id', $id)->first();

		return View::make('articleIndex', array(
	    	'pageTitle' => 'SugarCRM Douglas -- Article Index',
	    	'activeLink' => 'article',
	    	'articleRecords' => $articleRecords,
	    	'selectedArticle' => $selectedArticle,
	    	)
	    );
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
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function createArticleRecord($data = array())
	{
		$date = new \DateTime;
		$data['date_entered'] = $date;
		$data['date_modified'] = $date;

	    $articleId = DB::table('articles')->insertGetId($data);
		return $articleId;
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

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getImport($path)
	{
		$this->masterDir = "/Library/WebServer/Documents/pine/pineneedles/contents/";

		$this->loadDirectory($this->masterDir);
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
		        			//Found an article! Load it!
		        			$articleString = file_get_contents($dir . $file);
		        			$articleArray = explode("---\n\n",$articleString);
							
							(isset($articleArray['0']) ? $headerString = trim($articleArray['0']) : $headerString = "");
		        			(isset($articleArray['1']) ? $contentString = trim($articleArray['1']) : $contentString = "");
		        			
		        			//Read Headers and parse metadata
		        			try{
		        				(($headerString!=="") ? $parsedMetaData = yaml_parse($headerString) : $parsedMetaData = "");
		        			} catch (Exception $e) {
    							echo '<hr>Caught exception: ',  $e->getMessage(), "\n";
			    				echo "filename: $dir . $file <hr>";
    							$parsedMetaData = "";
							}

							//var_dump($parsedMetaData);
		        			if(is_array($parsedMetaData)){

		        				$parsedDir = substr($dir,strlen($this->masterDir)-1);
		        				$newArticleData['path'] = $parsedDir;

		        				$newArticleData['data_level'] = substr_count($parsedDir,"/");

		        				//Previous MetaData, now stored on article record
		        				if(isset($parsedMetaData['title'])) $newArticleData['title'] = $parsedMetaData['title'];
		        				if(isset($parsedMetaData['template'])) $newArticleData['template'] = $parsedMetaData['template'];
		        				if(isset($parsedMetaData['css'])) $newArticleData['css'] = $parsedMetaData['css'];
		        				if(isset($parsedMetaData['bodyclass'])) $newArticleData['bodyclass'] = $parsedMetaData['bodyclass'];
		        				if(isset($parsedMetaData['widgets'])) $newArticleData['widgets'] = $parsedMetaData['widgets'];
		        				if(isset($parsedMetaData['dateModified'])) $newArticleData['date_modified'] = $parsedMetaData['dateModified'];
			        			
			        			//Create Article Record
			        			try{
		        					$articleId = $this->createArticleRecord($newArticleData);
			        			} catch (Exception $e) {
	    							echo '<hr>Caught exception: ',  $e->getMessage(), "\n";
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
			    							echo '<hr>Caught exception: ',  $e->getMessage(), "\n";
			    							echo "filename: $dir . $file <hr>";
			    							$metaId = "";
										}
										
										
									}
								}
								if($articleId) echo"Article id : $articleId created<br>";
							}else{
								echo "<hr>Error parsing article metadata. Skipping... <br>";
			    				echo "filename: $dir . $file <hr>";
							}

							//Add Article Contents
		        		}
		        	}
		            
		        }
		        closedir($dh);
		    }
		}
	}

}
