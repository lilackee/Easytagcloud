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
$src = $this->parent->getPath('source');
$path = $src.DS.'modules'.DS.'mod_easytagcloud';
$installer = new JInstaller;
$result = $installer->install($path);

?>

<div style="color: #00FF00;font-weight:bold;">
<p><?php echo 'Easytagcloud '.JText::_('EASYTAGCLOUD_COMPONENT').' '.JText::_('EASYTAGCLOUD_INSTALLED'); ?></p>
<p><?php echo 'Easytagcloud '.JText::_('EASYTAGCLOUD_MODULE').' '.($result?JText::_('EASYTAGCLOUD_INSTALLED'):JText::_('EASYTAGCLOUD_NOT_INSTALLED')); ?></p>
</div>