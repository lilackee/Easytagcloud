<?php
/*
 * Update Tags Element
 *
 * @package		Easytagcloud
 * @version		2.3
 * @author		Kee 
 * @copyright	Copyright (c) 2012 www.joomlatonight.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 */

defined('_JEXEC') or die ('Restricted access');


		

jimport('joomla.form.formfield');


class JFormFieldUpdate extends JFormField
{
	protected $type = 'Update';
	
	function getInput()
	{   
	    global $mainframe;
        $db=JFactory::getDBO();
        $sql="SELECT * FROM #__jt_record";
        $db->setQuery($sql); 
        $result=$db->loadObject(); 	
		$nextupdatetime=($result->updatetime+$this->value*3600-time())*1000;
		//echo "updatetime:".$result->updatetime."<br/>Interval:".($this->value*3600)."<br/>This time:".time();
		$this->_includeAssets();  
		$uri=$this->_getRootAssetsUri();	
		$id = str_replace(array('[', ']'), array('_', ''), $this->name);	
		$updatequery=$uri.'updatequery.php?task=update';
		echo '<script type="text/javascript">updateLans=new Array(3);updateLans[0]="'.JText::_('MOD_EASYTAGCLOUD_UPDATEREADY').'";updateLans[1]="'.JText::_('MOD_EASYTAGCLOUD_UPDATING').'";updateLans[2]="'.JText::_('MOD_EASYTAGCLOUD_UDPATEDONE').'";</script>';

				
		return '<input type="text" name="'.$this->name.'" id="'.$id.'" value="'.$this->value.'"/> <div style="float:left;clear:both;font-weight:bold">Time to update: <span id="showcountdown" style="color:#ff0000;"></span></div><button type="button" id="updatenow" onClick="updateNow(\''.$updatequery.'\')" style="float:left;clear:both;">Update Now!</button><script>window.onload=initUpdateTime('.$nextupdatetime.');</script>';      
	}
	
	function _includeAssets()
	{
		static $loaded;
		
		if ($loaded)
			return ;
			
		$uri = $this->_getRootAssetsUri();
			
		$document =& JFactory::getDocument();
		$document->addScript($uri . 'update.js');		
		$loaded = true;
	}
	
	function _getRootAssetsUri()
	{
		static $uri;
		
		if (!is_null($uri))
			return $uri;
		
		$filePath = str_replace(DS == '\\' ? '/' : '\\', DS, dirname(__FILE__));
		if (strlen(JPATH_ROOT) > 1)
			$filePath = str_replace(JPATH_ROOT, '', $filePath);
			
		$uri = JURI::root(true) . str_replace(DS, '/', $filePath) . '/update/';
		
		return $uri;
	}
}
?>