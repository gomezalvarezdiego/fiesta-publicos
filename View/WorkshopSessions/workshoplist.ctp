﻿<?php $this->set('title_for_layout' , 'Ver carpas' );?>
<!-- Scripts para el calendario -->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script> 
 
 <!--Fin Scripts para el calendario -->
<div class="WorkshopSession form">
<?php echo $this->Form->create('WorkshopSession'); ?>
	<fieldset>
	<?php if (isset($hourwork)):  ?>
		<span> <?php echo "Los siguientes son las carpas con talleres disponibles (De acuerdo a las características del grupo) en el dia: $datework y en el horario: $hourwork"; ?> </span>
	<?php else: ?>
		<span> <?php echo "Los siguientes son las carpas con talleres disponibles (De acuerdo a las características del grupo) en el dia: $datework";?> </span>
	<?php endif;?>


	
	</br></br></br>
		<legend><?php //echo __('Add Workshop'); ?></legend>
		<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('workshop'); ?></th>
			<th><?php echo $this->Paginator->sort('institución'); ?></th>
			<th class="actions"><?php echo __('Opciones'); ?></th>
		</tr>	
	
		<?php 
		//echo $datework;
		//echo $usuario;
		//print_r($specific_condition);
		//print_r($public_type);
		//print_r($specific_conditionp);
		//print_r($public_typep);
		?>
		
		<?php 
		//print_r($taller);
		//print_r($tallerid);
		//print_r($querya);
		//echo $queryid;

		 foreach ($taller as $taller): ?>		
			<tr>
				<td><?php echo h($taller['name']); ?>&nbsp;</td>

				<td><?php echo h($taller['entity_name']); ?>&nbsp;</td>
				
				<?php if (isset($hourwork)):  ?>
					<td class="actions">
						<?php 
						$horataller=$hourwork;
						$timestamp=strtotime($horataller);
						echo $this->Html->link(__('Incribirse en este horario'), array('controller' => 'workshops','action' => 'workshop_update',$datework,$timestamp,$id_group,$taller['id_workshop'])); ?>			
					</td>
					<td class="actions">
					<?php echo $this->Html->link(__('Ver otros horarios'), array('controller' => 'workshops','action' => 'workshop_inscription',$taller['id_workshop'],$datework,$id_group)); ?>			
					</td>
				<?php else:?>
					<td class="actions">
					<?php echo $this->Html->link(__('Inscribirse'), array('controller' => 'workshops','action' => 'workshop_inscription',$taller['id_workshop'],$datework,$id_group)); ?>			
					</td>
				<?php endif;?>
				
			</tr>
		<?php endforeach;?>	
		
		</table>
			<!-- 
		<tr>
		
			<td><?php //echo h($taller['workshop']['name']); ?>&nbsp;</td>
		</tr>		-->	
		
		
	<!--  <td class="actions"> -->	
			<?php //echo $this->Html->link(__('View'), array('controller' => 'workshops','action' => 'workshop_inscription',$taller['workshop']['id_workshop'],$datework,$institutionidp)); ?>			
		</td>

				
		<?php
		
		//echo $this->Form->input('taller',array ('options' => array ($taller)),array('optgroups'=>array('label'=>'hola')));
		//echo $this->Form->input('taller',array ('options' => array ($tallerid)),array('optgroups'=>array('label'=>'hola')));
		?>
	
				
	
	<?php 
		//echo $this->Form->input('mail');
		?>

	
		<?php 	
		//echo $usuario;
		//print_r($specific_condition);
		//print_r($public_type);
		//print_r($specific_conditionp);
		//print_r($public_typep);
		?>
		
		<?php 
		//print_r($taller);
		//print_r($tallerid);
		//print_r($querya);
		//echo $queryid;
		
		?>
			

	</fieldset>
<?php echo $this->Form->end(); ?>
</div>
 <div class="actions">
	<h3><?php //echo __('Opciones'); ?></h3>
	<ul>		
		<li><?php //echo $this->Html->link(__('Regresar'), array('controller' => 'workshopSessions', 'action' => 'addworkshop')); ?> </li>
		<li><?php //echo $this->Html->link(__('Close Section'), array('controller' => 'users', 'action' => 'logout')); ?> </li>
	</ul>
</div> 
