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
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'toolbar.php' );
/**
 [controller]View[controller]
 */

class field_valueViewfield_value extends JView
{

	function __construct( $config = array()){
	 
		global $context;
	 	$context = 'field_value.list.';
	 	parent::__construct( $config );
	}

	function display($tpl = null)
	{
		$mainframe =& JFactory::getApplication(); global $context;
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('field') );
		$document->addStyleSheet ( 'components/com_djfappend/assets/css/icon.css' );
		$document->addStyleSheet ( 'components/com_djfappend/assets/css/general.css' );
		$document->addStyleSheet ( 'components/com_djfappend/assets/css/modal.css' );
		$document->addStyleSheet ( 'components/com_djfappend/assets/css/menu.css' );
		
		JToolBarHelper::title(   JText::_( 'GESTIONE_VALORI' ), 'values' );
		
		JToolBarHelper::addNewX();
		//JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
		JToolBarHelper::preferences ( 'com_djfappend', '250' );

		$uri	=& JFactory::getURI();
		
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'ordering' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );
		
		$lists['order'] 		= $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		
		$items			= & $this->get( 'Data');
		$total			= & $this->get( 'Total');
		
		$pagination = & $this->get( 'Pagination' );
		
		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('lists',		$lists);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('request_url',	$uri->toString());
		
		$this->assignRef('search', JRequest::getVar('search'));
		$this->assignRef('pulsanti', fieldHelperToolbar::getToolbar());
		
		parent::display($tpl);
	}
}
?>
