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

class field_type_detailVIEWfield_type_detail extends JView
{
	/**
	 * Display the view
	 */
	function display($tpl = null)
	{
		global $mainframe, $option;
		JToolBarHelper::title(   JText::_( 'djfappend - Gestione Tipologie' ), 'typology' );

	
		$uri 		=& JFactory::getURI();
		$user 	=& JFactory::getUser();
			$model	=& $this->getModel();
		$this->setLayout('form');
		$lists = array();
		$detail	=& $this->get('data');
		$isNew		= ($detail->id < 1);

		
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('field') );
		$document->addStyleSheet ( 'components/com_djfappend/assets/css/icon.css' );
		$document->addStyleSheet ( 'components/com_djfappend/assets/css/general.css' );
		$document->addStyleSheet ( 'components/com_djfappend/assets/css/modal.css' );
		$document->addStyleSheet ( 'components/com_djfappend/assets/css/menu.css' );
		$text = $isNew ? JText::_( 'NEW' ) : JText::_( 'EDIT' );
		JToolBarHelper::title('Djf Append - '.JText::_( 'TYPOLOGY_MANAGEMENT' ).': <small><small>[ ' . $text.' ]</small></small>','addedit' );
		JToolBarHelper::save();
		
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		if (!$isNew)
		{
			$model->checkout( $user->get('id') );
		} else {
			$detail->id_jarticle=0;
			$detail->field_name="";
			$detail->field_value="";
			
		}
	
		jimport('joomla.filter.filteroutput');
		JFilterOutput::objectHTMLSafe( $detail, ENT_QUOTES, 'description' );
		$this->assignRef('isNew',			$isNew);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}		

}


?>
