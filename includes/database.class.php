<?php
//ob_start();
class database
	{
		private $func;

		function __construct($pdo)
		{
			$this->pdo=$pdo; 
		}
		
		function add_articles_to_database($article_title,$article_author,$article_description,$article_url,$article_url_to_image,$article_content)
		{
			
			$query= $this->pdo->prepare("INSERT INTO `articles_table`VALUES (:article_title,:article_author,:article_description,
			:article_url,:article_url_to_image,:article_content)");
			$query->bindParam(':article_title', $article_title);
			$query->bindParam(':article_author', $article_author);
			$query->bindParam(':article_description', $article_description);
			$query->bindParam(':article_url', $article_url);
			$query->bindParam(':article_url_to_image', $article_url_to_image);
			$query->bindParam(':article_content', $article_content);	
			$query->execute();				
				$rowsadded = $query->rowCount();
				if ($rowsadded >0 )
				{	
												
				}
		}

		function read_articles()
		{
			$return_arr=array();
			$query=$this->pdo->prepare("select * from articles_table WHERE 1");
			$query->execute();
			while($article=$query->fetch(PDO::FETCH_OBJ)){
				$temp_holder= new \stdClass();
				$temp_holder->title=$article->TITLE;
				$temp_holder->author=$article->AUTHOR;
				$temp_holder->description=$article->DESCRIPTION;
				$temp_holder->url=$article->URL;
				$temp_holder->img_url=$article->URL_TO_IMAGE;
				//$temp_holder->content=$article->CONTENT;						
				array_push($return_arr,$temp_holder);	

			}
			$jsonData = json_encode($return_arr);
			echo $jsonData;	
			
						
		}

		function clietBookingMobile()
		{
			echo 'this is test data';	

		}


		function isSiteAvailible($url){
			// Check, if a valid url is provided
			if(!filter_var($url, FILTER_VALIDATE_URL)){
				return false;
			}		
			// Initialize cURL
			$curlInit = curl_init($url);			
			// Set options
			curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
			curl_setopt($curlInit,CURLOPT_HEADER,true);
			curl_setopt($curlInit,CURLOPT_NOBODY,true);
			curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);		
			// Get response
			$response = curl_exec($curlInit);			
			// Close a cURL session
			curl_close($curlInit);		
			return $response?true:false;
		}

	}					