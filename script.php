<?php
/*
  * @package plugin searchcustomhtml for Joomla! 4.x
 * @version $Id: searchjshopping 1.0.0 2023-01-02 01:10:10Z $
 * @author KWProductions Co.
 * @copyright (C) 2022- KWProductions Co.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 
 This file is part of searchcustomhtml.
    searchcustomhtml is free software: you can redistribute it and/or adify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    searchcustomhtml is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for are details.
    You should have received a copy of the GNU General Public License
    along with searchcustomhtml.  If not, see <http://www.gnu.org/licenses/>. 
*/


defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerScript;

class Pkg_SearchcustomhtmlInstallerScript extends InstallerScript
{
 public function install($parent)
 {
  
   
  $db  = Factory::getDbo();
  $query = $db->getQuery(true);
  $query->update('#__extensions');
  $query->set($db->quoteName('enabled') . ' = 1');
  $query->where($db->quoteName('element') . ' = ' . $db->quote('searchcustomhtml'));
  $query->where($db->quoteName('type') . ' = ' . $db->quote('plugin'));
  $db->setQuery($query);
  $db->execute(); 
  
   $query = $db->getQuery(true);
  $query->update('#__extensions');
  $query->set($db->quoteName('enabled') . ' = 1');
  $query->where($db->quoteName('element') . ' = ' . $db->quote('updatecustomindex'));
  $query->where($db->quoteName('type') . ' = ' . $db->quote('plugin'));
  $db->setQuery($query);
  $db->execute(); 
  
    $query = $db->getQuery(true);
  $query->update('#__extensions');
  $query->set($db->quoteName('enabled') . ' = 1');
  $query->where($db->quoteName('element') . ' = ' . $db->quote('updateindexer'));
  $query->where($db->quoteName('type') . ' = ' . $db->quote('plugin'));
  $db->setQuery($query);
  $db->execute(); 
  
    $query = $db->getQuery(true);
  $query->update('#__extensions');
  $query->set($db->quoteName('enabled') . ' = 1');
  $query->where($db->quoteName('element') . ' = ' . $db->quote('updatecustomlist'));
  $query->where($db->quoteName('type') . ' = ' . $db->quote('plugin'));
  $db->setQuery($query);
  $db->execute(); 
  
    $query = $db->getQuery(true);
  $query->update('#__extensions');
  $query->set($db->quoteName('enabled') . ' = 1');
  $query->where($db->quoteName('element') . ' = ' . $db->quote('updatemodulesview'));
  $query->where($db->quoteName('type') . ' = ' . $db->quote('plugin'));
  $db->setQuery($query);
  $db->execute(); 
  
  		$query = $db->getQuery(true);
        $query->select('*')->from($db->quoteName('#__finder_types'))->where($db->quoteName('title').'='.$db->quote('Custom'));
		$db->setQuery($query);
		$result = $db->loadObject();
		
		if($result===NULL):
   
		$query = $db->getQuery(true);

$columns = array('title', 'mime');


$values = array($db->quote('Custom'), $db->quote(''));

$query
    ->insert($db->quoteName('#__finder_types'))
    ->columns($db->quoteName($columns))
    ->values(implode(',', $values));

$db->setQuery($query);
$db->execute();
     endif;
	 	
	
   
  
 }
   public function uninstall($parent) 
  {
	   $db  = Factory::getDbo();
	       
       $query = "DROP IF EXISTS TABLE `#__mod_custom_titles`";
	   $db->setQuery($query);
			$db->execute();
			
		$query = $db->getQuery(true);
        $query->select('*')->from($db->quoteName('#__finder_types'))->where($db->quoteName('title').'='.$db->quote('Custom'));
		$db->setQuery($query);
		$result = $db->loadObject();
		
		if($result!==NULL):
				$query = $db->getQuery(true);
                $query->delete($db->quoteName('#__finder_types'))->where($db->quoteName('title').'='.$db->quote('Custom'));
				$db->setQuery($query);
				$db->execute();
		endif;
  }
}
