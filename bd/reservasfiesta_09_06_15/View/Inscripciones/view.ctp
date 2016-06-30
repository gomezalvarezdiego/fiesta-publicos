<div class="responsibles view">
<h2><?php echo __('Responsible'); ?></h2>
	<dl>
		<dt><?php echo __('C�dula del Responsible'); ?></dt>
		<dd>
			<?php echo h($responsible['Responsible']['id_responsible']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($responsible['Responsible']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Celular'); ?></dt>
		<dd>
			<?php echo h($responsible['Responsible']['celular']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mail'); ?></dt>
		<dd>
			<?php echo h($responsible['Responsible']['mail']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Educational Institution'); ?></dt>
		<dd>
			<?php echo $this->Html->link($responsible['Institution']['id_institution'], array('controller' => 'institutions', 'action' => 'view', $responsible['Institution']['id_institution'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Responsible'), array('action' => 'edit', $responsible['Responsible']['id_responsible'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Responsible'), array('action' => 'delete', $responsible['Responsible']['id_responsible']), null, __('Are you sure you want to delete # %s?', $responsible['Responsible']['id_responsible'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Responsibles'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Responsible'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Educational Institutions'), array('controller' => 'educational_institutions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Educational Institution'), array('controller' => 'educational_institutions', 'action' => 'add')); ?> </li>
	</ul>
</div>
