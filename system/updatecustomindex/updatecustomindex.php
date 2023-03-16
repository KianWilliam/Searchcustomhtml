<?php 

/**
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
 
**/


?>
<?php
defined('_JEXEC') or die;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Factory;
use Joomla\CMS\Editor\Editor;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\WebAsset\WebAssetManager;
use Joomla\CMS\Uri\Uri;

class PlgSystemUpdatecustomindex extends CMSPlugin
{		
	    protected $autoloadLanguage = true;
		protected $app;
		protected $db;


    public function onAfterInitialise()
	{
		
		$this->loadLanguage();
	}
	public function onBeforeRender()
	{
		//$app = Factory::getApplication();
	
	
		if(!$this->app->isClient('site')):
		$input = $this->app->input;
		$extension = $input->get('option');
				$view = $input->get('view');
		$doc = $this->app->getDocument();	
        $alert='alert("In order for your custom module to be reindexed by smart search, from under menu assignment tab ,from module assignment select options, YOU MUST CHOOSE ONLY ON THE PAGES SELECTED OPTION, REMEMBER!!! ")';		

						if($extension==="com_modules" && $view==="modules"){
								$doc->addScriptDeclaration($alert);

						}

		if($extension==="com_modules" && $view==="module"){
												 $this->app->input->cookie->set("flag", "haha");
				$moduleid = $input->get('id');
				$db = $this->db;
						$query = $db->getQuery(true);
						$query->select('*')->from($db->quoteName('#__modules'))->where($db->quoteName('id') .' = '.$db->quote($moduleid));
						$db->setQuery($query);
						$result = $db->loadObject();
						if(($result!==NULL && $result->module==='mod_custom') || ($input->get('eid')==205)){
							
							$doc->addScriptDeclaration($alert);
							
						}
		
		}					
		endif;


	}
		public function onAfterRender()
		{
			
	
		if(!$this->app->isClient('site')):
		$input = $this->app->input;
		$extension = $input->get('option');
				$view = $input->get('view');
					if($extension==="com_modules" && $view==="module"){
							 $this->app->input->cookie->set("flag", "haha");

					}
		endif;
		}

		
	



}
?>

