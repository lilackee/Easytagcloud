<?php
/*
 * Update Tags in Backend
 *
 * @package		Easytagcloud
 * @version		2.3
 * @author		Kee 
 * @copyright	Copyright (c) 2012 www.joomlatonight.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 */
// Get Joomla! framework

define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
$parts = explode( DS, dirname(__FILE__) );	

// pop up two levels to the real root
array_pop($parts);
array_pop($parts);
array_pop($parts);
array_pop($parts);
define( 'JPATH_BASE', implode( DS, $parts ) );
require_once ( JPATH_BASE . '/includes'.DS.'defines.php' );
require_once ( JPATH_BASE . '/includes'.DS.'framework.php' );
$mainframe =& JFactory::getApplication('site');
$mainframe->initialise();
        if($_GET['task']=='update') {
		$db =& JFactory::getDBO();
	    $sql="DROP table IF EXISTS #__jt_tags,#__jt_record";
	    $db->setQuery($sql);
	    $db->execute();
		echo "ooo";
}
?>