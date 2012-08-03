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
defined( '_JEXEC' ) or die( 'Restricted access' );

//DEVNOTE: import VIEW object class
jimport( 'joomla.application.component.view' );
jimport('joomla.application.component.helper');




/**
 [controller]View[controller]
 */

class fieldViewgroup extends JView
{

	function __construct( $config = array()){
	 
			$mainframe =& JFactory::getApplication(); global $context;
		
	 	//$context = 'field.list.';
	 	parent::__construct( $config );
	 	$total=0;
	 	$limit = 0;
		$limitstart = 0;
		
	}

	function display($tpl = null)
	{
		$mainframe =& JFactory::getApplication(); global $context;
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('Group') );
		$document->addStyleSheet('components/com_djfappend/assets/css/icon.css');
		$document->addStyleSheet('components/com_djfappend/assets/css/general.css');
		$document->addStyleSheet('components/com_djfappend/assets/css/modal.css');
		$document->addStyleSheet('components/com_djfappend/assets/css/template.css');
		$document->addStyleSheet('components/com_djfappend/assets/css/menu.css');
 		$document->addScript( JURI::root(true).'/includes/js/joomla.javascript.js');
		$uri	=& JFactory::getURI();		

		$filter_order = $mainframe->getUserStateFromRequest ( $context . 'filter_order', 'filter_order', 'ordering' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest ( $context . 'filter_order_Dir', 'filter_order_Dir', '' );
		
		$lists ['order'] = $filter_order;
		$lists ['order_Dir'] = $filter_order_Dir;
	
		$items			= & $this->get( 'Data');
		$total			= & $this->get( 'Total');

		
		$mainframe =& JFactory::getApplication();
		$params = &$mainframe->getParams();
		$categoria = JRequest::getVar('catid');
		$categoriaQuery = "";
		if ($categoria != "0") $categoriaQuery = " and cat.id = $categoria";
		$field_1 = JRequest::getVar('field_1');
		$field_2 = JRequest::getVar('field_2');
		$field_3 = JRequest::getVar('field_3');
		$field_4 = JRequest::getVar('field_4');
		$fields = array();
		if ($field_1 != "") array_push($fields,$field_1);
		if ($field_2 != "") array_push($fields,$field_2);
		if ($field_3 != "") array_push($fields,$field_3);
		if ($field_4 != "") array_push($fields,$field_4);
		
		$querymia = "select distinct df.id_jarticle, c.title 
		from #__djfappend_field df, #__content c, #__categories cat 
		where c.id = df.id_jarticle
		and c.catid = cat.id
		"
		.$categoriaQuery;
		
		$items = $this->getList ( $querymia, $this->limitstart, $this->limit );
		
		//$items = utility::getArray($querymia);
		$this->total = sizeof($items);
		
		$pagination = $this->getPagination();	
		
		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('lists',		$lists);
		$this->assignRef('fields',		$fields);
		$this->assignRef('items',		$items);	
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('itemid',	JRequest::getVar('Itemid'));
		$this->assignRef('request_url',	$uri->toString());		
		$this->assignRef('search', JRequest::getVar('search'));
		
		parent::display($tpl);
	}
	
	function getPagination() {
			$mainframe =& JFactory::getApplication(); global $context;
		
		if (empty ( $this->_pagination )) {
			jimport ( 'joomla.html.pagination' );
			$this->limit = $mainframe->getUserStateFromRequest ( $context . 'limit', 'limit', $mainframe->getCfg ( 'list_limit' ), 0 );
			$this->limitstart = $mainframe->getUserStateFromRequest ( $context . 'limitstart', 'limitstart', 0 );
			
			//$this->setState ( 'limit', $limit );
			//$this->setState ( 'limitstart', $limitstart );
			
			$pagination = new JPagination ( $this->total, $this->limitstart, $this->limit );
		}
		return $pagination;
	}
	
	function getList( $query, $limitstart=0, $limit=0 )
	{
		$mainframe =& JFactory::getApplication();
		$db = & JFactory::getDBO ();
		$db->setQuery( $query, $limitstart, $limit );
		$result = $db->loadObjectList();
		return $result;
	}
}
?>
