<script language="JavaScript">
<?php $this->set('title_for_layout' , 'Inscripción carpa' );?>
function doPrint(){
document.all.item("noprint").style.visibility='hidden';
document.getElementById("btnImprimir").style.display='none';
window.print();
}
</script>

<div class="inscripciones form">
<!--  <a href="javascript:window.print()"> imprimir </a>-->
<?php echo $this->Form->create('Inscripcion'); ?>
	<table>
		<tr><p></p></tr>
		<tr>
			<?php		
			if ($condicionp != 0)
			{			

					echo "¡Ya está​ lista tu inscripción! Aquí está la información del taller en el que participará el grupo que inscribiste. Recuerda que puedes hacer clic en imprimir para tenerla física.​ Es necesario que la presentes en el ingreso el día de tu visita. Si no tienes disponible impresora en este momento puedes volver a ingresar con tu usuario y contraseña para imprimirlo.​  </br></br></br>";
					
					echo "El Día:\n";
					
					//echo $institutionidp;
					//echo "id de la institucion";
					//echo  $condicionp;
					echo $this->Time->format($condiciond, '%B %e, %Y');				
					echo "</br>";
					echo "Nombre Taller:\n";
					echo $this->Html->link($condicionnom, array('controller' => 'workshops', 'action' => 'view', $condicionid));
					echo "</br>";
					echo "Hora Taller:\n";
					echo  $this->Time->format($condiciont, '%I:%M %p');;
					echo "</br>";					
					echo "Hora Recorrido:\n";
					echo  $this->Time->format($condiciontra, '%I:%M %p');
					echo "</br>";
					echo "Nombre del grupo:\n";
					echo $groupname;
					echo "</br>";
					echo "Nombre del Encargado:\n";
					echo  $rname;	
					echo "</br>";
					echo "Celular del Encargado:\n";
					echo  $rcelular;
					echo "</br>";
					echo "Cantidad de Inscritos:\n";
					echo  $groupnumber;
					echo "</br>";
					echo "</br>";
					echo "Para tener en cuenta:";
					echo "</br>";
					echo "</br>";
					echo "<p>"."• Todos los talleres se realizan en el Jardín Botánico de Medellín."."</p>";
					echo "<p>"."• El ingreso de los grupos es por la entrada peatonal del Jardín Botánico (cerca de la Estación Universidad del Metro, Calle 73). Este es el único acceso donde es posible validar la inscripción a la zona de Jardín Lectura Viva."."</p>";
					echo "<p>"."• Es necesario que el grupo llegue con 30 minutos de anticipación ​para hacer el registro y no generar retrasos en la actividad."."</p>";
					echo "<p>"."• La actividad tiene una duración de dos (2) horas por grupo​.  Es importante tener en cuenta que esta programación se hace con el fin de atender a todo el público que quiera asistir a la Fiesta, por lo tanto​, es indispensable respetar las actividades programadas para los demás grupos y no ingresar a las carpas de promoción de lectura sin inscripción previa."."</p>";
					echo "<p>"."• Durante la actividad el grupo contará con el acompañamiento de un guía, pero es indispensable que el responsable del grupo esté permanentemente."."</p>";
					echo "<p>"."• El Jardín Botánico no se hace responsable del transporte y la alimentación de los grupos​ pero sí está permitido ingresar alimentos a sus instalaciones."."</p>";
					echo "<p>"."• Es necesario que los niños​ ​de los grupos ​entre primera infancia y los 8 años, estén identificados con escarapela."."</p>";
					echo "<p>"."• Es importante llevar hidratación."."</p>";
					echo "<p>"."• La entrada y participación en el evento no tienen costo. La ciudadanía de Medellín ya pagó con sus impuestos."."</p>";
					echo "</br>";					
					echo "<p style='text-align:center;'>"." Gracias por hacer parte de esta gran fiesta de ciudad que durante diez días nos recordará "."</p>";
					echo "<p style='text-align:center;'>"."que <i><b>Medellín es Lectura Viva</b></i>"."</p>";
					echo "</br>";
					echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>ES INDISPENSABLE PRESENTAR ESTE FORMATO AL INGRESO DEL JARDÍN BOTÁNICO</b>"."</p>";
					echo "</br>";
					echo "<p>"."<b>Mayores informes:</b>"."</p>";
					echo "<p>"."<b>Tatiana Sierra Velásquez</b>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Carolina Cortés Duque</b>"."</p>";
					echo "<b>Líder Instituciones Educativas Públicas</b>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <b>Líder Públicos Dirigidos</b>"."</p>";
					echo "<p>"."<b>3220997 ext. 113</b>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <b>3220997 ext. 102</b>"."</p>";
					echo "<p>"."<b>3108908821</b>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>3052569184</b>"."</p>";
					echo "<p>"."<b>inscripciones@fiestadellibroylacultura.com</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <b>inscripcionespublicos@fiestadellibroylacultura.com</b>"."</p>";
			}

				if (($condicionp == 0)) {					
                  if (($pass != '')) {
					echo 'Tu registro ha sido guardado: tu usuario '.$usuario.' y contraseña '.$pass.', continúa haciendo click en el botón '."<i>".'inscripción carpa '. "</i>". 'para reservar el cupo en el taller que elijas.';
				
                  }
				  else{
						  	
						echo 'Tu usuario es: '.$usuario.', continúa  haciendo  click en el botón '."<i>".'inscripción carpa  '. "</i>".'para reservar el cupo en el taller que elijas.';
					}
				}
			
					?>
			
			</tr>			
	</table>
	<div class="submit">
	<input type="submit" id="btnImprimir" name="imprimir" value="Imprimir" onclick="window.doPrint();">
	</div>
</div>

<div id=noprint>

<div class="actions">
	<h3><?php echo __('Opciones'); ?></h3>
	<ul>	
		<li><?php echo $this->Html->link(__('Lista Grupos'), array('controller' => 'workshops', 'action' => 'index_inscription')); ?> </li>
		<li><?php 
		if (($condicionp == 0)) {
		echo $this->Html->link(__('Inscripción grupo'), array('controller' => 'Groups', 'action' => 'addresp')); 
		}
		?></li>
		
		<li><?php 
		if (($condicionp != 0)) {
		echo $this->Html->Link(__('Workshop Cancel'), array('controller' => 'Workshops', 'action' => 'workshop_cancel',$id_group), null, __('¿Está seguro que desea cancelar la carpa?, Recuerde que al cancelar deberá registrar nuevamente su grupo y el taller.')); 		
		}
		?></li>
		<li><?php echo $this->Html->link(__('Editar datos usuario'), array('controller' => 'Users','action' => 'edit',$ruser)); ?> </li>
		<li><?php echo $this->Html->link(__('Close Section'), array('controller' => 'users', 'action' => 'logout')); ?> </li>
	</ul>
</div>
</div>
