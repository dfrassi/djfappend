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


/**
 * field_type_detail  Controller
 *
 * @package		Joomla
 * @subpackage	field
 * @since 1.5
 */
class field_type_detailController extends JController {
	
	/**
	 * Custom Constructor
	 */
	function __construct($default = array()) {
		parent::__construct ( $default );
		
		// Register Extra tasks
		$this->registerTask ( 'add', 'edit' );
	
	}
	
	function edit() {
		
		JRequest::setVar ( 'view', 'field_type_detail' );
		JRequest::setVar ( 'layout', 'form' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();
		// give me  the field
		$model = $this->getModel ( 'field_type_detail' );
		$model->checkout ();
	
	}
	
	/**
	 * Funzione di salvataggio
	 *
	 */
	function save() {
		
		$post = JRequest::get ( 'post' );
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$post ['id'] = $cid [0];
		$name = $post ['name'];
		$queryN = 'select name from #__djfappend_field_type where lower(name) = lower("' . $name . '")';
		//echo($queryN);
		$fieldtype = utility::getArray ( $queryN );
		foreach ( $fieldtype as $questoField ) {
			$msg = JText::_ ( 'NAME_ALREADY_PRESENT' );
			echo ($queryN);
			$this->setRedirect ( 'index.php?option=com_djfappend&controller=field_type_detail', $msg );
			return false;
		}
		
		$model = $this->getModel ( 'field_type_detail' );
		$msg = "";
		if ($model->store ( $post )) {
			$msg = JText::_ ( 'Field Type Saved' );
		} else {
			$msg = JText::_ ( 'Error Saving Field Type' );
		}
		$model->checkin ();
		$this->setRedirect ( 'index.php?option=com_djfappend&controller=field_type', $msg );
	
	}
	
	/** 
	 * function remove
	 */
	
	function remove() {
		global $mainframe;
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'Select an item to delete' ) );
		}
		$model = $this->getModel ( 'field_type_detail' );
		foreach ( $cid as $id ) {
			
			$queryN = 'select id from #__djfappend_field_value where id_field_type = ' . $id;
			
			$fieldtype = utility::getArray ( $queryN );
			foreach ( $fieldtype as $questoField ) {
				$msg = $id.' - '. JText::_ ('IN_USE' );
				echo ($queryN);
				$this->setRedirect ( 'index.php?option=com_djfappend&controller=field_type', $msg );
				return false;
			}
			$queryN = 'select id from #__djfappend_field where id_field_type = ' . $id;
		
			$fieldtype = utility::getArray ( $queryN );
			foreach ( $fieldtype as $questoField ) {
				$msg = $id.' - '. JText::_ ('IN_USE' );
				echo ($queryN);
				$this->setRedirect ( 'index.php?option=com_djfappend&controller=field_type', $msg );
				return false;
			}
			if (! $model->delete ( $cid )) {
				echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
			}
		}
		
		
		
		$this->setRedirect ( 'index.php?option=com_djfappend&controller=field_type' );
	}
	
	/** 
	 * function cancel
	 */
	
	function cancel() {
		// Checkin the detail
		$model = $this->getModel ( 'field_type_detail' );
		$model->checkin ();
		$this->setRedirect ( 'index.php?option=com_djfappend&controller=field_type' );
	}

}
