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
use Joomla\CMS\Filesystem\Stream;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;



class PlgSystemUpdateindexer extends CMSPlugin
{		
	    protected $autoloadLanguage = true;
		protected $app;
		protected $db;


    public function onAfterInitialise()
	{
			//	$file = JPATH_SITE. '\administrator\components\com_finder\src\Indexer\Indexer.php';
							$file =  dirname(__DIR__, 3).'/administrator/components/com_finder/src/Indexer/Indexer.php';

				$allcontent = file_get_contents($file);
				if(!preg_match('/mod_custom/', $allcontent)):

		$this->loadLanguage();
		 		 $stream = new Stream();

 $fn = $stream->open($file);
 $lines = [];
 $i=0;
 $flag=0;
 while(!$stream->eof()){
   $lines[$i]=$stream->gets();	
//don't forget to check if the code is already added   

	 if(preg_match("/serverType/", $lines[$i]) && $flag==0){
	
		        $lines[$i] .= "if(\$item->module!==NULL && preg_match('/mod_custom/', \$item->module)){ \$query = \$db->getQuery(true)->select(\$db->quoteName('link_id') . ', ' . \$db->quoteName('md5sum'))->from(\$db->quoteName('#__finder_links'))->where(\$db->quoteName('url') . ' = ' . \$db->quote(\$item->url))->where(\$db->quoteName('title') . '  LIKE ' . \$db->quote('%'.\$item->title.'%'));}else";
		
		
		
$flag=1;


	 }
	 $i++;
  } 
$stream->close();

 $fn = $stream->open($file, 'w');
 for($i=0; $i<count($lines); $i++)
	 $stream->write($lines[$i]);

$stream->close();


     endif;


	}
	

		
	



}
?>

