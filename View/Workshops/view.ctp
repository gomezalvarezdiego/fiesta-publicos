<div class="workshops view">
<?php $this->set('title_for_layout' , 'Ver carpa' );?>
<h2><?php echo __('Workshop'); ?></h2>
	<dl>
		<dt><?php echo __('Nombre:'); ?></dt>
		<dd>
			<?php echo h($workshop['Workshop']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripción:'); ?></dt>
		<dd>
			<?php echo h($workshop['Workshop']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Entidad:'); ?></dt>
		<dd>
			<?php echo $this->Html->link($workshop['Entity']['name'], array('controller' => 'entities', 'action' => 'view', $workshop['Entity']['id_entity'])); ?>
			&nbsp;
		
		</dd>
		</dl>	
</div>
<div class="actions">
	<h3><?php echo __('Opciones'); ?></h3>
	<ul>
		<?php $usuario_level= $this->Session->read('Auth.User.permission_level');?>
	<?php if ($usuario_level == '2'){?>
	<li><?php echo $this->Html->link(__('Regresar'), array('controller' => 'workshops', 'action' => 'index_inscription')); ?> </li>
	<?php }?>
	<?php  $usuario_level= $this->Session->read('Auth.User.permission_level');
		if ($usuario_level == '1'){?>
		<li><?php echo $this->Html->link(__('Main Menu'), array('controller' => 'institutions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('List Workshops'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Workshop'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Edit Workshop'), array('action' => 'edit', $workshop['Workshop']['id_workshop'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Workshop'), array('action' => 'delete', $workshop['Workshop']['id_workshop']), null, __('Está seguro que desea Eliminar # %s?', $workshop['Workshop']['id_workshop'])); ?> </li>
		<li><?php //echo $this->Html->link(__('List Entities'), array('controller' => 'entities', 'action' => 'index')); ?> </li>
		<li><?php //echo $this->Html->link(__('New Entity'), array('controller' => 'entities', 'action' => 'add')); ?> </li>
		<?php }?>
		<li><?php echo $this->Html->link(__('Close Section'), array('controller' => 'users', 'action' => 'logout')); ?> </li>
	
	</ul>
</div>