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

<div>
<table>
				<tr>
					<td width="100%">
						<?php echo JText::_( 'Filtro generico' ); ?>:
						<input type="text" name="search" id="search" value="<?php echo $this->search;?>" class="text_area" onchange="document.adminForm.submit();" />
						<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
						<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
					</td>
					
					<td nowrap="nowrap">
					
						Tipologia: <?php echo $this->lists['tipi']; ?>
					</td>
					<td nowrap="nowrap">
					
						Categoria: 
						<?php 
						$catid = JRequest::getVar("catid");
						$querypercategoria = "select title from #__categories where id = $catid";
						//echo($querypercategoria);
						$categoria = utility::getArray($querypercategoria);
						foreach ($categoria as $questaCategoria){
						$categorianome = $questaCategoria->title;
						}
						
						echo ("<b>$categorianome</b>"); 
						
						?>
					</td>
				</tr>
			</table>
			

</div>
	<table class="adminlist" >
	<thead>

		<tr >
			<th width="10%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', 'Title', 'title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
						
						<?php for ($ii=0, $nn=count( $this->fields ); $ii < $nn; $ii++)
			{ ?>
			<th width="10%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', $this->fields[$ii], $this->fields[$ii], $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
			<?php
				}
				?>
			
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
		$link 	= JRoute::_( 'index.php?option=com_content&view=article&Itemid='.$this->itemid.'&id='. $row->id_jarticle );
		$checked 	= JHTML::_('grid.checkedout',$row, $i );
		?>
		
		<?php
				
					$script_ceccato="";
					$ceccato = false;
				
		?>
		
		<tr class="<?php echo "row$k"; ?>">
		<td style="width:80px;">
				
					<a href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit field' ); ?>">
						<?php echo $row->title; ?></a>
				
			</td>
		<?php for ($ii=0, $nn=count( $this->fields ); $ii < $nn; $ii++)
			{ ?>
			<td>
				<?php
				if (  $ceccato ) {
					echo $row->$ii;
				} else {
					$nome = $this->fields[$ii];
					//echo($nome);
					

					
					$queryquesto = "select distinct
						df.id,
						c.title,
						df.field_value as valore1,
						ft.name,
						(select valore from #__djfappend_field_value fv where df.id_field_type = fv.id_field_type and df.field_value = fv.id) as valore2 
						from #__djfappend_field df, #__content c,
						#__djfappend_field_type ft
						where c.id = df.id_jarticle
						and ft.name = '$nome'
						and c.id = $row->id_jarticle
						and ft.id = df.id_field_type";
					
					$valoreQuesto = utility::getArray($queryquesto);
					
						//echo($queryquesto);
						foreach ($valoreQuesto as $valquesto){
							if ($valquesto->valore2 == null){
								$valore = $valquesto->valore1;
							}else{
								$valore= $valquesto->valore2;
							}
					
						}
					
				?>
					<a <?php echo $script_ceccato; ?> href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit field' ); ?>">
						<?php echo $valore ?></a>
				<?php
				}
				?>
			</td>
			<?php } ?>
					</tr>
		<?php
		$k = 1 - $k;
	}
	?>
<tfoot>
		<td colspan="8">
			<?php 
			
				
		
				//$session =& JFactory::getSession('search');
				//$session->set('search',0,$this->id_jarticle);
		$post = JRequest::get ( 'post' );
		$post['search']=$this->search;
			
			echo $this->pagination->getListFooter(); ?>
			
		</td>
	</tfoot>
	</table>
</div>
<div>
<p style="text-align:center;"><b>djfappend:</b> Componente per l'associazione libera di campi addizionali agli articoli.</p>
</div>


<input type="hidden" name="controller" value="field" />
<input type="hidden" name="id_jarticle" value="<?php echo $this->id_jarticle; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />

<input type="hidden" name="checkedout" value="<?php echo $this->checkedout; ?>" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

<?php include JPATH_COMPONENT_ADMINISTRATOR.DS."version.php"; ?>
