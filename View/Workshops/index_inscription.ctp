﻿<div class="groups index">
	<h2><?php echo __('Groups'); ?></h2>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo __('Nombre grupo'); ?></th>
			<th><?php echo __('Número de integrantes'); ?></th>
			<th><?php echo __('Rango de edad');?></th>
			<th><?php echo __('Condición específica'); ?></th>
			<th><?php echo __('Responsable'); ?></th>
			<th><?php echo __('Nombre taller'); ?></th>
			<th><?php echo __('Día taller'); ?></th>
			<th><?php echo __('Hora taller'); ?></th>
			<th><?php echo __('Recorrido taller'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($groups as $group): ?>
	<tr>
		<td><?php echo h($group['gs']['name']); ?>&nbsp;</td>
		<td><?php echo h($group['gs']['members_number']); ?>&nbsp;</td>
		<td><?php echo h($group['pt']['name']); ?>&nbsp;</td>
		<td><?php echo h($group['0']['specific_conditions']); ?>&nbsp;</td>
		<td><?php echo h($group['us']['name']); ?>&nbsp;</td>
		<td><?php echo h($group['wp']['name']); ?>&nbsp;</td>
		<td><?php echo h($group['ws']['workshop_day']); ?>&nbsp;</td>
		<td><?php echo h($group['ws']['workshop_time']); ?>&nbsp;</td>
		<td><?php echo h($group['ws']['travel_time']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view_inscript', $group['gs']['id_group'])); ?>
			<?php //echo $this->Html->link(__('Edit'), array('action' => 'edit', $group['gs']['id_group'])); ?>
			<?php //echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $group['gs']['id_group']), null, __('Are you sure you want to delete # %s?', $group['gs']['id_group'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>

	
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Group'), array('controller' => 'groups', 'action' => 'addresp')); ?></li>
		<li><?php echo $this->Html->link(__('Close Section'), array('controller' => 'users', 'action' => 'logout')); ?> </li>
	</ul>
</div>
