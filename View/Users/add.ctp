<div class="users form mde-form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Add User'); ?></legend>
	<?php
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $this->Form->input('permission_level');
		echo $this->Form->input('name');
		echo $this->Form->input('identity');
		echo $this->Form->input('celular');
		echo $this->Form->input('mail');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('action' => 'index')); ?></li>
	</ul>
</div>
