<?php
include("simple_html_dom.php");
require_once('includes/config.php');
require_once('includes/private.php');
require_once('includes/database.class.php');
$db= new database($pdo);
$json = file_get_contents($private_url);
$json = json_decode($json);

foreach ($json->articles as $article) {
  if($db->isSiteAvailible($article->url)){
    $html = file_get_html($article->url);
    $str="";
    foreach($html->find('p') as $element)
         $str=$str.$element;
        


        
    $stat=(!$db->check_if_article_exists_in_tabe($article->url));
    if($stat){

      $db->add_articles_to_database($article->title ,$article->author ,$article->description 
      ,$article->url,$article->urlToImage,$str);

       // $stat=(!$db->check_if_article_exists_in_tabe($article->url));
        echo "inside loop";
        //article doesn't exist so inserting
        // $db->add_articles_to_database($article->title ,$article->author ,$article->description 
        // ,$article->url,$article->ururlToImagel,$str);


    }
    echo "---------------------------------------------------------------------------------------<br>";
    }
   
    
 }



?>