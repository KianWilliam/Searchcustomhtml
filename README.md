# Searchcustomhtml
Finder Plugin searchcustomhtml version 1.0.0 , system-updatecustomindex and extension-updatecustomindex, 
system-updateindexer, system-updatemodulesview, module-updatecustomlist
are free softwares which are developed by KWProductions Co.
The license is GNU/GPLv3
It is written for joomla 4.x, 
In order for custom html to be indexed by smartsearch component, the main plugin (finder-searchcustomhtml) must be enabled
which is done on installation of the package, yet you check by yourself.
In order for your custom modules to be re-indexed after update of the custom module, the plugins 
(extension-updatecustomindex and system-updatecustomindex) must be enabled, the latter gives message to remind you of the necessary condition
to be chosen and set in the custom module backend so that the custom-module get updated. The condition to be set is that:
You MUST only select the option : ONLY ON SELECTED PAGES option from selected options, under menu tab of a custom module 
in order for your custom module to be indexed and reindexed. For each language better to check ONLY the menuitems
for that language to get the optimum result! We advise you before the installation of the package set all customhtml modules
of your site according to the mentioned condition, then install the package, after that, employ com_smartsearch and index,
then in case of any update in your custom modules the plugins shall update the index the items .
In the pro-version there is no need to set the customhtml modules manually, everything will be done automatically.
The package is tested with general options of com_smartsearch but not all of them , therefore in case of any problem let us know.
Also taxonomy is not tested either, yet highly likely there will be no problem
updateindexer is a system plugin which adds a line of code to Indexer.php file of com_finder component
updatemodulesview is a system plugin too which adds a line of code to modules view of com_modules component,
In this version whenever the modules' view page is loaded or reloaded in the backend, the process of updating indexes of custom modules
 takes place based on the publish and unpublish state of custom htmls,
 ATTENTION:  THIS PACKAGE WORKS FROM JOOMLA ADMINISTRATOR FOR A REASONABLE AMOLUNT OF MODULES AND/OR MENUITEMS, FOR LOTS OF 
 MENUTIEMS AND MODULES IN A WEBSITE, 1-ADD TO THE MEMORY LIMIT (e.g memory_limit=512M) in the php.ini or from your host 2-IT HAD BETTER 
 EMPLOY THE JOOMLA CLI TO INDEX/REINDEX OR PURGE. 3-IN CASE OF DELETING/ADDING/PUBLISHING/UNPUBLISHING A MENU ITEM YOU HAVE TO REINDEX OR INDEX AFTER PURGE, IN THE PRO-VERSION SHALL BE DONE AUTOMATICALLY!
In the pro-version updating indexes occurs only when the publish or unpublish buttons are checked and the user makes the page 
become reloaded or when the user employes publish or unpublish button for each custom html individually
If the structure of these core files(Indexer.php and default.php of com_modules view) are changed by Joomla,
we are here to update these plugins with new versions but
For the time being update of joomla has no effect on functions of these two plugins.
updatecustomlist is a module plugin to index and update the custom modules on being published or unpublished 
Best way is to fix the condition for custom modules before installation of the package, after
installation employ smart search component to index then you may go to modules list and custom modules individually to alter
any settings.Also Cache (Broswer or server side) might sometimes prevent from proper results, in that case clear it.
In the Pro-version all custom modules shall be fixed according to the condition mentioned above!
you may download the extension @:
https://www.extensions.kwproductions121.ir/myplugins/searchcustomhtml.html<br />
In case of any problem contact me at:
webarchitect@kwproductions121.ir<br />
https://github.com/KianWilliam/Searchcustomhtml <br />
long live science.
