﻿<div class="specificConditions index">
<?php $this->set('title_for_layout' , 'Lista condición específica' );?>
	<h2><?php echo __('Specific Conditions'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th class="actions"><?php echo __('Opciones'); ?></th>
	</tr>
	<?php foreach ($specificConditions as $specificCondition): ?>
	<tr>
		<td><?php echo h($specificCondition['SpecificCondition']['name']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $specificCondition['SpecificCondition']['id_specific_condition'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $specificCondition['SpecificCondition']['id_specific_condition'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $specificCondition['SpecificCondition']['id_specific_condition']), null, __('Está seguro que  desea Eliminar # %s?', $specificCondition['SpecificCondition']['id_specific_condition'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	/*
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));*/
	echo $this->Paginator->counter(array(
			'format' => __('Pagina {:page} of {:pages}, resultado {:current} registros de {:count} en total, a partir del registro {:start}, que concluye en el {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Opciones'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Main Menu'), array('controller' => 'institutions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Specific Condition'), array('action' => 'add')); ?></li>
		<li><?php //echo $this->Html->link(__('List Workshops'), array('controller' => 'workshops', 'action' => 'index')); ?> </li>
		<li><?php //echo $this->Html->link(__('New Workshop'), array('controller' => 'workshops', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Close Section'), array('controller' => 'users', 'action' => 'logout')); ?> </li>
	</ul>
</div>
