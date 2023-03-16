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

//use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\Component\Finder\Administrator\Indexer\Adapter;
use Joomla\Component\Finder\Administrator\Indexer\Helper;
use Joomla\Component\Finder\Administrator\Indexer\Indexer;
use Joomla\Component\Finder\Administrator\Indexer\Result;
use Joomla\Database\DatabaseQuery;
use Joomla\Registry\Registry;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Language\Text;
use Joomla\String\StringHelper;
use Joomla\Database\ParameterType;




class PlgFinderSearchCustomhtml extends Adapter
{
	//try default too as vaule for context

	protected $context = 'Custom';

	
	protected $extension = 'mod_custom';

	

	
	protected $type_title = 'Custom';

	
	protected $table = '#__modules';

	
	protected $state_field = 'published';


	protected $autoloadLanguage = true;

  
   public function getIndex(Result $item)
   {
	    $this->index($item);
   }
	
	public function onAfterInitialise()
	{
		
		$this->loadLanguage();
	}


	public function onFinderAfterDelete($context, $table): void
	{
		if ($context === 'mod_custom.custom')
		{
			$id = $table->id;
		}
		elseif ($context === 'com_finder.index')
		{
			$id = $table->link_id;
		}
		else
		{
			return;
		}

		$this->remove($id);
	}

	
	public function onFinderAfterSave($context, $row, $isNew): void
	{
		
		
		if ($context === 'mod_custom.custom')
		{
			if (!$isNew && $this->old_access != $row->access)
			{
				$this->itemAccessChange($row);
			}

			$this->reindex($row->id);
		}
	

	
	}

	
	public function onFinderBeforeSave($context, $row, $isNew)
	{
	
		if ($context === 'mod_custom.custom')
		{
			if (!$isNew)
			{
				$this->checkItemAccess($row);
			}
		}


		return true;
	}

	
	public function onFinderChangeState($context, $pks, $value)
	{
		if ($context === 'mod_custom.custom')
		{
			$this->itemStateChange($pks, $value);
		}

		if ($context === 'com_plugins.plugin' && $value === 0)
		{
			$this->pluginDisable($pks);
		}
	}

	protected function index(Result $item)
	{
		
		
		

		$item->setLanguage();
	
	

			$registry = new Registry($item->params);

		
				
         

	 
		$identity = $item->menuid;
		$item->title = "(".$identity.")".$item->title ;

		$item->summary = Helper::prepareContent($item->body, $item->params, $item);
			$item->summary = "(".$identity.")".$item->summary;

			
		

		$item->publish_start_date =  Factory::getDate()->toSql();
		$item->start_date= Factory::getDate()->toSql();
		
		       $item->addTaxonomy('Type', 'Custom');

		
        $item->addTaxonomy('Language', $item->language);

		Helper::getContentExtras($item);

		 $item->url =Uri::root().$item->link."&Itemid=".$identity;
		  
		  $item->route = Uri::root().$item->link."&Itemid=".$identity;;
			  
		
		
		
	
		$this->indexer->index($item);
	}

	protected function setup()
	{
		
		return true;
	}

	protected function getListQuery($query = null)
	{
					$db = $this->db;
		 
			 
                 $query =  $db->getQuery(true);         
				 
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
			  	//->where('a.published = 1')
				 ->where(' mm.menuid != 0')
			  ->where('me.published = 1');	

			
              	

		return $query;
	}
	
	
}
