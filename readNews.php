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
  $id=$article->source->id;
  $name=$article->source->name;

  $str="";
  if($db->isSiteAvailible($article->url)){
    try{
      if($db->endswith($article->url,'html') |$db->endswith($article->url, '/')){
        $html = file_get_html($article->url);
        
        foreach($html->find('p') as $element){
          //replace quotes
          $element=str_replace('"',"&&quote&&",$element);
          $element=str_replace('<iframe',"&&frame",$element);
          $element=str_replace('</iframe',"&&frame&&",$element);
          //remove spans 
          $element=str_replace('<span',"&span",$element);
          $element=str_replace('</span',"&&span",$element);
          // remove buttons
          $element=str_replace('<button',"&button",$element);
          $element=str_replace('</button',"&&button",$element);
          $str=$str.$element;

      }
    }       
          
      }
      catch(Exception $r){
        $html=" ";

      }
   
    }        

         if($str!=""){
         try{
          $db->add_articles_to_database($article->title ,$article->author ,$article->description 
          ,$article->url,$article->urlToImage,$str,$id,$name);
      

         }
        catch(PDOException $e){
          echo "already present in db";
        }
      }
    echo "---------------------------------------------------------------------------------------<br>";
    }
   }
  


?>