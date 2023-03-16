<?php 

/**

 
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
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\Session\Session;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Component\Finder\Administrator\Indexer\Adapter;
use Joomla\Component\Finder\Administrator\Indexer\Helper;
use Joomla\Component\Finder\Administrator\Indexer\Indexer;
use Joomla\Component\Finder\Administrator\Indexer\Result;
use Joomla\Database\DatabaseInterface;
use Joomla\Registry\Registry;

class PlgModuleUpdatecustomlist extends CMSPlugin
{		
	    protected $autoloadLanguage = true;
		protected $app;
		protected $db;


    public function onAfterInitialise()
	{
		

		$this->loadLanguage();
	}
	   
			

	
		public function onAfterModuleList($modules)
		{
			
			if(!$this->app->isClient('site')):
			$db=$this->db;
				$query = $db->getQuery(true);

	 $query = "CREATE TABLE IF NOT EXISTS `#__mod_custom_titles` ( `id` int(10)  unsigned NOT NULL auto_increment,`module_id` int(10)  unsigned,`title` varchar(255) NOT NULL,  PRIMARY KEY  (`id`))";
			$db->setQuery($query);
			$db->execute();
		
				foreach($modules as $module):
			
				if($module->module==="mod_custom")
				{
					
					$query = $db->getQuery(true);
					$query->select('*')->from($db->quoteName('#__mod_custom_titles'))->where($db->quoteName('module_id').'='.$db->quote($module->id));
					$db->setQuery($query);
					$r = $db->loadObject();
					if($r===NULL){
					$query = $db->getQuery(true);
                    $columns = array('module_id', 'title');
                    $values = array($db->quote($module->id), $db->quote($module->title));
                    $query->insert($db->quoteName('#__mod_custom_titles'))->columns($db->quoteName($columns))->values(implode(',', $values));

                    $db->setQuery($query);
                    $db->execute();
					}
					else
					{
						$query = $db->getQuery(true);
						$value = array($db->quoteName('title').'='.$db->quote($module->title));
						$condition = array($db->quoteName('module_id').'='.$db->quote($module->id));
						$query->update($db->quoteName('#__mod_custom_titles'))->set($value)->where($condition);
						$db->setQuery($query);
						$db->execute();
					}

				}
			endforeach;
			
			
			
			
			
			
			
			$items = [];
			$flag = $this->app->input->cookie->get("flag");
			
		
			if($flag!=="haha" && $modules!==NULL && count($modules)>0 ){
				
				
			foreach($modules as $module):
			
				if($module->module==="mod_custom")
				{
					 $mp = PluginHelper::importPlugin('finder','searchcustomhtml');
			//  $myplugin=PluginHelper::getPlugin('finder','searchcustomhtml');
			          $dispatcher = Factory::getApplication()->getDispatcher();
				 $plgobj = new PlgFinderSearchCustomhtml($dispatcher, array('name'=>'searchcustomhtml', 'group'=>'finder', 'params'=>'', 'language'=>$module->language));
	  			$db = $this->db;


			 // $plgobj = new PlgFinderSearchCustomhtml($dispatcher, array('name'=>'searchcustomhtml', 'group'=>'finder', 'params'=>'', 'language'=>$data->language));

			
			
			$query = $db->getQuery(true);
			     $query->select('a.id, a.access, a.params ') 
                 ->select($db->quoteName('a.content', 'body')) 
				 ->select($db->quoteName('a.module', 'module'))  				 
				  ->select($db->quoteName('a.language', 'language'))                 			 
                 ->select($db->quoteName('a.title', 'title'))  
				 ->select($db->quoteName('a.published', 'state'))  
                ->select($db->quoteName('mm.menuid', 'menuid'))  
                 ->select($db->quoteName('me.link', 'link')) 
                 ->select($db->quoteName('me.alias', 'alias')) 					 
               ->from($db->quoteName('#__modules', 'a'))	
               ->join('LEFT', $db->quoteName('#__modules_menu','mm') .' ON '. $db->quoteName('a.id').' = '. $db->quoteName('mm.moduleid') )	
               ->join('LEFT', $db->quoteName('#__menu','me') .' ON '. $db->quoteName('me.id').' = '. $db->quoteName('mm.menuid') )	
	          ->where($db->quoteName('a.module').' = '.$db->quote('mod_custom'))
			  ->where($db->quoteName('a.title') . ' = ' . $db->quote($module->title))
				 ->where(' mm.menuid != 0')
			  ->where('me.published = 1');	
			  			 
			
            $db->setQuery($query);

             $results = $db->loadObjectList();
			
			 
		foreach($results as $result):

              		
$Result = new Result();

    $Result->link = $result->link;

  	$identity = $result->menuid;
		
 $Result->title=$result->title ;
 $Result->module = $result->module;
 

		$Result->body = $result->body;
		$Result->params = $result->params;
			
 
 
 $Result->state=$result->state;
 
 $Result->access=$result->access;
 $Result->language = $result->language;
 
 $query = $db->getQuery(true);
 $query->select('*')->from($db->quoteName('#__finder_types'))->where($db->quoteName('title').'='.$db->quote('Custom'));
 $db->setQuery($query);
 $findertype=$db->loadObject();
 $Result->type_id=$findertype->id;
 $Result->id = $result->id;
 $Result->menuid = $result->menuid;
 $Result->alias = $result->alias;
			 //   $databaseinterface = Factory::getContainer()->get(DatabaseInterface::class);
				//	JLoader::registerAlias('Indexer', 'Joomla\\Component\\Finder\\Administrator\\Indexer');
			//	if($Result!==NULL)						
               $plgobj->getIndex($Result);
	//  Factory::getApplication()->triggerEvent('onFinderAfterSave', array('mod_custom.custom', $Result, false));
		
	
		
            endforeach;

				}
			
			endforeach;
			}
			else
			  if($flag==="haha"){
			               $this->app->input->cookie->set("flag", "Jaque");
		
			  }
		
				
			
			
			endif;
			
		
		
			
       
		}
        

		


}
