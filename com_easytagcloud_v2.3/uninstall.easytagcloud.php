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

jimport('joomla.installer.installer');
// Load Easytagcloud file
$lang = &JFactory::getLanguage();

$lang->load('com_easytagcloud');
$db = & JFactory::getDBO();
$query = "SELECT `extension_id` FROM `#__extensions` WHERE `type`='module' AND element ='mod_easytagcloud' ";
$db->setQuery($query);
$id = $db->loadResult();
if($id) {
   $installer = new JInstaller;
   $result = $installer->uninstall('module', $id);
} else {
    $result=true; 
}    
?>
<div style="color: #FF0000;font-weight:bold;">
<p><?php echo 'Easytagcloud '.JText::_('EASYTAGCLOUD_COMPONENT').' '.($result?JText::_('EASYTAGCLOUD_UNINSTALLED'):JText::_('EASYTAGCLOUD_NOT_UNINSTALLED')); ?></p>
<p><?php echo 'Easytagcloud '.JText::_('EASYTAGCLOUD_MODULE').' '.($result?JText::_('EASYTAGCLOUD_UNINSTALLED'):JText::_('EASYTAGCLOUD_NOT_UNINSTALLED')); ?></p>
</div>