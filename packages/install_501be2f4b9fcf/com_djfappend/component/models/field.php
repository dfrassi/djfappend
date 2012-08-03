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
	var $_query = null;
	
	function __construct() {
		parent::__construct ();
		$mainframe =& JFactory::getApplication(); global $context;
		$this->_table_prefix = '#__djfappend_';
		
		$config = JFactory::getConfig ();
		$this->setState ( 'limit', $mainframe->getUserStateFromRequest ( 'com_weblinks.limit', 'limit', $config->getValue ( 'config.list_limit' ), 'int' ) );
		
		$limitstart = JRequest::getVar('limitstart');
		$limit = JRequest::getVar('limit');
		
		if ($limit!="")$this->setState ( 'limitstart', 0);
		else 
		$this->setState ( 'limitstart', JRequest::getVar ( 'limitstart',0, '', 'int' ) );
		//$this->setState ( 'limitstart', ($this->getState ( 'limit' ) != 0 ? (floor ( $this->getState ( 'limitstart' ) / $this->getState ( 'limit' ) ) * $this->getState ( 'limit' )) : 0) );
		}
	
	/**
	 * Method to get a field data
	 *
	 * questo metodo è chiamato da ogni proprietario della vista
	 */
	
	function getData() {
		if (empty ( $this->_data )) {
			$query = $this->_buildQuery ();
			$this->_query = $query;
			$this->_data = $this->_getList ( $query, $this->getState ( 'limitstart' ), $this->getState ( 'limit' ) );
		}
		return $this->_data;
	}
	
	/**
	 * Il metodo restituisce il numero totale di righe del modulo
	 */
	
	function getTotal() {
		if (empty ( $this->_total )) {
			//$query = $this->_buildQuery ();
			$query = $this->_query;
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
		$mainframe =& JFactory::getApplication();
		
		$orderby = $this->_buildContentOrderBy (); // costruisce l'order by (vedi sotto)
		
		$search = "";
		$post = JRequest::get ( 'post' );
		$id_field_type = JRequest::getVar('id_field_type');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$post ['id'] = $cid [0];
		$query_search="";
		//$session =& JFactory::getSession('search');
		//$search = $session->get('search',0,'search');
		//$search = 
		//echo($search);
		if (isset ( $post ['search'] )) {
			$search = $post ['search']; // se c'è un parametro search settato.
		}	
			
		if ($search==""){
			$search=JRequest::getVar('search');					
									
		}
		
		
		$params = &$mainframe->getParams();
		
		$anno = $params->get( 'anno');
		
		$id_category = $params->get( 'catid');
	
		if ($id_category==""){
			$id_category=JRequest::getVar('catid');					
									
		}
		
		if ($id_category=="" || $id_category=="0"){
			$query_category_search = "";					
									
		}else{
			$query_category_search = " and (a.catid = ".$id_category.")";
			
		}
		
		
		
		
		
		
		
		$tipologia = $params->get( 'tipologia');
	
		if ($tipologia==""){
			$tipologia=JRequest::getVar('tipologia');					
									
		}
		
		if ($tipologia==""){
			$query_tipology_search = "";					
									
		}else{
			$query_tipology_search = " and (ft.name = '".$tipologia."')";
			
		}
		
		$valore = $params->get( 'valore');
	
		if ($valore==""){
			$valore=JRequest::getVar('valore');					
									
		}
		
		if ($valore==""){
			$query_valore_search1 = "";					
			$query_valore_search2 = "";
									
		}else{
			$query_valore_search1 = " and (h.field_value = '".$valore."' )";
			$query_valore_search2 = " and (dfv.valore = '".$valore."' )";
			
		}
		
		
		
		
		//echo("<h1>ygjghjg".$id_category."</h1>");
		
		if (isset ( $post ['anno'] )) {
			$anno = $post ['anno']; // se c'è un parametro search settato.
		}	
			
		if ($anno=="" ){
			$anno=JRequest::getVar('anno');					
									
		}
		
		/*
		 * 
		 * 
		 * 
		 */

		if ($search != "")
			$query_search = " 
		and (
		 ft.name like '%" . $search . "%'
		or a.title like '%" . $search . "%'
		or h.field_value like '%" . $search . "%'
					
		) 		
		"; else $query_search = "";
			
			
			
		if ($anno != "" && $anno!="0")
			$query_anno_search = " 
		and (
		year(h.event_date) = " . $anno . "
		) 		
		"; else $query_anno_search = "";
			
			
			


		$filtro_type="";
		if ($id_field_type!=null && $id_field_type!='0'){
			$filtro_type = " and h.id_field_type = ".$id_field_type;
		}	
		
			
		$query = '
		(SELECT
		h.ordering,
		dfv.valore as valore1,
		h.id_jarticle,
		year(h.event_date) event_date,
		a.title,
		ft.name field_type
		FROM
		#__djfappend_field as h,
		#__djfappend_field_type as ft,
		#__content as a,
		#__djfappend_field_value dfv
		where h.id=h.id
		and a.id=h.id_jarticle
		and h.id_field_type=ft.id
		'.	$query_search .
		$query_tipology_search.
		$query_category_search.
		$query_valore_search2.
		$query_anno_search . 
		$filtro_type. 
		'
		AND dfv.id_field_type = ft.id
		and dfv.id = h.field_value)
		union
		(SELECT h.ordering, h.field_value as valore1, h.id_jarticle,
		year(h.event_date) event_date, a.title,
		ft.name field_type
		FROM #__djfappend_field as h,
		#__djfappend_field_type as ft,
		#__content as a where h.id=h.id
		and a.id=h.id_jarticle and h.id_field_type=ft.id
		'.
		$query_search .
		$query_tipology_search.
		$query_category_search.
		$query_valore_search1.
		
		' and h.field_value not in (select id from #__djfappend_field_value where id = h.field_value)) ';
		
				  		
		
		//$orderby;
		//echo ($query);
		

		return $query;
	}
	
	/**
	 * Costruisce l'order by automatico su colonna
	 */
	
	function _buildContentOrderBy() {
		
		$mainframe =& JFactory::getApplication(); global $context;
		
		$filter_order = $mainframe->getUserStateFromRequest ( $context . 'filter_order', 'filter_order', 'ordering' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest ( $context . 'filter_order_Dir', 'filter_order_Dir', '' );
		
		if ($filter_order == 'h.ordering') {
			$orderby = ' ORDER BY ordering ';
		} else {
			$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir . ' , h.ordering ';
		}
		return $orderby;
	}

	
	function getField_type($order = 'name') {
		$mainframe =& JFactory::getApplication();
		$query = 'SELECT id AS value, name AS text FROM #__djfappend_field_type 
		ORDER BY ' . $order;
		$this->_db->setQuery ( $query );
		return $this->_db->loadObjectList ();
	}	
	
	function getEvent_date($order = 'name') {
		$mainframe =& JFactory::getApplication();
		$query = 'SELECT distinct year(event_date) AS value, year(event_date) as text FROM #__djfappend_field ORDER BY 1';
		//echo($query);
		$this->_db->setQuery ( $query );
		return $this->_db->loadObjectList ();
	}	
}

?>

