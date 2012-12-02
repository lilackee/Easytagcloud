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

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the Easytagcloud Component
 */
class EasyTagCloudViewEasyTagCloud extends JView
{
	// Overwriting JView display method
	function display($tpl = null) 
	{
		// Assign data to the view
		$this->msg=$this->get('Msg');
		//$this->total=$this->get('Total');
		$total=$this->get('Total');
		$pagination=$this->get('Pagination');
				
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		$this->assignRef('total',$total);
		$this->assignRef('pagination',$pagination);
		
		// Display the view
		parent::display($tpl);
	}
}
