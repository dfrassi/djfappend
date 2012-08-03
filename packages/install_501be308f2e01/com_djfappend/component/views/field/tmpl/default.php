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
<?php 


$mainframe =& JFactory::getApplication();
$params = &$mainframe->getParams();
echo('<h1>'.$params->get('page_title').'</h1>');



	$uri =& JFactory::getURI();
	$post = JRequest::get ( 'post' ); 
	if (isset($post['search'])){
		$search=$post['search'];
	}
	$Itemid = JRequest::getVar('Itemid');
	$uristring = "index.php?option=com_djfappend&view=field";
	
	//echo("<h1>$uristring</h1>");



?>

<script language="javascript" type="text/javascript">
function sub(){
	
	var form = document.adminForm;

	form.action="<?php echo $uristring; ?>&search="+document.getElementById('search').value;
	form.action+="&catid="+document.getElementById('catid').value;
	form.action+="&anno="+document.getElementById('anno').value;
	form.action+="&id_field_type="+document.getElementById('id_field_type').value;
	form.submit();
}
</script>	
<form  method="post" name="adminForm" >
<div id="editcell">

<div>
<table>
				<tr>
					<td width="100%">
						<?php echo JText::_( 'Filtro generico' ); ?>:
						<input type="text" name="search" id="search" value="<?php echo $this->search;?>" class="text_area"  onchange="sub();" />
						<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
						<button onclick="document.getElementById('search').value='';this.form.submit();" onchange="sub();"><?php echo JText::_( 'Reset' ); ?></button>
					</td>
					
					<?php 
						
						$tipo_show = $params->get('tipo_show');
						$cate_show = $params->get('cate_show');
						$year_show = $params->get('year_show');
						$label_tipologia = $params->get('label_tipologia');
						$label_anno = $params->get('label_anno');
						$label_valore = $params->get('label_valore');
						$label_titolo = $params->get('label_titolo');
						
						if ($tipo_show=='')
							$tipo_show=JRequest::getVar('tipo_show');

						if ($cate_show=='')
							$cate_show=JRequest::getVar('cate_show');	
						
						//echo("<h1>asdfasd".$tipo_show."</h1>");				
						if ($tipo_show=='si'){
						?>					
							<td nowrap="nowrap">					
								Tipologia: <?php echo $this->lists['tipi']; ?>
							</td>							
					<?php } ?>
				
					
				</tr>
				
				<tr>
				
				<?php if ($cate_show=='si'){?> 							
							<td nowrap="nowrap">					
								Categoria: <?php echo $this->lists['categorie']; ?>
							</td>							
				<?php } ?>
				<?php if ($year_show=='si'){?> 							
							<td nowrap="nowrap">					
								Anno: <?php echo $this->lists['anni']; ?>
							</td>							
				<?php } ?>	
					
				</tr>
				
			</table>
			

</div>
	<table class="adminlist" >
	<thead>
		<tr >
		<?php

			
		
		
		if ($label_tipologia!=''){ ?>
		
			<th width="1%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', $label_tipologia, 'ft.name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
			
			<?php  }  if ($label_anno!=''){ ?>
			<th width="1%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', $label_anno, 'h.event_date', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th><?php  }  if ($label_valore!=''){ ?>
				<th width="1%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', $label_valore, 'h.field_value', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>	<?php  }  if ($label_titolo!=''){ ?>	
			<th width="70%" nowrap="nowrap" class="title"  style="text-align:left;">
				<?php echo JHTML::_('grid.sort', $label_titolo, 'a.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				
			</th>
			
				<?php  }  ?>
			
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
		//$checked 	= JHTML::_('grid.checkedout',$row, $i );
		?>
		
		<?php
				
					$script_ceccato="";
					$ceccato = false;
				
		?>
		
		<tr class="<?php echo "row$k"; ?>">
			
			<?php if ($label_tipologia!=''){ ?>
		
			
			<td>
				<?php
				if (  $ceccato ) {
					echo $row->field_type;
				} else {
				?>
					<a <?php echo $script_ceccato; ?> href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit field' ); ?>">
						<?php echo $row->field_type; ?></a>
				<?php
				}
				?>
			</td>
			
				
				<?php  }  if ($label_anno!=''){ ?>
				<td>
				<?php
				if (  $ceccato ) {
					echo $row->event_date;
				} else {
				?>
					<a <?php echo $script_ceccato; ?> href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit field' ); ?>">
						<?php echo $row->event_date; ?></a>
				<?php
				}
				?>
			</td>
			<?php  }  if ($label_valore!=''){ ?><td>
				<?php
				if (  $ceccato ) {
					echo $row->field_value;
				} else {
				?>
					<a <?php echo $script_ceccato; ?> href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit field' ); ?>">
						<?php 
						
						
						$valoretoShow = $row->valore1; 
						echo ($valoretoShow); 
						
						
						 
						
						
						?></a>
				<?php
				}
				?>
			</td>
			<?php  }  if ($label_titolo!=''){ ?><td>
				<?php
				if (  $ceccato ) {
					echo $row->title;
				} else {
				?>
					<a <?php echo $script_ceccato; ?> href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit field' ); ?>">
						<?php echo $row->title; ?></a>
				<?php
				}
				?>
			</td>
		<?php  }  ?>
			
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
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />

<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

<?php include JPATH_COMPONENT_ADMINISTRATOR.DS."version.php"; ?>
