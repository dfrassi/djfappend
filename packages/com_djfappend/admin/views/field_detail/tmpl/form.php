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
JHTML::_ ( 'behavior.modal');
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

	ajax.method = "POST";
	ajax.element = "lista_valori";
	ajax.onLoaded = showContent;
	ajax.onLoading = showWaitMessage;
	ajax.runAJAX();
	}
	
	
function showWaitMessage(){
	document.getElementById("my-pic2").style.display="block";
	document.getElementById("my-pic").src = "components/com_djfappend/assets/script/loader.gif";
	document.getElementById("my-pic").style.visibility="visible";
	document.getElementById("my-pic").style.width="18px";
				
}

function showContent(){
	
	document.getElementById("my-pic").style.visibility="hidden";
	document.getElementById("my-pic2").style.display="none";
}



		function submitbutton(pressbutton) {
			
			var form = document.adminForm;
			
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}

			if (pressbutton == 'save') {	
					
			 if (form.id_field_type.value == "0"){
					alert( "<?php echo JText::_ ( 'INSERIRE_ALMENO_UNO', true ); ?>" );		 
				} else if (form.field_value.value == "0"){
						alert( "<?php echo JText::_ ( 'INSERIRE_ALMENO_UNO', true ); ?>" );		 
				} else {
					submitform( 'save_detail' );
				}
			}
		}
		
  </script>





<?php 

$isNew	= ($this->detail->id < 1);
?>
<form action="<?php echo JRoute::_( $this->request_url )?>" method="post" name="adminForm" id="adminForm"  enctype="multipart/form-data">
<div style="width:100%;">
<fieldset class="adminform"><legend><?php echo JText::_ ( 'Djf Append' ); ?></legend>

<table class="admintable" style="width:100%;">
<tr>
<td align="right" COLSPAN="1" class="key">
<?php 
					utility::getJoomlaButton('components/com_djfacl/assets/images/save.png','save');
					
					?></td>
					
			<td>
			<?php utility::getJoomlaButton('components/com_djfacl/assets/images/cancel.png','cancel'); ?>
			</td>
	</tr>
	<tr>
		<td width="10" align="right" class="key"><label for="title">
					<?php
					echo JText::_ ( 'ID' );
					?>:
				</label></td>
		<td><input class="text_area" disabled="disabled" type="text" name="id"
			id="id" size="10" maxlength="250"
			value="<?php
			echo $this->detail->id;
			?>" />
			
			
			</td>
	</tr>
	

	<?php if ($isNew){?>
	<tr>
		<td valign="top" align="right" class="key"><label for="id_field_type">
			<?php echo JText::_ ( 'FIELD_TYPE' ); ?>: </label></td>
		<td>	<div style="float:left;"><?php echo $this->lists ['field_type_associati']; ?></div>
		<div id="my-pic2" name="my_pic2" style="display:none;">&nbsp;&nbsp;<img id="my-pic" name="my-pic" style="visibility:hidden;"/></div>
		</td>
	</tr>

	<tr>
		<td valign="top" align="right" class="key">
			<label for="field_value"><?php echo JText::_ ( 'FIELD_VALUE' ); ?>: </label>
		</td>
		<td><div id="lista_valori" name="lista_valori"><?php 
				
		$staquery="select id,valore from #__djfappend_field_value where id=" . $this->detail->field_value ;	
		if (!empty($this->detail->id_field_type))	
		$tipo = $this->detail->id_field_type;
		else $tipo="";
		$valore_field = utility::getArray ( $staquery);

		if (!empty($detail->id_field_type)) $idfieldtype= $detail->id_field_type;
		else $idfieldtype="";
		$lists ['field_type_associati'] = utility::getSelectExt ( "SELECT id AS value, name as text FROM #__djfappend_field_type
		ORDER BY trim(name)", 'id_field_type', 'id_field_type', $idfieldtype, 'onChange="reloadValues();"', false );
		
		
		
			if (sizeof ( $valore_field ) > 0) {
				foreach ( $valore_field as $questo_valore ) {
					
					$valore_field_vero = $questo_valore->valore;
					
					$lists = utility::getSelectExt ( "
					SELECT id AS value, valore as text 
					FROM #__djfappend_field_value where id_field_type = $tipo 
					ORDER BY trim(valore)", 
					'field_value', 
					'field_value', 
					$questo_valore->id, 
					'onChange="reloadValues();"', false );
				}
				echo($lists."<input type='hidden' id='field_value_vero' name='field_value_vero' value='".$valore_field_vero."'/>");
			} 
			else {
				$valore_field_vero = $this->detail->field_value;
				echo("
				<input type='hidden' id='field_value_vero' name='field_value_vero' value='".$valore_field_vero."'/>
			    <input type='text' id='field_value' name='field_value' value='$valore_field_vero'/>");
			}
						
			
			
		?>
		
		</div>
	</tr>
	<?php } else {
	
		echo('<input type="hidden" id="id_field_type" name="id_field_type" value="'.$this->detail->id_field_type.'"/>');
		echo('<input type="hidden" id="field_value" name="field_value" value="'.$this->detail->field_value.'"/>');
		echo('<input type="hidden" id="id_jarticle" name="id_jarticle" value="'.$this->detail->id_jarticle.'"/>');
		
		}?>
	<tr>
		<td width="10" align="right" class="key"><label for="title">
					<?php
					echo JText::_ ( 'DISPLAY_NAME' );
					?>:
				</label></td>
		<td><input class="text_area" type="text" name="display_name"
			id="display_name" size="20" maxlength="250"
			value="<?php
			echo $this->detail->display_name;
			?>" />
			
			
			</td>
	</tr>
	<tr>
		<td valign="top" align="right" class="key"><label for="event_date">
		<?php echo JText::_ ( 'EVENT_DATE' ); ?>: </label></td>
		<?php 
		$data_odierna = gmdate ( 'Y-m-d H:i:s' );
		if (!empty($this->detail->event_date)){
		if ($this->detail->event_date == "0000-00-00 00:00:00" || $this->detail->event_date == ""){
			$this->detail->event_date = $data_odierna;
		}}
		else $this->detail->event_date=$data_odierna;
		
		
		?>
		<td><?php echo JHTML::_ ( 'calendar', $this->detail->event_date, 'event_date', 'event_date', '%Y-%m-%d %H:%M:%S', array ('class' => 'inputbox', 'size' => '25', 'maxlength' => '19' ) );
		?>	</td>
	</tr>

	
</table>
</fieldset>


</div>
<div class="col50"></div>
<div class="clr"></div>

<input type="hidden" name="cid[]" value="<?php echo $this->detail->id; ?>" />
<input type="hidden" name="task" value="" />

<?php 
$id_jarticle="";
if (JRequest::getVar('task') == 'add'){
	$id_jarticle = JRequest::getVar('id_jarticle');
}
else {
	$id_jarticle = $this->detail->id_jarticle;
}
?>

<input type="hidden" name="id_jarticle" value="<?php echo $id_jarticle; ?>" />
<input type="hidden" name="controller" value="field_detail" />
	
</form>


