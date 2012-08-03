<?php
/**
 * @version $Id: component.php 5173 2006-09-25 18:12:39Z Jinx $
 * @package Joomla
 * @subpackage Config
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 * 
 * php echo $lang->getName();  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

//DEVNOTE: import html tooltips
JHTML::_ ( 'behavior.tooltip' );
JHTML::_ ( 'behavior.modal' );
?>

<script type="text/javascript">




function reloadValues(){
	var valuevero = "";
	if (document.adminForm.field_value_vero != null){
		
		valuevero = document.adminForm.field_value_vero.value;
	}
	avvia_ajax(document.adminForm.id_field_type.value, valuevero);
}


function avvia_ajax(testo, valore){
	
	var ajax = new sack();
	ajax.requestFile = "index.php?option=com_djfappend&controller=field_detail&task=rebuildselect&format=raw&id_field_type="+testo+"&valore="+valore;

	//alert (ajax.requestFile);
	ajax.method = "POST";
	ajax.element = "lista_valori";
	ajax.onLoaded = showContent;
	ajax.onLoading = showWaitMessage;
	ajax.runAJAX();
	}
	
	
function showWaitMessage(){
	//document.getElementById.("loading_mio").style.visibility="visible";
	document.getElementById("my-pic").src = "components/com_djfappend/assets/script/loader.gif";
	document.getElementById("my-pic").style.visibility="visible";
	document.getElementById("my-pic").style.width="20px";
				
}

function showContent(){
	
	//document.getElementById("loading_mio").style.visibility="hidden";
	document.getElementById("my-pic").style.visibility="hidden";
}

function jSelectArticle(id, title) {
	
                  document.getElementById('id_jarticle').value = id;
                  document.getElementById('article_title').value = title;
                  document.getElementById('sbox-window').close();
                  
}

		window.addEvent('domready', function() {

			SqueezeBox.initialize({});

			$$('a.modal-button').each(function(el) {
				el.addEvent('click', function(e) {
					new Event(e).stop();
					SqueezeBox.fromElement(el);
				});
			});
		});


		function submitbutton(pressbutton) {
			
			var form = document.adminForm;
			
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}

			if (pressbutton == 'save') {			
				if (form.id_jarticle.value == "0"){
					alert( "<?php
					echo JText::_ ( 'Collegare almeno un articolo', true );
					?>" );		 
				} else if (form.id_field_type.value == "0"){
					alert( "<?php
					echo JText::_ ( 'Collegare almeno una tipologia', true );
					?>" );		 
				} else if (form.field_value.value == ""){
						alert( "<?php
						echo JText::_ ( 'Inserire almeno un valore', true );
						?>" );		 
				} else {
					submitform( pressbutton );
				}
			}
		}
		
  </script>


<style type="text/css">
table.paramlist td.paramlist_key {
	width: 92px;
	text-align: left;
	height: 30px;
}
</style>



<form action="<?php
echo JRoute::_ ( $this->request_url )?>"
	method="post" name="adminForm" id="adminForm">
<div class="col50">
<fieldset class="adminform"><legend><?php
echo JText::_ ( 'Dettaglio utente djfappend' );
?></legend>
<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><label for="title">
					<?php
					echo JText::_ ( 'Id' );
					?>:
				</label></td>
		<td><input class="text_area" disabled="disabled" type="text" name="id"
			id="id" size="32" maxlength="250"
			value="<?php
			echo $this->detail->id;
			?>" />
		<div
			style="position: absolute; margin-left: 160px; margin-top: -20px;"><img
			id="my-pic" style="visibility: hidden;" /></div>

		</td>
	</tr>


	<tr>
		<td valign="top" align="right" class="key"><label for="id_jarticle"><?php
		echo JText::_ ( 'id_jarticle' );
		?>: </label></td>
		<td>
		<p><input id="article_title" disabled="disabled" type="text" size="60"
			value="<?php
			if (!empty($this->detail->title))
			echo $this->detail->title;
			?>" />&nbsp; <a class="modal-button" type="button"
			href="index.php?option=com_content&amp;task=element&amp;tmpl=component"
			rel="{handler: 'iframe', size: {x: 650, y: 375}}"><?php
			echo JText::_ ( 'SELECT ARTICLE' )?></a> <input id="id_jarticle"
			name="id_jarticle" type="hidden"
			value="<?php
			echo $this->detail->id_jarticle;
			?>" /></p>
		</td>
	</tr>
	<?php
	//$esito = utility::check_if_table_exists ( '#__djfappend_field' );
		$esito = true;
	if ($esito) {
		?>
	<tr>
		<td valign="top" align="right" class="key"><label for="id_field_type"><?php
		echo JText::_ ( 'id_field_type' );
		?>: </label></td>
		<td>	<?php
		echo $this->lists ['field_type_associati'];
		?></td>
	</tr>
	<tr>
		<td valign="top" align="right" class="key"><label for="field_value"><?php
		echo JText::_ ( 'field_value' );
		?>: </label>
		</td>
		<td>
		<div id="lista_valori"><?php
		
		$staquery = "select id,valore from #__djfappend_field_value where id=" . $this->detail->field_value;
		if (!empty($this->detail->id_field_type))
		$tipo = $this->detail->id_field_type;
		else $tipo="";
		$valore_field = utility::getArray ( $staquery );
		
		if (sizeof ( $valore_field ) > 0) {
			foreach ( $valore_field as $questo_valore ) {
				
				$valore_field_vero = $questo_valore->valore;
				
				$lists = utility::getSelectExt ( "
					SELECT id AS value, valore as text 
					FROM #__djfappend_field_value where id_field_type = $tipo 
					ORDER BY trim(valore)", 'field_value', 'field_value', $questo_valore->id, null, false );
			}
			echo ($lists . "<input type='hidden' id='field_value_vero' name='field_value_vero' value='" . $valore_field_vero . "'/>");
		} else {
			$valore_field_vero = $this->detail->field_value;
			echo ("
				<input type='hidden' id='field_value_vero' name='field_value_vero' value='" . $valore_field_vero . "'/>
			    <input type='text' id='field_value' name='field_value' value='$valore_field_vero'/>");
		}
		
		?></div>
		</td>
	</tr>

	<tr>
		<td valign="top" align="right" class="key"><label for="event_date"><?php
		echo JText::_ ( 'event_date' );
		?>: </label></td>
		<td>	
		
		
			<?php
		$data_odierna = gmdate ( 'Y-m-d H:i:s' );
		if (!empty($this->detail->event_date)){
		if ($this->detail->event_date == "0000-00-00 00:00:00" || $this->detail->event_date == "") {
			$this->detail->event_date = $data_odierna;
		}}
		else $this->detail->event_date = $data_odierna;
	}
	
	?>
<?php

	echo JHTML::_ ( 'calendar', $this->detail->event_date, 'event_date', 'event_date', '%Y-%m-%d %H:%M:%S', array ('class' => 'inputbox', 'size' => '25', 'maxlength' => '19' ) );
	?>	
</td>

	</tr>


</table>
</fieldset>


</div>
<div class="col50"></div>
<div class="clr"></div>

<input type="hidden" name="cid[]"
	value="<?php
	echo $this->detail->id;
	?>" /> <input type="hidden"
	name="task" value="" /> <input type="hidden" name="controller"
	value="field_detail" /></form>


<?php
include JPATH_COMPONENT_ADMINISTRATOR . DS . "version.php";
?>