<?php
/**
* @version      2.3
* @package		Easytagcloud
* @author       Wong Kee(lilackee@gmail.com)
* @copyright	Copyright(C)2012 Joomla Tonight. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Easytagcloud is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class modTagcloudhelper 
{
	
//rebuild the table(s)
 function rebuilddb($type=1) 
 {
  global $mainframe;
  $db=JFactory::getDBO();
  switch ($type) {
   case 1:

     $sql="DROP table IF EXISTS #__jt_tags";

	 $db->setQuery($sql);
	 $db->execute();
    $sql="CREATE TABLE #__jt_tags(
          `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
          `catid` INT NOT NULL ,
          `tag` VARCHAR( 64 ) NOT NULL ,
          `freq` INT NOT NULL
          )"; 
    $db->setQuery($sql);	  
    $db->execute();
    //Set the new update time
	$currenttime=time();
	$sql="UPDATE #__jt_record SET `updatetime`=".$currenttime." WHERE `version`='2.3'"; 
    $db->setQuery($sql); 
	$db->execute();
   break;
   case 2:
	 $sql="DROP table IF EXISTS #__jt_tags,#__jt_record";
	 $db->setQuery($sql);
	 $db->execute();
     $sql="CREATE TABLE #__jt_tags (
          `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
          `catid` INT NOT NULL ,
          `tag` VARCHAR(64) NOT NULL ,
          `freq` INT NOT NULL
          )"; 
   	 $db->setQuery($sql);
	 $db->execute();
	 $sql="CREATE TABLE #__jt_record (
          `version` VARCHAR(10) NOT NULL ,
          `updatetime` INT NOT NULL
          )";
     $db->setQuery($sql); 
     $db->execute();
     $currenttime=time();
	 $sql="INSERT INTO #__jt_record (
          `version` ,
          `updatetime`
          )
          VALUES (
          '2.3',  
		  ".$currenttime."
          )";
		  
     $db->setQuery($sql); 
     $db->execute();
	}
 }
 		
 function init(&$params)
 {
   global $mainframe;
   $db=JFactory::getDBO();
      $nowtimeinsql=JFactory::getDate()->toSql(); 
      $query="SELECT `metakey`,`catid`,`title`,`publish_up`,`publish_down` FROM #__content WHERE state=1";
      $db->setQuery($query);
      $queryresult=$db->loadObjectlist();
      foreach($queryresult as $result)
        {
		  //Logic error fix:try to index the tags in articles which will publish in future. From v2.3 Nov.25,2012 
		  if((intval($result->publish_up)!=0&&$result->publish_up>$nowtimeinsql)||(intval($result->publish_down)!=0&&$result->publish_down<$nowtimeinsql)) {
		      continue;
		  }
		  
          $temp=explode(",",$result->metakey);  

		  $tagsfilter=array();
		
          foreach($temp as $t)
             {
               $t=addslashes(strip_tags($t));
               $t=trim($t);
               if(modTagcloudhelper::stripSameTags($t,$tagsfilter)) {
			      continue; 
			   }else{
			        $tagsfilter[]=$t;
			   } 
               // insert into database directly,from v2.3
               // case-insensitive
               if($t!='') { //in case not empty
                  $sql="SELECT * FROM #__jt_tags WHERE `tag`='".$t."'";
			      $db->setQuery($sql);
			      $db->execute();
			      $x=$db->getNumRows();
		   	      if($x==0) {
			         $sql="INSERT INTO #__jt_tags (
				          `catid`,
					      `tag`,
					      `freq`
					      )
					      VALUES (
					      ".$result->catid.",
					      '".$t."',
					      1)";
			      $db->setQuery($sql); 
			      $db->execute();
			    }else {
			           $sql="UPDATE #__jt_tags SET `freq`=(`freq`+1) WHERE `tag`='".$t."' limit 1";
			           $db->setQuery($sql); 
				       $db->execute();
			    }
			 }
           
          }
      }


	 
  }

 //ensure fetch only one same tag from one article. From v2.3
 public function stripSameTags($t,$tagsfilter) {
   foreach($tagsfilter as $x) {
           if(strtolower($t)==strtolower($x)) {
		      return true;
		   }
   }
   return false; 
 }

 //get tags from database (from v2.3)
 function getTags(&$params)
 {
    global $mainframe;
    $db=JFactory::getDBO();
    $tagcloud_params=new stdClass();
    $tagcloud_params->tagssum=0;
    $tagcloud_params->tagsarray=array();	
    $tagcloud_params->tagsstyle=array();
    $catid=$params->get('catid');
    $expcatid=$params->get('expcatid');
    $blacklist=explode(",",$params->get('blacklist')); 
	$w_flag=true;
    if($catid[0]!='') {
       $catidquery=implode("' OR catid='",$catid);
       $catidquery="WHERE catid='".$catidquery."'";
    } else {
	   $catidquery="";
	   $w_flag=false;
    }
	 
   if($expcatid[0]!='') {
	  $expcatidquery=implode("' AND catid <>'",$expcatid);
	  if($w_flag) {
	     $expcatidquery=" AND catid<>'".$expcatidquery."'";
	  }else {
	     $expcatidquery="WHERE catid<>'".$expcatidquery."'";
		 $w_flag=true;
	  } 
   } else {
	  $expcatidquery="";
   }
 
   if(!(count($blacklist)==1&&$blacklist[0]=='')) {
      $blacklistquery=implode("' AND tag <>'",$blacklist);
	  if($w_flag) {
	     $blacklistquery=" AND tag<>'". $blacklistquery."'";
	  }else {
	     $blacklistquery="WHERE tag<>'". $blacklistquery."'";
		 $w_flag=true;
	  }
   } else {
      $blacklistquery="";
   }
   /*  
   $sql="SELECT SUM(`freq`) AS sum FROM #__jt_tags";
   $db->setQuery($sql); //get the sum of the frequency to calculate the font size
   $result=$db->loadObject();   
   $tagssum=$result->sum;
   */
   
   
   $sql="SELECT * FROM #__jt_tags ".$catidquery.$expcatidquery.$blacklistquery." ORDER BY freq desc"." LIMIT 0,".$params->get('maxtags'); //in case can't enable to get enough tags 

   $db->setQuery($sql);   
   $queryresult=$db->loadObjectlist();

   
   
   // set the tags order. From v2.3,bug fixed in Nov.18,2012
  
   switch($params->get('tags_order')) {
         case 0:
		 $sortresult=self::tagSort($queryresult,0);
		 break;
		 case 1:
		 $sortresult=self::tagSort($queryresult,1);
		 break;
		 case 2:
		 $sortresult=self::tagSort($queryresult,2);
		 break;
		 case 3:
		 $sortresult=self::tagSort($queryresult,3);		 
   } 
   // Get the sum of frequence of tags bug fixed in Nov.26,2012
   foreach ($sortresult as $sumcounter) {
         $tagssum+=$sumcounter->freq;
   }
   

   foreach($sortresult as $result)
	 {
	   if(!in_array($result->tag,$blacklist)) { //ignore the tags in blacklist
          $tagcloud_params->tagsarray[$result->tag]=$result->freq;
          $style=modTagcloudhelper::fontsizeCal($result->freq,$tagssum,$params);	
	      $tagcloud_params->tagsstyle[$result->tag]=$style;   
	   }
     }

     $tagcloud_params->tagssum=$tagssum;
	
	// set color
	 if($params->get('tagscolor')=='') {
	    $color="";
	 } else{	  		
	        $color="color: ".$params->get('tagscolor');
     }
	 
	// set hover color
	 if($params->get('tagshovercolor')=='') {
	    $hovercolor="";
	 } else{	  		
	        $hovercolor="color: ".$params->get('tagshovercolor');
     }	
	  
	// set underline
	if($params->get('show_underline'))
	  {
       $show_underline="underline";
	   }
	   else
	     {
           $show_underline="none";
		 }
		 
	//set underline when mouse hover
	if($params->get('hover_show_underline'))
	  {
       $hover_show_underline="underline";
	   }
	   else
	     {
           $hover_show_underline="none";
		 }

	//set tags align
	switch($params->get('tags_align'))
	      {
		  case 0:
		  $tags_align="left";
		  break;
		  case 1:
		  $tags_align="justify";
		  break;
		  case 2:
		  $tags_align="right";
		  break;
		  default:
		  $tags_align="center";
		} 	 
		 
	//set line height
	$line_height=$params->get('line_height')==""?"20px":$params->get('line_height');
	
    //set margin
	$horizontal_space=$params->get('horizontal_space')==""?"2px":$params->get('horizontal_space');
		
	
	//set search window
	$searchwindow=$params->get('searchwindow')==0?"_blank":"_self";
	
	//set colorful tags
	$colorfultags=$params->get('colorful_tags');
	

		 
    $tagcloud_params->show_underline=$show_underline;
	$tagcloud_params->hover_show_underline=$hover_show_underline;
    $tagcloud_params->color=$color;	
	$tagcloud_params->align=$tags_align;  
    $tagcloud_params->lineheight=$line_height;
	$tagcloud_params->margin=$horizontal_space;	
	$tagcloud_params->searchtype=$searchtype;	
	$tagcloud_params->searchwindow=$searchwindow;
	$tagcloud_params->colorfultags=$colorfultags;
	$tagcloud_params->hovercolor=$hovercolor;	
	$tagcloud_params->displaylimit=$params->get('displaylimit');
	$tagcloud_params->charslimit=$params->get('charslimit');	
