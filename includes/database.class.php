<?php
ob_start();
//session_start();
class database
	{
		private $func;

		function __construct($pdo)
		{
			$this->pdo=$pdo; 
		}
		function check_if_article_exists_in_tabe($article_url)
		{

			$query=$this->pdo->prepare("select * from articles where url='$article_url'");
			$query->execute();
			$count =$query->rowCount();
			if ($count == 1)
			{	
				//article exists in database
				return 1;
			}
			else{
				//article doesnt exist in database
				return 0;
			}

		}

		function add_articles_to_database($article_title,$article_author,$article_description,$article_url,$article_url_to_image,$article_content)
		{
			//$article_title="test";
			//$article_author="test";
			$article_description= "test";
			//$article_url="test";
			//$article_url_to_image="test";
			$article_content="test";
			//$query= $this->pdo->prepare("INSERT INTO `articles_table`VALUES ('$article_title','$article_author','$article_description','$article_url','$article_url_to_image','$article_content')");
			$query= $this->pdo->prepare("INSERT INTO `articles_table`VALUES (:article_title,:article_author,:article_description,
			:article_url,:article_url_to_image,:article_content)");
			$query->bindParam(':article_title', $article_title);
			$query->bindParam(':article_author', $article_author);
			$query->bindParam(':article_description', $article_description);
			$query->bindParam(':article_url', $article_url);
			$query->bindParam(':article_url_to_image', $article_url_to_image);
			$query->bindParam(':article_content', $article_content);	
			$query->execute();
				//$pid= $this->pdo->lastInsertId();			
				$rowsadded = $query->rowCount();
				if ($rowsadded >0 )
				{	
					//$ctr++;							
				}
		}

		function read_articles($article_url)
		{
			
			$query=$this->pdo->prepare("select * from articles where url='$article_url'");
			$query->execute();
			$article_list = $query->fetchAll();
			$count =$query->rowCount();
			if ($count >0)
			{	
								// data is available ..........add it to  json
				foreach ($article_list as $article)			
					{
				 		$innerapp->destination = $bus->destination;
						$innerapp->duration = $bus->duration;						
				        array_push($return_arr,$innerapp);							
					}
				$jsonData = json_encode($return_arr);
		    	echo $jsonData;				
			}
		}

		function clietBookingMobile()
		{
			echo 'this is test data';	

		}

	}					