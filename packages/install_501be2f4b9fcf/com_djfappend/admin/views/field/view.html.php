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

class fieldViewfield extends JView
{

	function __construct( $config = array()){
	 
		global $context;
	 	$context = 'field.list.';
	 	parent::__construct( $config );
	}

	/**
	 * Display the view
	 * take data from MODEL and put them into
	 * reference variables
	 *
	 * Go to MODEL, execute Method getData and
	 * result save into reference variable $items
	 * $items		= & $this->get( 'Data');
	 * - getData gets the country list from DB
	 *
	 * variable filter_order specifies what is the order by column
	 * variable filter_order_Dir sepcifies if the ordering is [ascending,descending]
	 */


	function display($tpl = null)
	{
		$mainframe =& JFactory::getApplication(); global $context;
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('field') );
		$document->addStyleSheet ( 'components/com_djfappend/assets/css/icon.css' );
		$document->addStyleSheet ( 'components/com_djfappend/assets/css/general.css' );
		$document->addStyleSheet ( 'components/com_djfappend/assets/css/modal.css' );
		$document->addStyleSheet ( 'components/com_djfappend/assets/css/menu.css' );
		
		JToolBarHelper::title(   JText::_( 'GESTIONE_CAMPI' ), 'field' );				
		
		
		//JToolBarHelper::addNewX();
		//JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
		JToolBarHelper::preferences ( 'com_djfappend', '250' );
		//fieldHelperToolbar::excel();
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
		$search				= $mainframe->getUserStateFromRequest('articleelement.search',				'search',			'',	'string');
		$search				= JString::strtolower($search);
		
		$this->assignRef('search', $search);
		$this->assignRef('pulsanti', fieldHelperToolbar::getToolbar());
		
		parent::display($tpl);
	}
}
?>
