<?php
include("simple_html_dom.php");
require_once('includes/config.php');
require_once('includes/private.php');
require_once('includes/database.class.php');
$db= new database($pdo);
$no=sizeof($private_url);
foreach($private_url as $url)
{
  $json = file_get_contents($url);
$json = json_decode($json);

foreach ($json->articles as $article) {
  if($db->isSiteAvailible($article->url)){
    try{
    $html = file_get_html($article->url);
      }
      catch(Exception $r){

      }
    $str="";
    foreach($html->find('p') as $element)
         $str=$str.$element;

         if($str!=""){
         try{
          $db->add_articles_to_database($article->title ,$article->author ,$article->description 
          ,$article->url,$article->urlToImage,$str);

         }
        catch(PDOException $e){
          echo "already present in db";
        }
      }
    echo "---------------------------------------------------------------------------------------<br>";
    }
   }
  }


?>