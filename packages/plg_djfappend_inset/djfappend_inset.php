<?php
/**
 * @version		$Id: djfappend_inset.php 14401 2010-01-26 14:10:00Z louis $
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.plugin.plugin' );

/**
 * Editor djfappend_inset buton
 *
 * @package Editors-xtd
 * @since 1.5
 */
class plgButtondjfappend_inset extends JPlugin {
	
	function plgButtondjfappend_inset(& $subject, $config) {
		parent::__construct ( $subject, $config );
		$this->_plugin = JPluginHelper::getPlugin ( 'editors-xtd', 'djfappend_inset' );
		$this->_params = new JParameter ( $this->_plugin->params );
	}
	
	function onAfterRender() {
		global $mainframe;
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
		$ahead = '<a class="modal-button" style="text-decoration:none;" type="button" href="' . $url . '" ';
		$ahead .= "rel=\"{handler: 'iframe', size: {x: 450, y: 250}}\">";
		$links = "$ahead<img src=\"$icon_url\" alt=\"$add_djfappend_txt\" /></a>";
		$links .= $ahead . " " . $add_djfappend_txt . "</a>";
		return "\n<div class=\"appendaattribute\" style=\"margin-left:-2px;\">$links</div>\n";
	
	}
	
	function onDisplay($name) {
		global $mainframe;
		
		$alist = "";
		$doc = & JFactory::getDocument ();
		$template = $mainframe->getTemplate ();
		$parent_id = JRequest::getVar ( 'id' );
		$getContent = $this->_subject->getContent ( $name );
		$present = JText::_ ( 'ALREADY EXISTS', true );
		$editor = &JEditor::getInstance ();
		$app = &JFactory::getApplication ();
		$nameeditor = $app->getCfg ( 'editor' );
		
		$article_id = JRequest::getVar ( "id" );
		$cid = JRequest::getVar ( "cid" );
		
		$article_cid = $cid[0];
		if (empty($article_id)){
			$article_id = $article_cid;
			$parent_id  = $article_id;
		} 
	
		$arraytoken = explode ( ":", $article_id );
		$article_id = $arraytoken [0];
		$Itemid = JRequest::getVar ( "Itemid" );
		$from = JRequest::getVar ( "Itemid" );

		
		if ($article_id != 0){
		echo ($this->djfappend_djfappendButtonsHTML ( $article_id, $Itemid, true ));
		}

		echo('
		<div>
		<table style="width:100%;border-width:1px;border-color:green;border-style:solid;">');
	
		$js = "
			function insertdjfappend_inset(editor, id) {
				var content = $getContent;
				if (content.match('{djfappend}'+id+'{/djfappend}')) {
					alert('$present');
					return false;
				} else {
					jInsertEditorText('{djfappend}'+id+'{/djfappend}',editor);
					document.getElementById('remove_'+id).style.display='block';
					document.getElementById('delete_'+id).style.display='none';
					document.getElementById('add_'+id).style.display='none';
				}
			}
			";
		
		$doc->addScriptDeclaration ( $js );
			$js = "
			function reloaddjfappend_status(editor, id) {
				var content = $getContent;
				if (content.match('{djfappend}'+id+'{/djfappend}')) {
					document.getElementById('remove_'+id).style.display='block';
					document.getElementById('delete_'+id).style.display='none';
					document.getElementById('edit_'+id).style.display='block';
					document.getElementById('add_'+id).style.display='none';
				} 
					else{
						document.getElementById('remove_'+id).style.display='none';
					document.getElementById('delete_'+id).style.display='block';
					document.getElementById('edit_'+id).style.display='block';
					document.getElementById('add_'+id).style.display='block';
					}
			}
			";
		
		$doc->addScriptDeclaration ( $js );
		$js = "
				function removedjfappend_inset(editor, id) {
					var str = $getContent; 
					var realid = '<p>{djfappend}'+id+'{/djfappend}</p>';
					var newContent = str.replace(realid, '');
					var realid = '{djfappend}'+id+'{/djfappend}';
					var newContent = newContent.replace(realid, '');
					var myIFrame = document.getElementById('text_ifr');
					var content = myIFrame.contentWindow.document.body.innerHTML=newContent;
					//self.refresh();
					document.getElementById('remove_'+id).style.display='none';
					document.getElementById('delete_'+id).style.display='block';
					document.getElementById('edit_'+id).style.display=	'block';
					document.getElementById('add_'+id).style.display='block';
				}
			";
		$doc->addScriptDeclaration ( $js );
		$output = JResponse::getBody ();
		JResponse::setBody ( $output );
		
		require_once (JPATH_PLUGINS . DS . 'system' . DS . 'djflibraries' . DS . 'utility.php');
		$query = "
		
			SELECT * from #__djfappend_field   
				WHERE id_jarticle='" . ( int ) $parent_id . "' order by id_field_type, field_value ";
		
		$risultati = utility::getQueryArray ( $query );
		
			
		
		foreach ( $risultati as $risultato ) {
			$thisM = new Multimedia($risultato->id);
			$path = $thisM->id;
			
			$delete_img = Juri::root().'components/com_djfappend/media/delete.gif';
			
			$add_img = Juri::root().'components/com_djfappend/media/attach_add.png';
			$addLink = '<a title="Inserisci l\'allegato nel testo" id="add_' . $thisM->id . '" href="javascript:noscript();" onclick="insertdjfappend_inset(\'' . $name . '\',' . $thisM->id . ');return false;">
						<img src="' . $add_img . '"/></a>';
			$removeLink = '<a title="Rimuovi l\'allegato dal testo" 
						id="remove_' . $thisM->id . '" 
						href="javascript:noscript();" 
						onclick="removedjfappend_inset(\'' . $name . '\',' . $thisM->id . ');">
						 <img src="' . $delete_img . '"/></a>';
			$macroSign = "{djfappend}" . $thisM->id . "{/djfappend}";
			$queryPerUtilizzo = 'select id as value from #__content where 
				(introtext like \'%' . $macroSign . '%\' or
				`fulltext` like \'%' . $macroSign . '%\') and id = ' . $thisM->id_jarticle;
			
			$scriptx = "reloaddjfappend_status('".$name."',".$thisM->id.")";
			
			
			//utility::onBodyLoad($scriptx);
			// david frassi tolto il 2 maggio 2011 perchè su IE non aggiornava lo status add non add
			
			$idTrovato = utility::getField ( $queryPerUtilizzo );
			$visible_delete = ' style="display:block;" ';
			
			if (empty ( $idTrovato )) {
				$removeLink = '<a style="display:none;" id="remove_' . $thisM->id . '" href="javascript:noscript();" onclick="removedjfappend_inset(\'' . $name . '\',' . $thisM->id . ');"><img src="' . $delete_img . '"/></a>';
			} else {
				$addLink = '<a style="display:none;" id="add_' . $thisM->id . '" href="#/" onclick="insertdjfappend_inset(\'' . $name . '\',' . $thisM->id . ');return false;"><img src="' . $add_img . '"/></a>';
				$visible_delete = ' style="display:none;" ';
			}
			
			// delete file link
			$urlremove = "index.php?option=com_djfappend&controller=field_detail&tmpl=component&id=" . $thisM->id;
			$delete_img = Juri::root().'plugins/system/djflibraries/assets/images/icons/delete.gif';			
			$del_link = '<a '.$visible_delete.' id="delete_' . $thisM->id . '" class="modal-button" type="button" href="' . $urlremove . '&task=remove"';
			
			$app = & JFactory::getApplication ();
			$applicationName = $app->getName ();
			
			if ($applicationName=="administrator") $del_link = '<a '.$visible_delete.' id="delete_' . $thisM->id . '" class="modal-button" type="button" href="' . $urlremove . '&task=remove_detail"';
			
			$del_link .= " rel=\"{handler: 'iframe', size: {x: 450, y: 250}}\"";
			$tooltip = "Delete this Field";
			$del_link .= " title=\"$tooltip\"><img src=\"$delete_img\" alt=\"$tooltip\" /></a>";
			$deleteFileLink = $del_link;
			// fine delete file link
			
			$visible_edit = ' style="display:block;" ';
			
			// edit link
			$urledit = "index.php?option=com_djfappend&controller=field_detail&tmpl=component&id=" . $thisM->id;
			$edit_img = Juri::root().'plugins/system/djflibraries/assets/images/icons/edit.png';			
			$edit_link = '<a '.$visible_edit.' id="edit_' . $thisM->id . '" class="modal-button" type="button" href="' . $urledit . '&task=edit"';
			$edit_link .= " rel=\"{handler: 'iframe', size: {x: 450, y: 250}}\"";
			$tooltip_edit = "Edit this Field";
			$edit_link .= " title=\"$tooltip_edit\"><img src=\"$edit_img\" alt=\"$tooltip_edit\" /></a>";
			$editFileLink = $edit_link;
			// fine edit link
			
			// preview file link
			$urlpreview = "index.php?option=com_djfappend&controller=field_detail&tmpl=component&id=" . $thisM->id;
			$preview_img = Juri::root().'plugins/system/djflibraries/assets/images/icons/preview.gif';
			$preview_link = '<a class="modal-button" type="button" href="' . $urlpreview . '&task=view"';
			$preview_link .= " rel=\"{handler: 'iframe', size: {x: 450, y: 250}}\"";
			$tooltip_preview = "Visualizza";
			$preview_link .= " title=\"$tooltip_preview\"><img src=\"$preview_img\" alt=\"$tooltip_preview\" /></a>";
			$previewFileLink = $preview_link;
			// fine preview file link
			
			// publish file link
			$urlpublish = "index.php?option=com_djfappend&controller=field_detail&tmpl=component&id=" . $thisM->id;
			$publish_img = Juri::root().'plugins/system/djflibraries/assets/images/icons/publish_g.png';
			$publish_link = '<a class="modal-button" type="button" href="' . $urlpublish . '&task=publish"';
			$publish_link .= " rel=\"{handler: 'iframe', size: {x: 450, y: 250}}\"";
			$tooltip_publish = "Mostra in coda all'articolo";
			$publish_link .= " title=\"$tooltip_publish\"><img src=\"$publish_img\" alt=\"$tooltip_publish\" /></a>";
			$publishFileLink = $publish_link;
			// fine publish file link
			
			// unpublish file link
			$urlunpublish = "index.php?option=com_djfappend&controller=field_detail&tmpl=component&id=" . $thisM->id;
			$unpublish_img = Juri::root().'plugins/system/djflibraries/assets/images/icons/publish_r.png';
			$unpublish_link = '<a class="modal-button" type="button" href="' . $urlunpublish . '&task=unpublish"';
			$unpublish_link .= " rel=\"{handler: 'iframe', size: {x: 450, y: 250}}\"";
			$tooltip_unpublish = "Non mostrare in coda all'articolo";
			$unpublish_link .= " title=\"$tooltip_unpublish\"><img src=\"$unpublish_img\" alt=\"$tooltip_unpublish\" /></a>";
			$unpublishFileLink = $unpublish_link;
			// fine unpublish file link
			
			$nameToShow = '<a href="'.$thisM->filelink.'">'.$thisM->label.'</a>';
			
				
			if ($thisM->options != "file")
				$nameToShow = $thisM->label;
			
			
			if($risultato->published == "1") $published = $unpublishFileLink;
			else $published = $publishFileLink;
			
			$baseIconPath = $thisM->baseIconPath;
			$link = "#";
			$nomeImg = "css.gif";
			$icona = '<a href="javascript:noscript();"><img src="'.$baseIconPath.$nomeImg.'"/></a>';
			$realImagePath = $thisM->realImagePath;
			$filename = $thisM->filename;
			$url = $thisM->filelink;
			//$icona = $thisM->iconRender();
			$icona = $thisM->getIconPerPopup();

			$filevalore = $thisM->field_name."->".$thisM->valore;
			
			

			$nomemostrato = $thisM->label;
			if (empty($nomemostrato)) $nomemostrato = $thisM->filename;
			
			
			
			$alist .= '<tr>
					<td style="width:20px;"><b>' . $thisM->id . '</b></td>
					<td style="width:20px;">' . $addLink . $removeLink . '</td>
					<td style="width:20px;">' .$editFileLink.'</td>
					<td style="width:20px;">' . $published . '</td>
					<td style="width:20px;">' . $deleteFileLink . '</td>
					<td style="width:80px;white-space: nowrap;">'.JHTML::_ ( 'date', $thisM->data_evento, '%d-%m-%Y' ) . '</td>
					<td style="width:20%;">' . $filevalore.'</td>
					<td style="width:20px;">' . $icona . '</td>
					<td>' . $nameToShow . '</td>';
					
			echo ("</tr>");
		}
		
		if (! empty ( $risultati )) {
			echo ("
			
			
			<style type=\"text/css\">
				.thappend {
				background-color:#d102fe; 
				color:#FFFFFF;
				}
			</style>
			
				<tr class='thappend'>
			  	<th class='thappend'>Id</th>
    			<th class='thappend' colspan=\"4\" >Azioni</th>
    			<th class='thappend' style=\"width:80px;\">Data</th>
    			<th class='thappend' style=\"width:20px;\">Tipo</th>
    			<th class='thappend' colspan=\"2\" >Anteprima Nome</th>
  				</tr>
  			" . $alist );
		}
		echo("</table><h3></h3>");
		
		$button = new JObject ();
		$button->set ( 'modal', false );
		$button->set ( 'onclick', 'insertdjfappend_inset(\'' . $name . '\');return false;' );
		$button->set ( 'text', JText::_ ( 'djfappend_inset' ) );
		$button->set ( 'name', 'djfappend_inset' );
		$button->set ( 'link', '#' );
	
		//return $button;
	// commentando questo il bottone vero non appare
	}
}