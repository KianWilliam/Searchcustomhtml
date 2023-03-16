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



class PlgSystemUpdatemodulesview extends CMSPlugin
{		
	    protected $autoloadLanguage = true;
		protected $app;
		protected $db;


    public function onAfterInitialise()
	{
				//$file = JPATH_SITE. '\administrator\components\com_modules\tmpl\modules\default.php';
				$file =  dirname(__DIR__, 3).'/administrator/components/com_modules/tmpl/modules/default.php';

				$allcontent = file_get_contents($file);
				
				if(!preg_match('/updatecustomlist/', $allcontent)):

		$this->loadLanguage();
		 		 $stream = new Stream();

		   
 $fn = $stream->open($file);
 $lines = [];
 $i=0;
 $flag=0;
 while(!$stream->eof()){
   $lines[$i]=$stream->gets();	

	 if(preg_match("/Session/", $lines[$i]) && $flag==0){
		 
			 $lines[$i] .= "use Joomla\CMS\Plugin\PluginHelper;PluginHelper::importPlugin('module','updatecustomlist');Factory::getApplication()->triggerEvent('onAfterModuleList', array(\$this->items));";

		
		
		
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

