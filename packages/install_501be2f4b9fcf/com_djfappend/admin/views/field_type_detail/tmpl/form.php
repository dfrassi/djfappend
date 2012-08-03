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
JHTML::_ ('behavior.modal');
?>

<script type="text/javascript">

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
			 if (form.name.value == ""){
					alert( "<?php echo JText::_ ( 'INSERIRE_ALMENO_UN_NOME', true ); ?>" );		 
				}  else {
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

<form action="<?php echo JRoute::_ ( $this->request_url )?>" method="post" name="adminForm" id="adminForm">
<div class="col50">
<fieldset class="adminform"><legend><?php
echo JText::_ ( 'FIELD_TYPOLOGY_DETAIL' );
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
			?>" /></td>
	</tr>
	

	<tr>
		<td valign="top" align="right" class="key"><label for="name"><?php
		echo JText::_ ( 'FIELD_NAME' );
		?>: </label></td>
		<td><input class="text_area" type="text" name="name" id="name"
			size="32" maxlength="250"
			value="<?php
			if (!empty($this->detail->name))
			echo $this->detail->name;	
			?>" /></td>
	</tr>
	<tr>
		<td valign="top" align="right" class="key"><label for="description"><?php
		echo JText::_ ( 'description' );
		?>: </label></td>
		<td><input class="text_area" type="text" name="description" id="description"
			size="32" maxlength="250"
			value="<?php
			if (!empty($this->detail->description))
				echo $this->detail->description;
			?>" /></td>
	</tr>
<?php 

$select_custom_options = utility::addArrayItemToSelect(array (
	JText::_("file") => "file", 
	JText::_("file_url") => "file_url",
	JText::_("youtube") => "youtube",
	JText::_("youtubeplaylist") => "youtubeplaylist",
	JText::_("vimeo") => "vimeo",
	JText::_("map") =>"map", 
	JText::_("text") =>"text", 
	JText::_("list") =>"list"));
if (empty($this->detail->options)) $this->detail->options="";
utility::getFormSelectRow (
	$paramName = 'options',
	$paramValue = $this->detail->options,
	$select_custom = $select_custom_options,
	$query_select = '',
	$inputTags = '');

?>

	
</table>
</fieldset>


</div>
<div class="col50"></div>
<div class="clr"></div>

<input type="hidden" name="cid[]" value="<?php echo $this->detail->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="field_type_detail" />
	
	</form>


<?php include JPATH_COMPONENT_ADMINISTRATOR.DS."version.php"; ?>