return $tagcloud_params;
 }

 public function tagSort($result,$type) {
    $sort_array=Array();
	$sort_array2=Array();
	$sort_array3=Array();	
	$merge_array=Array();
	if($type==1) {
	    return $result; //no need to sort
	}else {
        foreach($result as $i=>$r) {
	        $sort_array[]=$r->tag;
	        $sort_array2[]=$r->tag;		
			$sort_array3[]=$r->freq;	
		}			      
	}	      

	
	if($type==0) { //sort by tag name
		natcasesort($sort_array);
	    foreach($sort_array as $i=>$a) {
           
        $merge_array[]=$result[$i];
		
		   

	    }	
	
		   
	}
	elseif($type==2) { //sort by freq asc
		natcasesort($sort_array3);
	    foreach($sort_array3 as $i=>$a) {
		   $merge_array[]=$result[$i];
	    }		
	}
	elseif($type==3) { //sort randomly
	
		  shuffle($sort_array2);
	      foreach($sort_array2 as $a) {
	         $key=array_keys($sort_array,$a);
		     $merge_array[]=$result[$key[0]];
	      }		  
	}
		

	return $merge_array;
 }

 public function fontsizeCal($n,$m,&$params)
 {
    //Caculate the font size based on the radio,but not bigger than the max font size or smaller than the min font size 
	$k=$params->get('coefficient');
    $radio=number_format(($n/$m),2);
	$fontsize=$radio*$k>$params->get('maxfontsize')?$params->get('maxfontsize'):($radio*$k<$params->get('minfontsize')?$params->get('minfontsize'):$radio*$k);
    return $fontsize;
 }


}
?>
