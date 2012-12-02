<?php 
// no direct access
 defined('_JEXEC') or die('Restricted access');
?>

<style type="text/css">
<!--
div#easytagcloud<?php echo $moduleclass_sfx.'_'.$module->id; ?> a:link {
	text-decoration: <?php echo $easytagcloud_params->show_underline; ?>;
	<?php echo $easytagcloud_params->color; ?>
}
div#easytagcloud<?php echo $moduleclass_sfx.'_'.$module->id; ?> a:visited {
	text-decoration: <?php echo $easytagcloud_params->show_underline; ?>;
	<?php echo $easytagcloud_params->color; ?>
}

div#easytagcloud<?php echo $moduleclass_sfx.'_'.$module->id; ?> a:hover {
	text-decoration: <?php echo $easytagcloud_params->hover_show_underline; ?>;
    <?php echo $easytagcloud_params->hovercolor; ?>
}

div#easytagcloud<?php echo $moduleclass_sfx.'_'.$module->id; ?> a {
	margin-left: <?php echo $easytagcloud_params->margin; ?>;
	margin-right: <?php echo $easytagcloud_params->margin; ?>;	
}

div#easytagcloud<?php echo $moduleclass_sfx.'_'.$module->id; ?> {
    line-height: <?php echo $easytagcloud_params->lineheight; ?>;
}


-->
</style>
<div id="easytagcloud<?php echo $moduleclass_sfx.'_'.$module->id; ?>" style="text-align:<?php echo $easytagcloud_params->align; ?>">
<?php
 foreach($easytagcloud_params->tagsarray as $key=>$value) 
  {    
   $app =& JFactory::getApplication();
   if($value==1)
     {
     $tip=Jtext::_('MOD_EASYTAGCLOUD_RELATED_ARTICLE');
	 }else 
	    {
		  $tip=Jtext::_('MOD_EASYTAGCLOUD_RELATED_ARTICLES');
		}
		//colorful tags
		 if($easytagcloud_params->colorfultags==0)
		   {
		   $tagcolor="color:#".dechex(rand(0,16777215));
		   }
		   else 
		    {
			 $tagcolor="";
			}
			
	    //$tagurl=JRoute::_('index.php?option=com_search&amp;Itemid=1&amp;submit=Search&amp;searchphrase='.$easytagcloud_params->searchtype.'&amp;ordering=newest');
		$tagurl='index.php?option=com_easytagcloud&t='.urlencode($key);
		$tagurl.='&limit='.$easytagcloud_params->displaylimit.'&chars='.$easytagcloud_params->charslimit;
		$tagurl=JRoute::_($tagurl);
	    $searchphrase="<a href='".$tagurl."' style='font-size:".$easytagcloud_params->tagsstyle[$key]."em;".$tagcolor."' title='".$value." $tip' target='".$easytagcloud_params->searchwindow."'>".$key."</a>";   
	  
   echo $searchphrase;
   echo " "; 
  } 
 ?>
</div>