<div class="explanation">
<?php //$this->set('title_for_layout' , 'Ingreso' );?>
<!--¡Bienvenido!
</br></br>
Este es el portal en el que puedes inscribirte para participar en los talleres de promoción de lectura de la 10ª Fiesta del libro y la cultura.
</br></br>
Tú mismo puedes elegir a qué taller asistir, qué día y a qué hora. Para iniciar el proceso debes registrarte creando un usuario. Si ya lo creaste, ingresa tus datos y realiza la inscripción.
</br></br>
En Jardín Lectura Viva te invitamos a leer la vida desde el arte, la música, la ciencia, el cine, la fotografía, la literatura y la pintura. ¡Déjate sorprender!
</br></br>
Recuerda que la visita consta del taller de promoción de lectura que elijas y de un recorrido guiado, de una hora, por los espacios de la Fiesta.
</div>
-->

¡Bienvenido!
</br></br>
Te invitamos a conocer Nuevos Mundos; escoge tu equipaje, organiza tu mochila, empaca tu imaginación, limpia tus anteojos y prepárate para ser parte de Jardín Lectura Viva.
</br></br>
Inscribiéndote en este aplicativo podrás participar en los Talleres de Promoción Lectura en la Zona de Jardín Lectura Viva. Para hacerlo solo debes ir al botón de registro y seguir las instrucciones.
</br></br>
¡Leer es la mejor manera de comenzar el viaje!
</br></br>
Recuerda que la visita consta del taller de promoción de lectura que elijas y de un recorrido guiado, de una hora, por los espacios de la Fiesta.
</br></br>
	Si tienes alguna duda puedes comunicarte con:
	</br></br>
	Carolina Cortés Duque 
	</br>
	Líder Públicos Dirigidos
	</br> 
	inscripcionespublicos@fiestadellibroylacultura.com
	</br> 
	Celular: 3234843230
	</br>
	Tel: 3220997 Ext 102
	

	</br></br>
	Tatiana Sierra Velásquez
	</br>
	Líder Instituciones Educativas
	</br>
	inscripciones@fiestadellibroylacultura.com
	</br>
	Tel: 3220997 Ext 102
</div>

<!-- <div class="explanation">
	¡Ya están cerradas las inscripciones vía web para los talleres de fomento a la lectura de Jardín Lectura Viva! 
	
	</br></br>
	Si no lograste hacer la inscripción de tu grupo puedes acercarte a Rotonda del Jardín Botánico, cerca de la estación del Metro Universidad. Allí puedes ingresar con tu grupo y disfrutar de todas las actividades de la Fiesta del Libro y la Cultura.
	
	</br></br>
	Para mayor información puedes comunicarte con:
	</br></br>
	Carolina Cortés Duque 
	</br>
	Líder Públicos Dirigidos
	</br> 
	inscripcionespublicos@fiestadellibroylacultura.com
	</br> 
	Celular: 3234843230
	</br>
	Tel: 3220997 Ext 102
	

	</br></br>
	Tatiana Sierra Velásquez
	</br>
	Líder Instituciones Educativas
	</br>
	inscripciones@fiestadellibroylacultura.com
	</br>
	Tel: 3220997 Ext 102

</div>
<!--  <div class="inscription">

</div> -->
 	<div class="login">
		<?php echo $this->Session->flash('auth'); ?>
		<?php echo $this->Form->create('User'); ?>
		    <fieldset>
		        <legend>
		            <?php echo __('Please enter your username and password'); ?>
		        </legend>
		        <?php echo $this->Form->input('username',array('label'=>'Usuario'));
		         echo $this->Form->input('password',array('label'=>'Contraseña'));     
		        ?>
		    
		    </fieldset>
		   
		<?php echo $this->Form->end(__('Ingresar')); ?>
		<?php echo $this->Html->link(__('Regístrate'), array('controller' => 'institutions', 'action' => 'findinstitution')); ?>
		 <span><?php echo 'para ingresar por primera vez' ?></span>
		 </br></br></br>
		<?php echo $this->Html->link(__('¿Olvidaste tu contraseña?'), array('controller' => 'Users', 'action' => 'finduser')); ?>
		</n> 
	</br></br></br>	</br>
	<span>La inscripción y la participación en los talleres es gratuita.</span>
	</br></br></br>
	<span>Si tienes dudas o necesitas ayuda durante el proceso, comunícate con nosotros al teléfono <strong>3220997</strong>, extensión <strong>102</strong></span>
	</div> 




