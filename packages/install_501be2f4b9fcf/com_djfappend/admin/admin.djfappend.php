<?php
/**
* @package HelloWorld 02
* @version 1.5
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software and parts of it may contain or be derived from the
* GNU General Public License or other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/


// controllo che il componente venga chiamato soltanto da joomla
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once (JPATH_PLUGINS.DS.'system'.DS.'djflibraries'.DS.'utility.php');
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_banners'.DS.'tables');
$mainframe =& JFactory::getApplication(); global $context;
$document = & JFactory::getDocument();

$document->addStyleSheet ( 'components/com_djfappend/assets/css/djfappend.css' );

$controllerName = JRequest::getVar('controller');



$activeField=false;
$activeType=false;
$activeValue=false;
$activeBackup=false;

if ($controllerName=='field') $activeField = true; 
if ($controllerName=='field_type') $activeType = true;
if ($controllerName=='field_value') $activeValue = true;
if ($controllerName=='loader') $activeBackup = true;

JSubMenuHelper::addEntry(JText::_('CAMPI'), 'index.php?option=com_djfappend&controller=field', $activeField );
JSubMenuHelper::addEntry(JText::_('TIPOLOGIE'), 'index.php?option=com_djfappend&controller=field_type', $activeType );
JSubMenuHelper::addEntry(JText::_('VALORI'), 'index.php?option=com_djfappend&controller=field_value',$activeValue);
JSubMenuHelper::addEntry(JText::_('DOBACKUP'), 'index.php?option=com_djfappend&controller=loader',$activeBackup);

// questo è il controller di default se non ne viene selezionato alcuno
$controller = JRequest::getVar('controller','field_type' );

// indirizza il controller giusto
require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');

// Create the controller
$classname  = $controller.'controller';

//create a new class of classname and set the default task:display
$controller = new $classname( array('default_task' => 'display') );

// Perform the Request task
$controller->execute( JRequest::getVar('task' ));

// Redirect if set by the controller
$controller->redirect();
?>
