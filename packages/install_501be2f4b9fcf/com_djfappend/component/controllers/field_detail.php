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

//DEVNOTE: import CONTROLLER object class
jimport ( 'joomla.application.component.controller' );
require_once ('plugins/system/djflibraries/multimedia.php');

/**
 * field_detail  Controller
 *
 * @package		Joomla
 * @subpackage	field
 * @since 1.5
 */
class field_detailController extends JController {
	
	/**
	 * Custom Constructor
	 */
	function __construct($default = array()) {
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	
	}
	
	function rebuildselect() {
		
		$field_type = JRequest::getVar ( 'id_field_type' );
		$valore = JRequest::getVar ( 'valore' );
		$uscita = "";
		if (! isset ( $field_type ))
			$field_type = "1";
		$queryrisultati = "SELECT id AS value, valore as text FROM #__djfappend_field_value where id_field_type = $field_type";
		$risultati = utility::getArray ( $queryrisultati );
		
		$queryPerTipo = 'select * from #__djfappend_field_type where id = ' . $field_type;
		$tipi = utility::getQueryArray ( $queryPerTipo );
		foreach ( $tipi as $questoTipo ) {
			$tipo = $questoTipo->options;
			$nometipo = $questoTipo->options;
			break;
		}
		
		if (! empty ( $risultati )) {
			if (! empty ( $risultati->value ))
				$risvalue = $risultati->value;
			else
				$risvalue = "";
			$querrr = "SELECT id AS value, valore as text FROM #__djfappend_field_value where id_field_type = $field_type ORDER BY trim(valore)";
			$lists ['valori'] = utility::getSelectExt ( $querrr, 'field_value', 'field_value', $risvalue, '', false );
			$uscita = $lists ['valori'];
		
		} 
		
		if (!empty($tipo) && $tipo == "file") {
			$uscita = '<input type="file" name="upload" id="upload"   />
				<input type="hidden" name="update_file" value="TRUE" />
				<input type="hidden" name="filename" value="" />
				<input type="hidden" name="filename_sys" value="" />
				<input type="hidden" name="field_value" id="field_value" value=""/>
				<img src="components/com_djfappend/assets/images/help_16.png" title="'.JText::_('label_file').'"/>';
		}
		
		if (!empty($tipo) && $tipo == "file_url") {
			$uscita = '<input type="text" name="field_value" id="field_value" value="' . $valore . '"/>
				<img src="components/com_djfappend/assets/images/help_16.png" title="'.JText::_('label_file_url').'"/>
				';
		}
		
		if (!empty($tipo) && $tipo == "youtube") {
			$uscita = '<input type="text" name="field_value" id="field_value" value="' . $valore . '"/>
				<img src="components/com_djfappend/assets/images/help_16.png" title="'.JText::_('label_youtube').'"/>
				';
		}

		if (!empty($tipo) && $tipo == "youtubeplaylist") {
		$uscita = '<input type="text" name="field_value" id="field_value" value="' . $valore . '"/>
				<img src="components/com_djfappend/assets/images/help_16.png" title="'.JText::_('label_youtubeplaylist').'"/>
				';
		}
		
		if (!empty($tipo) && $tipo == "vimeo") {
		$uscita = '<input type="text" name="field_value" id="field_value" value="' . $valore . '"/>
				<img src="components/com_djfappend/assets/images/help_16.png" title="'.JText::_('label_vimeo').'"/>
				';
		}

		if (!empty($tipo) && $tipo == "map") {
		$uscita = '<input type="text" name="field_value" id="field_value" value="' . $valore . '"/>
				<img src="components/com_djfappend/assets/images/help_16.png" title="'.JText::_('label_map').'"/>
				';
		}

				if (!empty($tipo) && $tipo == "text") {
		$uscita = '<input type="text" name="field_value" id="field_value" value="' . $valore . '"/>
				<img src="components/com_djfappend/assets/images/help_16.png" title="'.JText::_('label_text').'"/>
				';
		}

						if (!empty($tipo) && $tipo == "list") {
		$uscita = '<input type="text" name="field_value" id="field_value" value="' . $valore . '"/>
				<img src="components/com_djfappend/assets/images/help_16.png" title="'.JText::_('label_list').'"/>
				';
		}
		
		
		echo ($uscita);
	}
	
