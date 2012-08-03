<?php
/**
 * @package HelloWorld
 * @version 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * Joomla! is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

//DEVNOTE: import CONTROLLER object class
jimport ( 'joomla.application.component.controller' );
jimport ( 'joomla.filesystem.archive' );
jimport ( 'joomla.filesystem.file' );



/**
 * helloworld  Controller
 *
 * @package		Joomla
 * @subpackage	helloworld
 * @since 1.5
 */

class loaderController extends JController {
	
	/**
	 * Custom Constructor
	 */
	function __construct($default = array()) {
		parent::__construct ( $default );
	}
	/**
	 * Cancel operation
	 * redirect the application to the begining - index.php
	 */
	function cancel() {
		$this->setRedirect ( 'index.php' );
	}
	
	/**
	 * Method display
	 *
	 * 1) create a helloworldVIEWhelloworld(VIEW) and a helloworldMODELhelloworld(Model)
	 * 2) pass MODEL into VIEW
	 * 3)	load template and render it
	 */
	
	function display() {
		parent::display ();
	}
	
	function saveFile() {
		$mainframe =& JFactory::getApplication();
		$file = JRequest::getVar ( 'upload', null, 'files', 'array' );
		jimport ( 'joomla.filesystem.file' );
		
		//Clean up filename to get rid of strange characters like spaces etc
		if ($_FILES ['upload'] ['size'] > 500000) {
			$mainframe->enqueueMessage ( JText::_ ( 'DIMENSIONE_ERRATA' ), 'error' );
			return false;
		}
		$filename = JFile::makeSafe ( $file ['name'] );
		if (strtolower ( JFile::getExt ( $filename ) ) == 'zip') {
			//Set up the source and destination of the file
			$src = $file ['tmp_name'];
			
			if (JFolder::exists ( JPATH_BASE . DS . "components" . DS . "com_djfappend" . DS . "backup" )) {
				JFolder::delete ( JPATH_BASE . DS . "components" . DS . "com_djfappend" . DS . "backup" );
			}
			JFolder::create ( JPATH_BASE . DS . "components" . DS . "com_djfappend" . DS . "backup" . DS );
			$dest = JPATH_BASE . DS . "components" . DS . "com_djfappend" . DS . "backup" . DS . $filename;
			
			JFile::upload ( $src, $dest );
			return true;
		} else {
			$mainframe->enqueueMessage ( JText::_ ( 'ESTENSIONE_ERRATA' ), 'error' );
			return false;
		}
	}
	
	function restore(){
		
		$mainframe =& JFactory::getApplication();
		utility::restore("djfappend");
		return $this->execute ( 'loader' );
		
		
	}
	
	function backup() {
		
		$mainframe =& JFactory::getApplication();
		utility::backup("djfappend", array('field', 'field_type', 'field_value'));
		return $this->execute ( 'loader' );
	
	}

}
?>
