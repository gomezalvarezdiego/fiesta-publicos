<script type="text/javascript">

      function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
 
         return true;
      }
</script>

<div class="descripcion-formularios">
Creando esta cuenta tendrás acceso para administrar las inscripciones que realices de uno o varios grupos. Selecciona a continuación el tipo de registro que vas a realizar; puedes hacerlo como Institución educativa o como Otros públicos (grupos de amigos, familiares, clubes, colectivos, corporaciones, fundaciones, entidades, etc.)
<?php echo "</br></br>"?>

Te informamos que tus datos personales se encuentran incluidos en las bases de datos de los Eventos del Libro, proyecto de la Alcaldía de Medellín.
	<?php echo "</br>"?>
Si llenas este formulario, aceptas que tus datos personales sean utilizados por los Eventos del Libro.
</div>

<div class="users form mde-form find-institution">
	<?php echo $this->Form->create('User'); ?>
	<div class="Buscar lista">
		<fieldset>
			<?php echo $this->Form->input('id_institution',array('type'=>'select','empty'=>'Selecciona el tipo de público','options' => array ('1'=>'Institución educativa', '2'=>'Otros públicos'),'id' => 'lista-responsable','label' => 'Selecciona el tipo de público al que perteneces:'));?>	

			<div class="independiente hidden_texto"><p>Si eres Otro público (grupos de amigos, familiares, clubes, colectivos, corporaciones, fundaciones, entidades, etc.), por favor <?php echo $this->Html->link(__('Registrate.'), array('controller' => 'institutions', 'action' => 'add')); ?></p></div>

			<div class="educativa hidden_texto2"><p>Para buscar tu institución educativa por favor ingresa el <strong>nombre</strong> o el <strong>código DANE</strong> en el campo de texto que se encuentra a continuación. Si no lo encuentras, debes registrarla en este enlace <?php echo $this->Html->link(__('Registrar.'), array('controller' => 'institutions', 'action' => 'add')); ?></p></div>

			<div class="autocompleted hidden_lista seccion-person">
				<div class="input" >
					<label>Institución educativa</label>

					<input type="text"  id="completed-institution" class="Institution-autocomplete" data-required="true" data-valcontainer=".results-input" data-emptymsg="Por favor ingresa una institucion" data-limit="1">

					<div class="results-input" data-input-name="data[Institution][Institution][]">
					</div>
				</div>		
			</div>	

			<div class="boton hidden_completed">
				<input type="button" value="Continuar" id="boton-responsable" class="Submit"/>
			</div>	

		</fieldset>
	</div>

	<div class="responsable hidden_book">	
			<fieldset>
				<div class="mde-form legendinsitution"><?php echo __('Agregar Usuario'); ?></div>				
				<?php
					echo $this->Form->input('username',array('id'=>'username'));
				?>
				<div id="Infouser"></div>

				<?php echo $this->Form->input('password');?>				
				<input name="data[User][permission_level]" value="2" id="UserPermissionLevel" type="hidden"/>

				<?php 
					echo $this->Form->input('name');
					echo $this->Form->input('identity',array('label'=>'Documento de identidad','onkeypress'=>'return isNumberKey(event)'));
					echo $this->Form->input('celular',array('onkeypress'=>'return isNumberKey(event)'));
					echo $this->Form->input('mail');
				?>
				
			</fieldset>
		<?php echo $this->Form->end(__('Submit')); ?>
	</div>
</div>
<!-- <div class="actions"> 
	<h3>?php //echo __('Actions'); ?></h3>
	<ul>-->
<!-- 		<li>?php //echo $this->Html->link(__('List Users'), array('action' => 'index')); ?></li> 
	</ul>
</div>	
-->