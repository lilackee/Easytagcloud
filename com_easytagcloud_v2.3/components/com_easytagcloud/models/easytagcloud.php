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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * EasyTagCloud Model
 */
class EasyTagCloudModelEasyTagCloud extends JModelItem
{
	/**
	 * @var string msg
	 */
	protected $msg=null;
	var $_total=null;
	var $_pagination = null;

	/**
	 * Get the message
	 * @return string The message to be displayed to the user
	 */
	public function getMsg() 
	{
		require_once JPATH_SITE . '/components/com_content/helpers/route.php';		
		$db=JFactory::getDbo();
		$app=JFactory::getApplication();
		$this->setState('limit', JRequest::getUInt('limit', 20));
		$this->setState('limitstart', JRequest::getUInt('limitstart', 0));		
		//$nullDate=$db->getNullDate();
		//$date=JFactory::getDate();
		//$now=$date->toSql();	
		$keyword=urldecode(JRequest::getVar('t'));
	    $keyword=$db->Quote('%'.$db->escape($keyword, true).'%', false);		
		$where= 'a.metakey LIKE '.$keyword;
		$where.='AND a.state=1';
		$order = 'a.created DESC';				
				
		$query=$db->getQuery(true);	
		$query->clear();
		
		//CASE WHEN
		$case_when = ' CASE WHEN ';
		$case_when .= $query->charLength('a.alias');
		$case_when .= ' THEN ';
		$a_id = $query->castAsChar('a.id');
		$case_when .= $query->concatenate(array($a_id, 'a.alias'), ':');
		$case_when .= ' ELSE ';
		$case_when .= $a_id.' END as slug';
		$case_when1 = ' CASE WHEN ';
		$case_when1 .= $query->charLength('c.alias');
		$case_when1 .= ' THEN ';
		$c_id = $query->castAsChar('c.id');
		$case_when1 .= $query->concatenate(array($c_id, 'c.alias'), ':');
		$case_when1 .= ' ELSE ';
		$case_when1 .= $c_id.' END as catslug';
		
	    $query->select('a.title AS title, a.metakey, a.created AS created, '
		.$query->concatenate(array("a.introtext", "a.fulltext")).' AS text,'
	    .'c.title AS section,'.$case_when.','.$case_when1.', '.'\'2\' AS browsernav');		
	    $query->from('#__content AS a');
		$query->innerJoin('#__categories AS c ON c.id=a.catid');	
		$query->where('('. $where .')');
		$query->group('a.id, a.title, a.metakey, a.created, a.introtext, a.fulltext, c.title, a.alias, c.alias, c.id');
		$query->order($order);		
		$db->setQuery($query);
		$list=$db->loadObjectList();
		
		if (isset($list)) {
			$i=0;
			foreach($list as $key=>$item) {
			        //Get accurate results,ignore the "similar" results.FROM v2.3
					$mtags=explode(",",$item->metakey); 
					if(self::exactTag(urldecode(JRequest::getVar('t')),$mtags)) {
					   $i++;
					}else {
					   unset($list[$key]);
					   continue;
					}
									
					$list[$key]->href=ContentHelperRoute::getArticleRoute($item->slug, $item->catslug);
					$list[$key]->text=self::smartSubstr(self::stripAllTags($item->text),JRequest::getUInt('chars', 200));

		    }
		    $this->_total=$i;				
		}
		if ($this->getState('limit')>0) {
			$list=array_splice($list, $this->getState('limitstart'), $this->getState('limit'));
			} 	
		return $list;

	
		
	}
	
	public function exactTag($t,$mtags) {
        foreach($mtags as $x) {
            $x=trim($x);
            if(strtolower($t)==strtolower($x)) {
		      return true;
		    }
        }
        return false; 
    }
	
	function getTotal() {
		return $this->_total;
	}
	
	function getPagination() {
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_pagination;
	}
	
	
	public function stripAllTags($text) {
	  	$text = preg_replace("'<script[^>]*>.*?</script>'si", "", $text);
		$text = preg_replace('/{.+?}/', '', $text);
			// replace line breaking tags with whitespace
		$text = preg_replace("'<(br[^/>]*?/|hr[^/>]*?/|/(div|h[1-6]|li|p|td))>'si", ' ', $text);
		return strip_tags($text);	
	}
	
	
	public function smartSubstr($text,$len) {
	    if(JString::strlen($text)<=$len) {
		    return $text;
		}
		for($i=$len;$i<JString::strlen($text);$i++) {
		    if(JString::substr($text,$i-1,1)==' ') {
			   return JString::trim(JString::substr($text,0,$i))."...";
			 }  
		}
		return $text;
	    
	}
}

?>
