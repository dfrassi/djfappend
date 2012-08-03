<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );
/**
 * djfappend plugin
 * @package djfappend
 * @Copyright (C) 2007, 2008 Jonathan M. Cameron, All Rights Reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link http://joomlacode.org/gf/project/djfappend/frs/
 * @author Jonathan M. Cameron
 **/

$mainframe->registerEvent ( 'onAfterDispatch', 'adddjfappendStyleSheet' );
$mainframe->registerEvent ( 'onPrepareContent', 'adddjfappend' );
jimport ( 'joomla.plugin.plugin' );
require_once ('components/com_djfappend/controllers/field_detail.php');

// was: $mainframe->registerEvent('onAfterDisplayContent', 'adddjfappend');


function adddjfappendStyleSheet() {
	$document = & JFactory::getDocument ();
	$document->addStyleSheet ( JURI::base () . 'plugins/content/djfappend.css', 'text/css', null, array () );
}


function djfappend_djfappendButtonsHTML($article_id, $Itemid, $from) {
	
	$lang = & JFactory::getLanguage ();
	$lang->load ( 'plg_djfappend', JPATH_ADMINISTRATOR );
	
	//echo ("sono qui djfappend_djfappendButtonsHTML");
	$document = & JFactory::getDocument ();
	$document->addScript ( JURI::root ( true ) . '/media/system/js/modal.js' );
	JHTML::_ ( 'behavior.modal', 'a.modal-button' );
	$url = "index.php?option=com_djfappend&controller=field_detail&task=add&id_jarticle=$article_id&tmpl=component";
	if ($from) {
		$url .= "&from=closeme";
	}
	$url = JRoute::_ ( $url );
	$icon_url = JURI::Base () . 'components/com_djfappend/media/sout.gif';
	$add_djfappend_txt = JText::_ ( 'APPEND_ATTRIBUTE' );
	$ahead = '<a class="modal-button" type="button" href="' . $url . '" ';
	$ahead .= "rel=\"{handler: 'iframe', size: {x: 450, y: 250}}\">";
	$links = "$ahead<img src=\"$icon_url\" alt=\"$add_djfappend_txt\" /></a>";
	$links .= $ahead . $add_djfappend_txt . "</a>";

	// commentato da quando esiste la gestione interna
	//return "\n<div class=\"appendaattribute\" style=\"margin-left:-2px;\">$links</div>\n";


}

function adddjfappend(&$row, &$params, $page = 0) {
	
	$option = JRequest::getCmd('option');
	
	if ($option != 'com_content')
		return;
	if (! isset ( $row->id ))
		return;
	
	jimport ( 'joomla.application.component.helper' );
	
	$user = & JFactory::getUser ();
	$gid = $user->get ( 'gid' );
	$lang = & JFactory::getLanguage ();
	$lang->load ( 'plg_djfappend', JPATH_ADMINISTRATOR );
	
	$from = JRequest::getVar ( 'view', false );
	$Itemid = JRequest::getVar ( 'Itemid', false );
	
	if (is_numeric ( $Itemid ))
		$Itemid = intval ( $Itemid );
	else
		$Itemid = 1;
	
	if (! empty ( $plugin->params )) {
		$pluginParams = new JParameter ( $plugin->params );
		$gid_consentito = $pluginParams->def ( 'access_level', 18 );
	}
	
	if (empty ( $user_can_add ))
		$user_can_add = "";
	
	$row->text .= djfappend_djfappendListHtml ( $row->id, $user_can_add, $Itemid, $from );
	
	if ($gid > 0)
		$row->text .= djfappend_djfappendButtonsHTML ( $row->id, $Itemid, $from );

}

function djfappend_djfappendListHTML($article_id, $user_can_add, $Itemid, $from) {
	
	$lang = & JFactory::getLanguage ();
	$lang->load ( 'plg_djfappend', JPATH_ADMINISTRATOR );
	$query = "select id from #__djfappend_field where published = 1 and id_jarticle = " . $article_id;
	$listaField = utility::getQueryArray ( $query );
	$ritorno = "";
	if (! empty ( $listaField )){
		$ritorno = "\n<div style=\"clear:both;\" ></div>
		<div class=\"appendattribute\" style=\"margin-top:5px;width:100%;\">
		<div style=\"color:green;margin-bottom:0;padding-bottom:0;width:100%;\"><b>" . JText::_ ( 'ATTRIBUTES' ) . ":</b></div>
		<table style=\"background-color: transparent; border: 1px solid #C0C0C0;margin-top:0;width:100%;\">";
	
	foreach ( $listaField as $row ) {
		
		$thisM = new Multimedia ( $row->id );
		$nomeImg = $thisM->icon;
		$mode = $thisM->mode;
		$linkbutton = $thisM->filelink;
		$filevalore = $thisM->field_name;
		$label = $thisM->label;
		$valore = $thisM->valore;
		if (!empty($label) && ($label != $valore)){ 
			
			$valore = $valore." (".$label.")";
			if($thisM->options == "map" ||$thisM->options == "vimeo" ||$thisM->options == "youtubeplaylist" ||$thisM->options == "youtube"  ) $valore = $thisM->label;
			
		}
		$data = $thisM->data_evento;
		$iconaRender = "";
		$urlremove = "";
		$delete_img = "";
		$linkbutton = $thisM->showlink;
		$iconaRender = $thisM->getIconPerPopup();
		$ritorno .= "<tr>
			<td style=\"width:80%\">
				<div style='margin-right:5px;float:left;'>" . $iconaRender . "</div>
				<div>" . $filevalore . " - <b>" . $valore . "</b></div>
			</td>" . " 
			<td style=\"width:20%;white-space: nowrap;text-align:right;\">" .
				 JHTML::_ ( 'date', $data, '%d-%m-%Y' ) . 
				 "</td>";
		$ritorno .= "</tr>";
	}
	$ritorno .= "</table></div>";
	}
	$view = JRequest::getVar ( "view" );
	if ($view != "article")
		$ritorno = "";
	
	return $ritorno;
}

?>
