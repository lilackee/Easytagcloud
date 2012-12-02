<?php
/*
 * Titile Element
 *
 * @package		Easytagcloud
 * @version		2.3
 * @author		Kee 
 * @copyright	Copyright (c) 2012 www.joomlatonight.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 * @javascript script from www.jscolor.com v1.4.0
 */

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldTitle extends JFormField
{
	protected $type = 'Title';
	
	function getInput()
	{
        $value= JText::_($this->value);
		return '<div style="float: left;width: 100%;font-weight: bold;font-size: 1.3em;color: #fff;background-color: #7EA5EB;padding: 3px;text-align: center">'.$value.'</div>';      
	}
	
	
}
?>