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
class Tablefield_detail extends JTable {
	
	var $id = null; // int
	var $id_jarticle = 0; // int
	var $id_field_type = 0; // int
	var $field_value = ''; // varchar	
	var $event_date = '0000-00-00 00:00:00'; // varchar
	var $checked_out = 0; // int
	var $checked_out_time = '0000-00-00 00:00:00'; // datetime
	var $published = 0; // int
	var $ordering = null;
	var $filename = null;
	var $filename_sys = null;
	var $file_type = null;
	var $file_size = null;
	var $url = null;
	var $display_name = null;
	var $x = null;
	var $y = null;
	 
	
	

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function Tablefield_detail(& $db) {
		//initialize class property
		$this->_table_prefix = '#__djfappend_';
		
		parent::__construct ( $this->_table_prefix . 'field', 'id', $db );
	}
	
	
	function check()
	{

		/** check for valid name */
		if (trim($this->id) == '') {
			$this->_error = JText::_('YOUR Field MUST CONTAIN A id.');
			return false;
		}

			/** check for existing name */
		$query = 'SELECT id FROM '.$this->_table_prefix.'field  WHERE id = '.$this->id;
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
