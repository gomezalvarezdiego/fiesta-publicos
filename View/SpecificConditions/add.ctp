<div class="specificConditions form mde-form">
<?php $this->set('title_for_layout' , 'Agregar condici�n espec�fica' );?>
<?php echo $this->Form->create('SpecificCondition'); ?>
	<fieldset>
		<legend><?php echo __('Add Specific Condition'); ?></legend>
	<?php
		echo $this->Form->input('name',array('maxLength'=>'256'));
		echo $this->Form->input('Workshop_id',array('required' => 'required'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Opciones'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Main Menu'), array('controller' => 'institutions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('List Specific Conditions'), array('action' => 'index')); ?></li>
		<li><?php //echo $this->Html->link(__('List Workshops'), array('controller' => 'workshops', 'action' => 'index')); ?> </li>
		<li><?php //echo $this->Html->link(__('New Workshop'), array('controller' => 'workshops', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Close Section'), array('controller' => 'users', 'action' => 'logout')); ?> </li>
	</ul>
</div>
