<?php defined('_JEXEC') or die('Restricted access'); 

//Ordering allowed ?
$ordering = ($this->lists['order'] == 'ordering');

//onsubmit="return submitform();"

//DEVNOTE: import html tooltips
JHTML::_('behavior.tooltip');

?>

<script language="javascript" type="text/javascript">
/**
* Submit the admin form
* 
* small hack: let task desides where it comes
*/
function submitform(pressbutton){
var form = document.adminForm;
   if (pressbutton)
    {form.task.value=pressbutton;}
     
	 if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')||(pressbutton=='approve')||(pressbutton=='unapprove')
	 ||(pressbutton=='orderdown')||(pressbutton=='orderup')||(pressbutton=='saveorder')||(pressbutton=='remove') )
	 {
	  form.controller.value="field_detail";
	 }
	try {
		form.onsubmit();
		}
	catch(e){}
	
	form.submit();
}


</script>

<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm" >
<div id="editcell">




	<table class="adminlist" >
	<thead>
		<tr >
			<th width="1%" style="text-align:left;">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="1%"  style="text-align:left;">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>
			<th width="1%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', 'id', 'h.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
			<th width="10%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', 'title', 'a.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
			<th width="10%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', 'field_type', 'ft.name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
				<th width="30%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', 'field_value', 'h.field_value', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>		
				<th width="30%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', 'event_date', 'h.event_date', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
		</tr>
	</thead>	
	
	
	<?php 
	//echo $this->pulsanti; 
	$iconUnPublish = " <img border=\"0\" src=\"images/publish_x.png\" alt=\"add new hello world link\" />";
	$iconPublish = " <img border=\"0\" src=\"images/tick.png\" alt=\"add new hello world link\" />";		
	$user =& JFactory::getUser();
	$k = 0;
	
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$link 	= JRoute::_( 'index.php?option=com_djfappend&controller=field_detail&task=edit&cid[]='. $row->id );
		$checked 	= JHTML::_('grid.checkedout',$row, $i );
		//$published 	= JHTML::_('grid.published', $row, $i );		
		//$approved 	= JHTML::_('grid.approved', $row, $i );

		?>
		
		<?php
				
					$script_ceccato="";
					$ceccato = false;
				
		?>
		
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
					<?php
					if (JTable::isCheckedOut($user->get ('id'), $row->checked_out )) :
						echo $row->id;
					else :
						?>
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit field' );?>::<?php echo $row->id; ?>">
						
							<?php echo $row->id; ?> </span>
						<?php
					endif;
					?>
					</td>
			
			<td>
				<?php
				if (  $ceccato ) {
					echo $row->title;
				} else {
				?>
					
						<?php 
						
						$link = "index.php?option=com_content&sectionid=-1&task=edit&cid[]=".$row->id_jarticle;
						
						echo ("<a href='".$link."' title='Edit Article'>".$row->title."</a>"); 
						
						?>
				<?php
				}
				?>
			</td>
			
			<td>
				<?php
				if (  $ceccato ) {
					echo $row->field_type;
				} else {
				?>
					
						<?php echo $row->field_type; ?>
				<?php
				}
				?>
			</td>
			<td>
				<?php
				if (  $ceccato ) {
					
					if (!empty($row->field_value))
						echo $row->field_value;
						else echo($row->filename);
						
				} else {
				?>
					
						<?php 
						

						//echo $row->field_value; 
						
						
						$staquery="select valore from #__djfappend_field_value where id=" . $row->field_value ;
						//echo($staquery);
		
						$valore_field = utility::getArray ( $staquery);
						if (sizeof ( $valore_field ) > 0) {
							foreach ( $valore_field as $questo_valore ) {
								$valore_field_vero = $questo_valore->valore;
							}
						} 
							else $valore_field_vero = $row->field_value;
						
						
							if (!empty($valore_field_vero)){
								echo($valore_field_vero);
							}
						
							
							if (!empty($row->filename)){
								$thisM = new Multimedia($row->id);
								$icona = $thisM->getIconPerPopup();
								echo($icona." ".$row->filename);
							}
							
	
						?>
				<?php
				}
				?>
			</td>
			<td>
				<?php
				if (  $ceccato ) {
					echo $row->event_date;
				} else {
				?>
					
						<?php echo $row->event_date; ?>
				<?php
				}
				?>
			</td>
					</tr>
		<?php
		$k = 1 - $k;
	}
	?>
<tfoot>
		<td colspan="7">
			<?php echo $this->pagination->getListFooter(); ?>
				
		</td>
	</tfoot>
	</table>
</div>



<input type="hidden" name="controller" value="field" />

<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />


<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

<?php include JPATH_COMPONENT_ADMINISTRATOR.DS."version.php"; ?>
