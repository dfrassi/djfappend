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
jimport ( 'joomla.application.component.model' );

class fieldModelfield extends JModel {
	
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	var $_table_prefix = null;
	
	function __construct() {
		parent::__construct ();
		global $mainframe, $context;
		$this->_table_prefix = '#__djfappend_';
		
		//DEVNOTE: Parametri di paginazione
		$limit = $mainframe->getUserStateFromRequest ( $context . 'limit', 'limit', $mainframe->getCfg ( 'list_limit' ), 0 );
		$limitstart = $mainframe->getUserStateFromRequest ( $context . 'limitstart', 'limitstart', 0 );
		$this->setState ( 'limit', $limit );
		$this->setState ( 'limitstart', $limitstart );
	}
	
	/**
	 * Method to get a field data
	 *
	 * questo metodo è chiamato da ogni proprietario della vista
	 */
	
	function getData() {
		if (empty ( $this->_data )) {
			$query = $this->_buildQuery ();
			$this->_data = $this->_getList ( $query, $this->getState ( 'limitstart' ), $this->getState ( 'limit' ) );
		}
		
		
		return $this->_data;
	}
	
	/**
	 * Il metodo restituisce il numero totale di righe del modulo
	 */
	
	function getTotal() {
		if (empty ( $this->_total )) {
			$query = $this->_buildQuery ();
			$this->_total = $this->_getListCount ( $query );
		}
		return $this->_total;
	}
	
	/**
	 * Method to get a pagination object for the field
	 */
	
	function getPagination() {
		if (empty ( $this->_pagination )) {
			jimport ( 'joomla.html.pagination' );
			$this->_pagination = new JPagination ( $this->getTotal (), $this->getState ( 'limitstart' ), $this->getState ( 'limit' ) );
		}
		return $this->_pagination;
	}
	
	/**
	 * Metodo che effettua la query vera e propria sul db
	 */
	
	function _buildQuery() {
		

		$search = "";
		$post = JRequest::get ( 'post' );
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$post ['id'] = $cid [0];
		$query_search="";
		$orderby = $this->_buildContentOrderBy (); // costruisce l'order by (vedi sotto)
	
		if (isset ( $post ['search'] )) {
			$search = $post ['search']; // se c'è un parametro search settato.
		}
		
		if ($search != "")
			$query_search = " 
		and (
		h.id = " . $search . "
		or ft.name like '%" . $search . "%'
		or a.title like '%" . $search . "%'
		or h.field_value like '%" . $search . "%'
		or h.event_date like '%" . $search . "%'
		
		) 		
		";
		else
			$query_search = "";
		
		$query = ' SELECT h.*, ft.name field_type, a.title title FROM ' . $this->_table_prefix . 'field as h, #__content as a, #__djfappend_field_type as ft where ft.id = h.id_field_type and a.id = h.id_jarticle '. $query_search . $orderby;
		//echo ($query);
		

		return $query;
	}
	
	/**
	 * Costruisce l'order by automatico su colonna
	 */
	
	function _buildContentOrderBy() {
		global $mainframe, $context;
		
		$filter_order = $mainframe->getUserStateFromRequest ( $context . 'filter_order', 'filter_order', 'ordering' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest ( $context . 'filter_order_Dir', 'filter_order_Dir', '' );
		
		if ($filter_order == 'h.ordering') {
			$orderby = ' ORDER BY ordering ';
		} else {
			$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir . ' , h.ordering ';
		}
		return $orderby;
	}

}

?>

