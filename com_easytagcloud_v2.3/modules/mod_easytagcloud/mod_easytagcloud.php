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


// Include the syndicate functions only once

require_once (dirname(__FILE__).DS.'helper.php');


//Check if the module need to be initialized
  $db=JFactory::getDBO();
  
  
  $sql="SELECT * FROM #__jt_record";
  $db->setQuery($sql); 
  $result=$db->loadObject(); 
  if(is_null($result)) { //not exist,create tables
     modTagcloudHelper::rebuilddb(2);
	 modTagcloudHelper::init($params);
     } elseif($result->version!='2.3') { //not v2.3,rebuilt tables
              modTagcloudHelper::rebuilddb(2);    
		      modTagcloudHelper::init($params);
	   }else { //check the updatetime,decide to init or not
	          $updatetime=$result->updatetime; 
			  $currenttime=time();
			  if(($currenttime-$updatetime)>=($params->get('interval')*3600)||empty($updatetime)) {
			     modTagcloudHelper::rebuilddb(1);				 
				 modTagcloudHelper::init($params);
              }
	   } 

	   
  $moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
  $easytagcloud_params=modTagcloudHelper::getTags($params);
  require(JModuleHelper::getLayoutPath('mod_easytagcloud'));

?>