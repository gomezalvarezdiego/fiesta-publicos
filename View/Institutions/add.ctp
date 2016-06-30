<script type="text/javascript">
<?php $this->set('title_for_layout' , 'Agregar Institución' );?>
function goBack()
  {
  		window.history.back()
  }
</script>
<script type="text/javascript">
 function validarEmail(valor) {
	 var filter=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
  if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(valor)){
  } else {
   alert("La dirección de correo es incorrecta.");
   document.getElementById("InscriptionRepresentativeMail").focus();
   return false;
  }
  return true;
}
 </script> 
 <script type="text/javascript">
 function validarCorreo(valor) {
	 var filter=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
  if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(valor)){
  } else {
   alert("La dirección de correo es incorrecta.");
   document.getElementById("InscriptionContanctMail").focus();
   return false;
  }
  return true;
}
 </script>
 <script type="text/javascript">
 $(document).ready(function() {	
        
 });    

 </script> 
<script type="text/javascript">
      function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
 
         return true;
      }
</script>
<!--  función para los select quemados...-->
<script type="text/javascript">
function checkSubmit()
{
	var objfrm = document;
    if(objfrm.getElementById("InstitutionComune").selectedIndex == 0) 
    {
	    document.getElementById("InstitutionComune").focus();	
	    alert('Por favor elija la comuna donde vive');
	    return false;
     }
     else
     {
     	
     	var dane_code = document.getElementById('Institutioncode_education').value;
     	if(dane_code.charAt(0) == " "){
     		return false;
     	}
        return true;
     }
}

</script>
<div class="descripcion-formularios"><span>Ingresa los datos completos de tu institución educativa o agrupación. Si eres un grupo familiar o de amigos piensa en un nombre que los identifique. Por favor ingresa el nombre exacto. (Ejemplo: Institución Educativa Benedikta Zur Nieden, Fundación Taller de Letras Jordi Sierra i Fabra).
</span>
</div>
<div class="institutions form mde-form forminstitution">
<?php echo $this->Form->create('Institution',array('onsubmit'=>'return checkSubmit()')); ?>

	<fieldset>
		<div class="mde-form legendinsitution"><?php echo __('Agregar'); ?></div>
		<div class="mde-form textinstitution">Si eres Otro tipo de público (grupos de amigos, familiares, clubes, colectivos, corporaciones, fundaciones, entidades, etc.),<?php echo "</br>"?>selecciona la opción Otro tipo de público.</div>
		<?php echo $this->Form->input('inst_type',array ('id' => 'inst_type_id','type'=>'select','options' => array ('Institucion Educativa'=>'Institucion educativa','Institucion Independiente'=>'Otro público'),'label'=>'Tipo de institución','empty'=>'Seleccione tipo de institución'));?>
		<div id="hola2" class="input select required">
				<?php 
					echo $this->Form->input('educational_inst_type',array('type'=>'hidden','label'=>'Tipo Institución educativa'));
					echo $this->Form->input('code_education',array('id'=>'code','type'=>'hidden','label'=>'Código DANE'));		
				?>
		</div>
	<?php
		//echo $this->Form->input('id_institution',array('type'=>'text'));
		echo $this->Form->input('name',array('label'=>'Nombre de la institución o agrupación','maxLength'=>'256'));
		echo $this->Form->input('mail',array('label'=>'Correo electrónico','maxLength'=>'80','type'=>'email','onchange'=>'validarEmail(this.value)'));
		echo $this->Form->input('address',array('label'=>'Dirección','maxLength'=>'80'));
		echo $this->Form->input('phone',array('label'=>'Teléfono','maxLength'=>'10','onkeypress'=>'return isNumberKey(event)'));
		echo $this->Form->input('city',array ('id' => 'city','type'=>'select','options' => array ('Medellin'=>'Medellin','Otras'=>'Otra ciudad o municipio'),'label'=>'Ciudad','empty'=>'Seleccione ciudad'));
				
	?>
		<div id="hola" class="input select required">
		<?php 
			echo $this->Form->input('comune',array('type'=>'hidden','label'=>'Comuna'));
			echo $this->Form->input('neighborhood',array('type'=>'hidden','label'=>'Barrio'));
		
		?>
		</div>	
	</fieldset>
<?php echo $this->Form->end(__('Continuar')); ?>
</div>
<?php
$usuario_level= $this->Session->read('Auth.User.permission_level');	
if ($usuario_level == '1'){?>

<div class="actions">
	<h3><?php //echo __('Actions'); ?></h3>
	<ul>
		<li><?php //echo $this->Html->link(__('Main Menu'), array('controller' => 'institutions', 'action' => 'index')); ?> </li>
		<li><?php //echo $this->Html->link(__('List Institutions'), array('action' => 'index')); ?></li>		
		<li><?php //echo $this->Html->link(__('New Workshop Session'), array('controller' => 'workshop_sessions', 'action' => 'add')); ?> </li>		
		<li><?php //echo $this->Html->link(__('Close Section'), array('controller' => 'users', 'action' => 'logout')); ?> </li>
	</ul>
</div>
<?php }?>
<?php

/*
$this->Js->get('#city');
$this->Js->event('change', $this->Js->alert('hey you!'));
*/

$this->Js->get('#city')->event('change', 
	$this->Js->request(array(
		'controller'=>'institutions',
		'action'=>'getbycity'
		), array(
		'update'=>'#hola',
		'async' => true,
		'method' => 'post',
		'dataExpression'=>true,
		'data'=> $this->Js->serializeForm(array(
			'isForm' => true,
			'inline' => true
			))
		))
	);

$this->Js->get('#inst_type_id')->event('change',
		$this->Js->request(array(
				'controller'=>'institutions',
				'action'=>'getbytype'
		), array(
				'update'=>'#hola2',
				'async' => true,
				'method' => 'post',
				'dataExpression'=>true,
				'data'=> $this->Js->serializeForm(array(
						'isForm' => true,
						'inline' => true
				))
		))
);


?>
