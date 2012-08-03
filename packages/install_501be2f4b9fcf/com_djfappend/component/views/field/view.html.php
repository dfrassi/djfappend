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

class fieldViewfield extends JView
{

	function __construct( $config = array()){
	 
		global $context;
	 	$context = 'field.list.';
	 	parent::__construct( $config );
	}

	function display($tpl = null)
	{
		$mainframe =& JFactory::getApplication(); global $context;
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('field') );
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
		
		$pagination = & $this->get( 'Pagination' );	
		$id_field_type = Jrequest::getVar('id_field_type');
		$lists ['tipi'] = $this->Tipi( 'id_field_type', null, 'onChange="sub();"', 'id', 1, $id_field_type );

		$query = "select distinct cat.id as value, cat.title as text 
		from #__categories cat, 
		#__content con, 
		#__djfappend_field fie where fie.id_jarticle = con.id and cat.id = con.catid order by 2";
		
		
		$catid = JRequest::getVar("catid");
		$lists ['categorie'] = utility::getSelectExt ($query, 'catid', 'catid', $catid, 'onChange="sub();"', false );
		
		$queryAnni = "select distinct year(event_date) as value, year(event_date) as text from #__djfappend_field order by 2";
		
		
		
		
		$year = JRequest::getVar("anno");
		
		$lists ['anni'] = utility::getSelectExt ($queryAnni,  'anno', 'anno', $year, 'onChange="sub();"', false );
	
		
		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('lists',		$lists);
		$this->assignRef('items',		$items);			
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('itemid',	JRequest::getVar('Itemid'));	
		$this->assignRef('request_url',	$uri->toString());		
		$this->assignRef('search', JRequest::getVar('search'));
		
		
		
		
		
		
		$this->assignRef('catid', JRequest::getVar('catid'));
		$this->assignRef('id_field_type', JRequest::getVar('id_field_type'));
		$this->assignRef('anno', JRequest::getVar('anno'));
		
		
		
		
		
		
		parent::display($tpl);
	}
	
	function Tipi($name, $active = NULL, $javascript = NULL, $order = 'name', $size = 1, $sel_desc = 1) {
		$mainframe =& JFactory::getApplication();
		$model = & $this->getModel ();
		$tipi [] = JHTML::_ ( 'select.option', '0', '- ' . JText::_ ( 'Seleziona una tipologia' ));
		$tipi = array_merge ( $tipi, $model->getField_type ( $order ) );	
		$post = JRequest::get ( 'post' );
		$id_field_type = JRequest::getVar('id_field_type');		
		if (count ( $tipi ) < 1) {
			$mainframe->redirect ( 'index.php?option=com_djfappend', JText::_ ( 'Devi prima creare una tipologia.' ) );
		}		
		$tipo = JHTML::_ ( 'select.genericList', $tipi, $name, 'class="inputbox" size="' . $size . '" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $id_field_type );
		return $tipo;
	}	
	
	function Anni($name, $active = NULL, $javascript = NULL, $order = 'name', $size = 1, $sel_desc = 1) {
		$mainframe =& JFactory::getApplication();
		$model = & $this->getModel ();
		$anni [] = JHTML::_ ( 'select.option', '0', '- ' . JText::_ ( 'Seleziona anno' ));
		$anni = array_merge ( $anni, $model->getEvent_date ( $order ) );	
		$post = JRequest::get ( 'post' );
		$anni = JRequest::getVar('anni');		
		$anno = JHTML::_ ( 'select.genericList', $anni, $name, 'class="inputbox" size="' . $size . '" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $event_date );
		return $anno;
	}	
}
?>
