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
?>
<div class="easytagcloud-resultscounter"><?php echo $this->pagination->getResultsCounter(); ?></div>
<dl class="easytagcloud-related-articles">
<?php foreach($this->msg as $result) : ?>
	           <dt class="easytagcloud-title">
               <a href="<?php echo JRoute::_($result->href); ?>">
               <?php echo $this->escape($result->title);?>
			   </a>
               </dt>
               <dd class="easytagcloud-text">
			   <?php echo $result->text; ?>
	           </dd>
               <dd class="easytagcloud-created">
			   <?php echo JText::sprintf('JGLOBAL_CREATED_DATE_ON', $result->created); ?>
		       </dd>
               
<?php endforeach; ?>
</dl>    
<div class="pagination">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
<div style="text-align: center;"><img src="<?php echo JURI::root(true); ?>/media/easytagcloud/assets/easytagcloud16X16.png" width="16px" height="16px" title="Easytagcloud" /><a href="http://www.joomlatonight.com" target="_blank" title="Easytagcloud v2.3" style="font-size: 12px;margin-left: 5px;">Easytagcloud v2.3</a></div>