<?php
include("simple_html_dom.php");
require_once('includes/config.php');
require_once('includes/private.php');
require_once('includes/database.class.php');
$db= new database($pdo);
$json = file_get_contents($private_url);
$json = json_decode($json);

foreach ($json->articles as $article) {
    $html = file_get_html($article->url);
    $str="";
    foreach($html->find('p') as $element)
         $str=$str.$element;
        // echo $article->title;
         //echo "<br>";
     //    echo $article->description;
     //    echo "<br>";
     //    echo $article->url;
     //    echo "<br>";
     //    echo $article->urlToImage;
        // echo "<br>";
       //  echo $article->content;
     //    echo "<br>";
       //  echo $article->publishedAt;
     //    echo "<br>";
     //    echo "<br>*****************************************************************<br>";
   //      echo $str;  
echo "before db";
         $db->add_articles_to_database($article->title ,$article->author ,$article->description 
         ,$article->url,$article->urlToImage,$str);
         echo "inside loop";
    //$stat=(!$db->check_if_article_exists_in_tabe($article->url));
   // echo $stat;
    //if($stat){

       // $stat=(!$db->check_if_article_exists_in_tabe($article->url));
        echo "inside loop";
        //article doesn't exist so inserting
        // $db->add_articles_to_database($article->title ,$article->author ,$article->description 
        // ,$article->url,$article->ururlToImagel,$str);


  //  }
    echo "---------------------------------------------------------------------------------------<br>";

   
    
 }



?>