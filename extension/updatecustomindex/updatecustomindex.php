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
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Factory;
use Joomla\CMS\Editor\Editor;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\WebAsset\WebAssetManager;
use Joomla\CMS\Uri\Uri;
use Joomla\Session\Session;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Component\Finder\Administrator\Indexer\Adapter;
use Joomla\Component\Finder\Administrator\Indexer\Helper;
use Joomla\Component\Finder\Administrator\Indexer\Indexer;
use Joomla\Component\Finder\Administrator\Indexer\Result;
use Joomla\Database\DatabaseInterface;
use Joomla\Registry\Registry;




class PlgExtensionUpdatecustomindex extends CMSPlugin
{		
	    protected $autoloadLanguage = true;
		protected $app;
		protected $db;


    public function onAfterInitialise()
	{


		$this->loadLanguage();
	}
		public function onExtensionBeforeSave($context, $data , $isNew)
		{
			              //necessary in case the title of module is changed completely
						  //to remove recs in finder table with the former name of the module
						  //and former urls too!
                    if(!$isNew && $data->module==="mod_custom"){

			   $db = $this->db;
              $query = $db->getQuery(true);
              $query->select('*')->from($db->quoteName('#__mod_custom_titles'))->where($db->quoteName('module_id').'='.$db->quote($data->id));	
              $db->setQuery($query);
              $r = $db->loadObject();
		  
			  
			  //to update change of urls in custom module, the former ones must be deleted
			  
               $query = $db->getQuery(true);
			$query->select($db->quoteName('link_id'))->from($db->quoteName('#__finder_links'))->where($db->quoteName('title').' LIKE '.$db->quote('%'.$r->title.'%'));
            $db->setQuery($query);
			$results = $db->loadObjectList();
              
              if($results!==NULL && count($results)>0):
              
			$link_ids = [];
			foreach($results as $result):	
			                $query = $db->getQuery(true);
							$query->delete($db->quoteName('#__finder_taxonomy_map'))->where($db->quoteName('link_id').'='.$db->quote($result->link_id));
                            $db->setQuery($query);
							$db->execute();		
			

			endforeach;
              
              	
			$term_ids = [];
			foreach($results as $result):			
	
			   $query = $db->getQuery(true);
			   $query->select($db->quoteName('term_id'))->from($db->quoteName('#__finder_links_terms'))->where($db->quoteName('link_id').' = '.$db->quote($result->link_id));
               $db->setQuery($query);
			   $tids = $db->loadObjectList();
			   foreach($tids as $tid):
			   $termids [] = $tid->term_id;
			   endforeach;			   
			 endforeach;
              
              	        foreach($results as $result):			

							  $query = $db->getQuery(true);
							$query->delete($db->quoteName('#__finder_links_terms'))->where($db->quoteName('link_id').'='.$db->quote($result->link_id));
                            $db->setQuery($query);
							$db->execute();
						 endforeach;
              
              
             for($i=0; $i<count($term_ids); $i++){
				   $query = $db->getQuery(true);
							$query->delete($db->quoteName('#__finder_terms'))->where($db->quoteName('term_id').'='.$db->quote($term_ids[$i]));
                            $db->setQuery($query);
							$db->execute();
							
							 $query = $db->getQuery(true);
							$query->delete($db->quoteName('#__finder_tokens_aggregate'))->where($db->quoteName('term_id').'='.$db->quote($term_ids[$i]));
                            $db->setQuery($query);
							$db->execute();
			 }	
              
              	
			$query = "DELETE FROM #__finder_links WHERE title LIKE '%' '".$r->title."'";
			$db->setQuery($query);
			$db->execute();
		
			
			$query = "DELETE FROM #__assets WHERE title LIKE '%' '".$r->title."' ";;
			$db->setQuery($query);
			$db->execute(); 
			
              
              endif;
					}
		
		}
	
	

	
		public function onExtensionAfterSave($context, $data , $isNew)
		{
			
			
			if($data!==null && $data->module==="mod_custom")
			{
				
			 $this->app->input->cookie->set("flag", "haha");
              $db = $this->db;	
					
			 $mp = PluginHelper::importPlugin('finder','searchcustomhtml');
			          $dispatcher = Factory::getApplication()->getDispatcher();
				 $plgobj = new PlgFinderSearchCustomhtml($dispatcher, array('name'=>'searchcustomhtml', 'group'=>'finder', 'params'=>'', 'language'=>$data->language));
	  			

				
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
			  ->where($db->quoteName('a.title') . ' = ' . $db->quote($data->title))
				 ->where(' mm.menuid != 0')
			  ->where('me.published = 1');	
			  			
			
            $db->setQuery($query);

             $results = $db->loadObjectList();
		
	    if($results !==NULL && count($results)>0) :
		
	 
			 
		foreach($results as $result):

              		
$Result = new Result();

	
	

			$registry = new Registry($result->params);

 
  $in = strpos($result->link, 'index');
  $indexpath = substr($result->link, $in);

  
  $Result->link = $indexpath;
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
	endif;
//Title must be updated  here, in case the user change and save several times the title of custom module without closing the custom module backend!
			
			$query = $db->getQuery(true);
			if($isNew)
			{
				
                    $columns = array('module_id', 'title');
                    $values = array($db->quote($data->id), $db->quote($data->title));
                    $query->insert($db->quoteName('#__mod_custom_titles'))->columns($db->quoteName($columns))->values(implode(',', $values));

                    $db->setQuery($query);
                    $db->execute();
			}
			else
			{
						$value = array($db->quoteName('title').'='.$db->quote($data->title));
						$condition = array($db->quoteName('module_id').'='.$db->quote($data->id));
						$query->update($db->quoteName('#__mod_custom_titles'))->set($value)->where($condition);
						$db->setQuery($query);
						$db->execute();
			}

			
			}
			
			
		
		}
        