	function edit() {
		
		JRequest::setVar ( 'view', 'field_detail' );
		JRequest::setVar ( 'layout', 'form' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();
		// give me  the field
		$model = $this->getModel ( 'field_detail' );
		$model->checkout ();
	
	}
	
	function showMedia() {
		$id_field = JRequest::getVar ( 'id_field' );
		$thisM = new Multimedia ( $id_field );
		
		$finale = $thisM->showMediaOnIframe ();
		echo ($finale);
		exit();
	}
	
	/**
	 * Funzione di salvataggio
	 *
	 */
	function save() {
		
		$mainframe =& JFactory::getApplication();
		$post = JRequest::get ( 'post' );
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$post ['id'] = $cid [0];
		
		$isNew = $post ['id'] == 0;
		
		if ($isNew) {
			
			$checkEventDate = "";
			$id_jarticle = JRequest::getVar ( 'id_jarticle' );
			if ($id_jarticle == "" || $id_jarticle == "0") {
				$id_jarticle = JRequest::getVar ( 'id_jarticle' );
			}
			
			$display_name = JRequest::getVar ( 'display_name' );
			
			$id_field_type = JRequest::getVar ( 'id_field_type' );
			$tipoCampo = utility::getField ( 'select options as value from #__djfappend_field_type where id = ' . $id_field_type );
			
			$event_date = JRequest::getVar ( 'event_date' );
			if ($event_date != "") {
				$checkEventDate = 'and event_date = "' . $event_date . '"';
			}
			
			$field_value = JRequest::getVar ( 'field_value' );
			if ($tipoCampo == "file" ) {
				jimport ( 'joomla.filesystem.file' );
				$post = JRequest::get ( 'post' );
				$file = JRequest::getVar ( 'upload', null, 'files', 'array' );
				$file = JFile::makeSafe ( $file );
				$filename = $file ['name'];
				jimport ( 'joomla.user.helper' );
				$urlSelf = 'index.php?option=com_djfappend&controller=field';
				$app = JFactory::getApplication('site');$params = & $app->getParams('com_djfappend');
				$maxsize = $params->get ( 'maxsize' );
				$size = $_FILES ['upload'] ['size'];
				
				$dest = JPATH_ROOT . DS . 'images' . DS . 'stories' . DS . 'com_djfappend' . DS . 'uploads' . DS . $id_jarticle;
				$dest_to_store = 'images' . DS . 'stories' . DS . 'com_djfappend' . DS . 'uploads' . DS . $id_jarticle;
				$dest_thumb = JPATH_ROOT . DS . 'images' . DS . 'stories' . DS . 'com_djfappend' . DS . 'uploads' . DS . $id_jarticle . DS . 'thumb';
				$dest_thumb2 = JPATH_ROOT . DS . 'images' . DS . 'stories' . DS . 'com_djfappend' . DS . 'uploads' . DS . $id_jarticle . DS . 'micro';
				$dest_url_icon = 'images/stories/com_djfappend/uploads/' . $id_jarticle . "/" . $filename;
				$queryIdAlready = '
			select campi.id as value 
			from #__djfappend_field as campi
			where 
			campi.id_jarticle = ' . Jrequest::getVar ( 'id_jarticle' ) . ' and 
			campi.id_field_type = ' . Jrequest::getVar ( 'id_field_type' ) . ' and
			campi.url = "' . $dest_url_icon . '" ';
				
				$idAlready = utility::getField ( $queryIdAlready );
				
				if (! empty ( $idAlready )) {
					$this->setRedirect ( $urlSelf, JText::_ ( 'Nome oggetto esistente' ) );
					return false;
				}
				
				if ($filename != "") {
					$extension = utility::right ( $filename, 3 );
					if (Multimedia::checkExtensions ( $extension ))
						$thumb = "no";
					else
						$thumb = "si";
					
					$i_width = $params->get ( 'i_width' );
					$it_width = $params->get ( 'it_width' );
					$micro_width = $params->get ( 'micro_width' );
					$widthThumbTutte = $it_width . "," . $micro_width;
					
					$msg = utility::saveFile ( $maxsize, $dest_thumb, $dest, $widthThumbTutte, $thumb, array ('rtf','jpg', 'png', 'gif', 'mp3', 'flv', 'mp4', 'doc', 'xls', 'pdf', 'ppt', 'zip', 'pps' ) );
					
					if ($msg == "") {
						$filename = JFile::makeSafe ( $file ['name'] );
						$post ['images'] = $filename;
						$field_value = $dest_url_icon;
					
					} else {
						$app = JFactory::getApplication('site');$params = & $app->getParams('com_djfappend');
						$maxsize = $params->get ( 'image_maxsize_upload' );
						$this->setRedirect ( $urlSelf, JText::_ ( $msg ) );
						return false;
					}
				}
			}
		
			
		if ($tipoCampo == "file_url" ) {
				$post = JRequest::get ( 'post' );
				$filename = $post ['field_value'];
				jimport ( 'joomla.user.helper' );
				$urlSelf = 'index.php?option=com_djfappend&controller=field';
				$app = JFactory::getApplication('site');$params = & $app->getParams('com_djfappend');
				$maxsize = $params->get ( 'maxsize' );
				
				$dest = JPATH_ROOT . DS . 'images' . DS . 'stories' . DS . 'com_djfappend' . DS . 'uploads' . DS . $id_jarticle;
				$dest_to_store = 'images' . DS . 'stories' . DS . 'com_djfappend' . DS . 'uploads' . DS . $id_jarticle;
				$dest_url_icon = 'images/stories/com_djfappend/uploads/' . $id_jarticle . "/" . $filename;
				$queryIdAlready = '
			select campi.id as value 
			from #__djfappend_field as campi
			where 
			campi.id_jarticle = ' . Jrequest::getVar ( 'id_jarticle' ) . ' and 
			campi.id_field_type = ' . Jrequest::getVar ( 'id_field_type' ) . ' and
			campi.url = "' . $dest_url_icon . '" ';
				
				$idAlready = utility::getField ( $queryIdAlready );
				
				if (! empty ( $idAlready )) {
					$this->setRedirect ( $urlSelf, JText::_ ( 'Nome oggetto esistente' ) );
					return false;
				}
				
				if ($filename != "") {
					$extension = utility::right ( $filename, 3 );
					$thumb = "no";
						
					$i_width = $params->get ( 'i_width' );
					$it_width = $params->get ( 'it_width' );
					$micro_width = $params->get ( 'micro_width' );
					$widthThumbTutte = $it_width . "," . $micro_width;
					
					//$msg = utility::saveFile ( $maxsize, $dest_thumb, $dest, $widthThumbTutte, $thumb, array ('rtf','jpg', 'png', 'gif', 'mp3', 'flv', 'mp4', 'doc', 'xls', 'pdf', 'ppt', 'zip', 'pps' ) );
					
					$existence = file_exists($dest_url_icon);
					echo($existence);
					echo($filename);
					//exit();
					if ($existence) {
						//$filename = JFile::makeSafe ( $file ['name'] );
						$post ['images'] = $filename;
						$field_value = $dest_url_icon;
						$size = filesize($dest_url_icon);
				
					
					} else {
						$app = JFactory::getApplication('site');$params = & $app->getParams('com_djfappend');
						$maxsize = $params->get ( 'image_maxsize_upload' );
						$this->setRedirect ( $urlSelf, JText::_ ( $msg ) );
						return false;
					}
				}
			}
			
			
			
			if ($post ['id'] != 0) {
				$queryN = 'select id from #__djfappend_field 
		where id_jarticle = ' . $id_jarticle . '
		and id_field_type = ' . $id_field_type . '
		and lower(field_value) = lower("' . $field_value . '")' . $checkEventDate;
				
				$fieldtype = utility::getArray ( $queryN );
				
				foreach ( $fieldtype as $questoField ) {
					$msg = JText::_ ( 'ALREADY_PRESENT' );
					$this->setRedirect ( 'index.php?option=com_djfappend&controller=field_detail&tmpl=component&task=add&id_jarticle=' . $id_jarticle . '&cid[]=' . $post ['id'], $msg );
					return false;
				}
			}
		}
		$model = $this->getModel ( 'field_detail' );
		$msg = "";
		
		if ($isNew){
			if ($tipoCampo == "file") {
				
				$dest = str_replace ( "\\", "/", $dest . "/" . $filename );
				$dest_to_store = str_replace ( "\\", "/", $dest_to_store . "/" . $filename );
				$post ['filename'] = $filename;
				$post ['filename_sys'] = $dest_to_store;
				$fieldValueFromPost =JRequest::getVar ( 'field_value' ); 
				if (empty ( $fieldValueFromPost ))
					$post ['field_value'] = $display_name;
				$post ['file_type'] = Multimedia::getFileTypeFromPath ( $dest );
				$post ['file_size'] = $size;
				$post ['url'] = $dest_url_icon;
				$post ['display_name'] = $display_name;
			}
			if ($tipoCampo == "file_url") {
				$filename = $post ['field_value'];
				$dest = str_replace ( "\\", "/", $dest . "/" . $filename );
				$dest_to_store = str_replace ( "\\", "/", $dest_to_store . "/" . $filename );
				$post ['filename'] = $filename;
				$post ['filename_sys'] = $dest_to_store;
				$fieldValueFromPost =JRequest::getVar ( 'field_value' ); 
				if (empty ( $fieldValueFromPost ))
					$post ['field_value'] = $display_name;
				$post ['file_type'] = Multimedia::getFileTypeFromPath ( $dest );
				$post ['file_size'] = $size;
				$post ['url'] = $dest_url_icon;
				$post ['display_name'] = $display_name;
			}
		}
		$idultimo = $model->store ( $post );
		
		if (empty ( $display_name ))
			$display_name = $filename;
		
		$model->checkin ();
		
		$Itemid = Jrequest::getVar ( 'Itemid' );
		$id = Jrequest::getVar ( 'id_jarticle' );
		
		echo ('<h1 style="margin-left:20px;">Salvataggio riuscito!</h1>');
		echo "<script language=\"javascript\" type=\"text/javascript\">
            window.parent.document.getElementById('sbox-window').close();
            window.parent.location.reload();
            </script>";
		exit ();
	}
	
	/** 
	 * function remove
	 */
	
	function remove() {
		$post = JRequest::get ( 'post' );
		$model = $this->getModel ( 'field_detail' );
		$msg = "";
		$id = JRequest::getVar ( 'id' );
		$confirm = JRequest::getVar ( 'confirm' );
		$queryreal = "select filename_sys as value from #__djfappend_field where id = " . $id;
		$idArticle = utility::leftTokenize ( utility::getField ( "select id_jarticle as value from #__djfappend_field where id = " . $id ), ":" );
		
		$filename = utility::getField ( "select filename as value from #__djfappend_field where id = " . $id );
		$realPath = utility::getField ( $queryreal );
		$realPath = JPATH_ROOT . DS . $realPath;
		//$confirm="yes";		
		if ($confirm == "yes") {
			if ($model->delete ( $id )) {
				$msg = JText::_ ( 'Field removed' );
				$thumbpath = str_replace ( $filename, "", $realPath ) . "/thumb/" . $filename;
				$thumbpathmicro = str_replace ( $filename, "", $realPath ) . "/thumb/micro/" . $filename;
				$realPath = str_replace ( "\\", "/", $realPath );
				$thumbpath = str_replace ( "\\", "/", $thumbpath );
				$thumbpathmicro = str_replace ( "\\", "/", $thumbpathmicro );
				utility::deleteFileAndDirectory ( $realPath );
				utility::deleteFileAndDirectory ( $thumbpath );
				utility::deleteFileAndDirectory ( $thumbpathmicro );
				$introtext = utility::getField ( 'select introtext as value from #__content where id = ' . $idArticle );
				$queryPerUpdate = 'update #__content set introtext = REPLACE(introtext,"{djfappend}' . $id . '{/djfappend}", "") where id = ' . $idArticle;
				
				utility::executeQuery ( $queryPerUpdate );
				
				$scriptRemove = "delete from #__djfappend_field where id = " . $id;
				utility::executeQuery ( $scriptRemove );
			} else {
				$msg = JText::_ ( 'Error removing Field' );
			}
		}
		$delete_url = "index.php?option=com_djfappend&controller=field_detail&tmpl=component&id=$id&task=remove&confirm=yes";
		$echofori = '<div class="deleteWarning" style="margin-left:20px;">
        <h1>' . JText::_ ( 'WARNING' ) . '</h1>
        <h2 id="warning_msg">' . JText::_ ( 'REALLY_DELETE' ) . '</h2>
        <p>Attenzione proseguendo con questa funzione perderai le modifiche che stavi apportando al testo. Si consiglia di salvare l\'articolo prima di effettuare questa operazione.</p>
        <form action="' . $delete_url . '" name="delete_warning_form" method="post">
        <div align="center">
            <input type="submit" name="submit" value="' . JText::_ ( 'Continua e Cancella' ) . '" />
        </div>
        </form>
        </div>';
		echo ($echofori);
		if ($confirm == "yes")
			echo "<script language=\"javascript\" type=\"text/javascript\">
            window.parent.document.getElementById('sbox-window').close();
            window.parent.location.reload();
            </script>";
	
	}
	
	function publish() {
		
		$post = JRequest::get ( 'post' );
		$model = $this->getModel ( 'field_detail' );
		$msg = "";
		$id = JRequest::getVar ( 'id' );
		$confirm = JRequest::getVar ( 'confirm' );
		if ($confirm == "yes") {
			if ($model->publish ( $id )) {
				$msg = JText::_ ( 'Field published' );
			}
		}
		
		$delete_url = "index.php?option=com_djfappend&controller=field_detail&tmpl=component&id=$id&task=publish&confirm=yes";
		
		$echofori = '<div class="deleteWarning" style="margin-left:20px;">
        <h1>' . JText::_ ( 'WARNING' ) . '</h1>
        <h2 id="warning_msg">' . JText::_ ( 'REALLY_PUBLISH' ) . '</h2>
        <p>' . JText::_ ( 'REALLY_PUBLISH_ADV' ) . '</p>
        
        <form action="' . $delete_url . '" name="delete_warning_form" method="post">
        <div align="center">
            <input type="submit" name="submit" value="' . JText::_ ( 'CONTINUE_AND_PUBLISH' ) . '" />
        </div>
        </form>
        </div>';
		echo ($echofori);
		if ($confirm == "yes")
			echo "<script language=\"javascript\" type=\"text/javascript\">
            window.parent.document.getElementById('sbox-window').close();
            window.parent.location.reload();
            </script>";
	
	}
	function unpublish() {
		$post = JRequest::get ( 'post' );
		//$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		//$post ['id'] = $cid [0];
		

		$model = $this->getModel ( 'field_detail' );
		$msg = "";
		$id = JRequest::getVar ( 'id' );
		$confirm = JRequest::getVar ( 'confirm' );
		if ($confirm == "yes") {
			if ($model->unpublish ( $id )) {
				$msg = JText::_ ( 'Field unpublished' );
			}
		}
		
		$delete_url = "index.php?option=com_djfappend&controller=field_detail&tmpl=component&id=$id&task=unpublish&confirm=yes";
		
		$echofori = '<div class="deleteWarning" style="margin-left:20px;">
        <h1>' . JText::_ ( 'WARNING' ) . '</h1>
        <h2 id="warning_msg">' . JText::_ ( 'REALLY_UNPUBLISH' ) . '</h2>
        <p>Attenzione proseguendo con questa funzione perderai le modifiche che stavi apportando al testo. Si consiglia di salvare l\'articolo prima di effettuare questa operazione.</p>
        <form action="' . $delete_url . '" name="delete_warning_form" method="post">
        <div align="center">
            <input type="submit" name="submit" value="' . JText::_ ( 'Continua e Rimuovi pubblicazione' ) . '" />
        </div>
        </form>
        </div>';
		echo ($echofori);
		//echo($msg);
		if ($confirm == "yes")
			echo "<script language=\"javascript\" type=\"text/javascript\">
            window.parent.document.getElementById('sbox-window').close();
            var parente = window.parent.location.reload();
            </script>";
	
		// exit();
	//   }
	

	}
	
	/** 
	 * function cancel
	 */
	
	function cancel() {
		// Checkin the detail
		$model = $this->getModel ( 'field_detail' );
		$model->checkin ();
		echo "<script language=\"javascript\" type=\"text/javascript\">
            window.parent.document.getElementById('sbox-window').close();
            window.parent.location.reload();
            </script>";
		exit ();
	
		//$this->setRedirect ( 'index.php?option=com_djfappend&controller=field' );
	}

}
