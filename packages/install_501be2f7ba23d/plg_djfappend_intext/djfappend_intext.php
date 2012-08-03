<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );
$mainframe->registerEvent ( 'onPrepareContent', 'showAppendInText' );

function showAppendInText(&$row, &$params, $page = 0) {
	global $plugin;
	$baseIconPath = "components/com_djfappend/assets/images/icons/";
	$regex = "#{djfappend}(.*?){/djfappend}#s"; // this the MP3 URL is in matches[2], even when autostart is not set
	preg_match_all ( $regex, $row->text, $out, PREG_SET_ORDER );
	for($i = 0; $i < sizeOf ( $out ); $i ++) {
		$idToFind = $out [$i] [1];
		$iconaRender = showMedia ( $idToFind );
		$toReplace = "{djfappend}" . $idToFind . "{/djfappend}";
		$row->text = str_replace ( $toReplace, $iconaRender, $row->text );
	}
	return true;
}

function showMedia($id_field) {
	
	$mainframe =& JFactory::getApplication();
	$thisM = new Multimedia ( $id_field );
	$toReturn = $thisM->showMedia();
	if ($thisM->type!="document") 
		$toReturn = '<div class="' . $thisM->class . '" style="width:' . $thisM->xIframe . 'px;height:' . $thisM->yIframe . 'px;">' . $toReturn . '</div>';
	return $toReturn;
}

?>