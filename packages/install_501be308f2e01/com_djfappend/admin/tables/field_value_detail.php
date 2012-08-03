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

//DEVNOTE: iInclude library dependencies
jimport ( 'joomla.application.component.model' );

/**
 * helloworld Table class
 *
 * @package		Joomla
 * @subpackage	helloworlds
 * @since 1.0
 */
class Tablefield_value_detail extends JTable {
	
	var $id = null; // int
	var $id_field_type = null; // int
	var $valore = ''; // varchar	
	var $checked_out = 0; // int
	var $checked_out_time = '0000-00-00 00:00:00'; // datetime
	var $published = 0; // int
	

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function Tablefield_value_detail(& $db) {
		//initialize class property
		$this->_table_prefix = '#__djfappend_';
		
		parent::__construct ( $this->_table_prefix . 'field_value', 'id', $db );
	}
	
	
	function check()
	{

		/** check for valid name */
		if (trim($this->id) == '') {
			$this->_error = JText::_('YOUR Field Type MUST CONTAIN A id.');
			return false;
		}

			/** check for existing name */
		$query = 'SELECT id FROM '.$this->_table_prefix.'field_value  WHERE id = '.$this->id;
		$this->_db->setQuery($query);

		$xid = intval($this->_db->loadResult());
		
		/*
		echo "query=$query<BR>";
		var_dump($xid);
		exit;
		*/
		if ($xid && $xid != intval($this->id)) {
			$this->_error = JText::sprintf('WARNNAMETRYAGAIN', JText::_('HELLOWORLD LINK'));
			return false;
		}
		return true;
	}
}
?>