		public function onExtensionAfterDelete($context, $table )
		{
			
		if($table->module==="mod_custom")
		{
			$db = $this->db;
			
            $query = $db->getQuery(true);
			$query->select($db->quoteName('link_id'))->from($db->quoteName('#__finder_links'))->where($db->quoteName('title').' LIKE '.$db->quote('%'.$table->title.'%'));
            $db->setQuery($query);
			$results = $db->loadObjectList();
          
             if($results!==NULL && count($results)>0):
		
			$link_ids = [];
			foreach($results as $result):	
			                $query = $db->getQuery(true);
							$query->delete($db->quoteName('#__finder_taxonomy_map'))->where($db->quoteName('link_id').'='.$db->quote($result->link_id));
                            $db->setQuery($query);
							$db->execute();
						
			

			endforeach;
		
			
			
			/*$lids = implode("','", $link_ids);
				$query = "DELETE FROM #__finder_taxonomy_map WHERE link_id  IN ('". $lids."')";
			    $db->setQuery($query);
			    $db->execute();	
				
				$query = "DELETE FROM #__finder_terms WHERE link_id  IN ('". $lids."')";
			    $db->setQuery($query);
			    $db->execute();	*/

				
			$term_ids = [];
			foreach($results as $result):			
	
			   $query = $db->getQuery(true);
			   $query->select($db->quoteName('term_id'))->from($db->quoteName('#__finder_links_terms'))->where($db->quoteName('link_id').' = '.$db->quote($result->link_id));
               $db->setQuery($query);
			   $tids = $db->loadObjectList();
			   foreach($tids as $tid):
			   $termids [] = $tid->term_id;
			   endforeach;			   
			 endforeach;
			
			 				foreach($results as $result):			

							  $query = $db->getQuery(true);
							$query->delete($db->quoteName('#__finder_links_terms'))->where($db->quoteName('link_id').'='.$db->quote($result->link_id));
                            $db->setQuery($query);
							$db->execute();
						 endforeach;

			 
             for($i=0; $i<count($term_ids); $i++){
				   $query = $db->getQuery(true);
							$query->delete($db->quoteName('#__finder_terms'))->where($db->quoteName('term_id').'='.$db->quote($term_ids[$i]));
                            $db->setQuery($query);
							$db->execute();
							
							 $query = $db->getQuery(true);
							$query->delete($db->quoteName('#__finder_tokens_aggregate'))->where($db->quoteName('term_id').'='.$db->quote($term_ids[$i]));
                            $db->setQuery($query);
							$db->execute();
			 }				 

			
			
			$query = "DELETE FROM #__finder_links WHERE title LIKE '%' '".$table->title."'";
			$db->setQuery($query);
			$db->execute();
		
			
			$query = "DELETE FROM #__assets WHERE title LIKE '%' '".$table->title."' ";;
			$db->setQuery($query);
			$db->execute();
			
					 $query = $db->getQuery(true);
							$query->delete($db->quoteName('#__mod_custom_titles'))->where($db->quoteName('title').'='.$db->quote($table->title));
                            $db->setQuery($query);
							$db->execute();
          
          endif;
			
			
			}
		}


}
