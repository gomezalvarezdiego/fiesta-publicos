<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::import('Controller', 'Groups');
/**
 * Workshops Controller
 *
 * @property Workshop $Workshop
 * @property PaginatorComponent $Paginator
 */
class WorkshopsController extends AppController {
	
	var $uses = array('Workshop','Register','User','Institution','WorkshopSession','PublicType','Group');
	
/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');
	

/**
 * index method
 *
 * @return void
 */

	
	public function isAuthorized($user) {
		// Any registered user can access public functions
	
	
		if ((isset($user['permission_level']) && $user['permission_level'] == '2')||(isset($user['permission_level']) && $user['permission_level'] == '1')) {
			return true;
		}
			
	
		// Default deny
		//return false;
			
	}
	
	//Controlador que muestra los horarios disponibles en un taller en el que un grupo se puede inscribir.
	public function workshop_inscription($id = NULL,$datework=null,$id_group=null)  {
		
		$this->set('datework',$datework);
		$this->set('id_group',$id_group);
		if (!$this->Workshop->exists($id)) {
			throw new NotFoundException(__('Invalid Workshop'));
		}
		$options = array('conditions' => array('Workshop.' . $this->Workshop->primaryKey => $id));
		$this->set('taller', $this->Workshop->find('first', $options));
		

		//Query que devuelve el dìa del taller escogido
		$queryday="select distinct workshop_session.workshop_day from workshop_session inner join workshop on  workshop.id_workshop = workshop_session.workshop_id where workshop.id_workshop = '$id' and workshop_session.workshop_day = '$datework' and workshop_session.full = 0";
		$tallerday=$this->Workshop->query($queryday);
		//$this->set(compact('tallerday'));
		
		$this->set('tallerday',$tallerday);

		//Si la anterior consulta no retorna ningún resultado quiere decir que mientras el grupo actual hacia la inscripción otro tomó la sesiones que estaban disponibles.
		if ($tallerday == Array ( ))
		{
			$this->Session->setFlash(__('El taller no tiene horarios disponibles en esta fecha,Por favor seleccione otro taller o regrese al calendario y seleccione otra fecha'));
			return $this->redirect(array('controller' => 'WorkshopSessions', 'action' => 'workshoplist',$datework));
			
		}
		
		//Si la consulta anterior retorna resultados, se pasa la información a la vista 
		foreach ($tallerday as $tallerday):
			$tallerdayp=$tallerday['workshop_session']['workshop_day'];
		endforeach;
		$this->set('tallerdayp',$tallerdayp);
		
		//Consulta que devuelve los horarios en los que está disponible el taller actual.  Con base en las sesiones que tienen cupo.
		$querytime="select distinct workshop_session.workshop_time, workshop_session.id_workshop_session, workshop_session.workshop_id from workshop_session inner join workshop on  workshop.id_workshop = workshop_session.workshop_id where workshop.id_workshop = '$id' and workshop_session.workshop_day = '$datework' and workshop_session.full = 0";
		
		$tallertimeprevios=$this->Workshop->query($querytime);

		//debug($tallerday);
		//debug($tallertimeprevios);
		//Poner en el listado de horas solo aquellas en las que hay cupo disponible para este grupo.
		$tallertime=array();
		foreach ($tallertimeprevios as $tallertimeprevio){
			$id_workshop_session=$tallertimeprevio['workshop_session']['id_workshop_session'];
			$workshop_id=$tallertimeprevio['workshop_session']['workshop_id'];

			//*Verificar si el cupo de la sesión está lleno
		
			//Consultar el numero de inscritos en la sesión
			$numero_inscritos_query = $this->Group->query("SELECT sum(members_number) FROM `groups` where workshop_session_id='$id_workshop_session'");
			$suma_inscritos=$numero_inscritos_query[0][0]['sum(members_number)'];

			//Consultar el cupo máximo de la carpa/taller asociada a esta sesion
			$cupo_maximo_query = $this->Workshop->query("select room from workshop where id_workshop='$workshop_id'");
			$cupo_maximo_carpa=	$cupo_maximo_query[0]['workshop']['room'];
		
			//Consultar el número de miembros del grupo en cuestión
			$miembros_grupo_query=$this->Group->query("SELECT sum(members_number) FROM `groups` where id_group='$id_group'");
			
			$miembros_grupo=$miembros_grupo_query[0][0]['sum(members_number)'];
			$cupo_disponible=$cupo_maximo_carpa - $suma_inscritos;
			if ($cupo_disponible >= $miembros_grupo){
				$tallertime[]=$tallertimeprevio;
			}
		}

		//Se construye la lista con todos los horarios disponibles y se envía a la vista.
		$tallertimen = '';
		foreach ($tallertime as $tallertimes):
			$hora=$tallertimes['workshop_session']['workshop_time'];
			$horaconformato= date('h i a', strtotime($hora));
			$tallertimen = $tallertimen.'<option value="'.$tallertimes['workshop_session']['workshop_time'].'">'.$horaconformato.'</option>';
		endforeach;
		$this->set('tallertimen',$tallertimen);
		
		//Una vez se selecciona la hora y se la da clic al botón de inscripción redirige a workshop_update para guardar la inscripción.
		if ($this->request->is('post')) {	
			$horataller= $this->request->data['Workshop']['horataller'];
			$this->set('horataller',$horataller);
			$timestamp=strtotime($horataller);
			return $this->redirect(array('controller' => 'workshops','action' => 'workshop_update',$datework,$timestamp,$id_group,$id));
		}
	
	}
	
	//Guarda una inscripción luego de que el usuario ha seleccionado la hora
	public function workshop_update($datework=null,$timestamp=null,$id_group=null, $workshopid=null)  {
		$this->set('datework',$datework);
		$horataller=date('H:i:s',$timestamp);
		$this->set('horataller',$horataller);
		$this->set('institutionidp',$institutionidp);
		
		//
		$condicion=$this->Workshop->query("select group_id from workshop_session where group_id = '$id_group'");
		$this->set('condicion',$condicion);
		//debug($institutionidp);
		$condicionp='';
		foreach ($condicion as $condiciones):
		$condicionp=$condiciones['workshop_session']['group_id'];
		endforeach;
		$this->set('condicionp',$condicionp);
		
		if($condicionp == 0)
		{
			

		//Consulta para poner el id del grupo en la sesion escogida.  TODO: Borrar
		$queryupdate="update workshop_session SET group_id = '$id_group' where workshop_session.workshop_day = '$datework' and workshop_session.workshop_time= '$horataller' and workshop_session.workshop_id= '$workshopid'";
		$tallerupdate=$this->Workshop->query($queryupdate);
		$this->set(compact('tallerupdate'));

		//Se busca el id del workshop_session seleccionado (Que cumple con todos los parámetros)
		$queryfinds="select id_workshop_session from workshop_session where workshop_session.workshop_day = '$datework' and workshop_session.workshop_time= '$horataller' and workshop_session.workshop_id= '$workshopid'";
		$sessions=$this->Workshop->query($queryfinds);
		$id_workshop_session='';
		foreach ($sessions as $session):
		$id_workshop_session=$session['workshop_session']['id_workshop_session'];
		endforeach;


		//Consulta para poner el id de la sesión en el grupo.
		$queryupdategroup="update groups SET workshop_session_id = '$id_workshop_session' where groups.id_group='$id_group'";
		$groupupdate=$this->Group->query($queryupdategroup);

		//*Verificar si el cupo de la sesión está lleno
		
		//Consultar el numero de inscritos en la sesión
		$numero_inscritos_query = $this->Group->query("SELECT sum(members_number) FROM `groups` where workshop_session_id='$id_workshop_session'");
		$suma_inscritos=$numero_inscritos_query[0][0]['sum(members_number)'];

		//Consultar el cupo máximo de la carpa/taller asociada a esta sesion
		$cupo_maximo_query = $this->Workshop->query("select room from workshop where id_workshop='$workshopid'");
		$cupo_maximo_carpa=	$cupo_maximo_query[0]['workshop']['room'];
	//debug($suma_inscritos."/".$cupo_maximo_carpa);
		//Si el cupo de la sesión está lleno, se debe poner el valor full=1 en la tabla de sesiones.  TODO:  Considerar el desfase de 5.  Por ejemplo, si inscribo 37, y el cupo màximo es 40, no se llenará, pero luego nunca habrá forma de que se llene porque el grupo debe tener mìnimo 5 integrantes. 
		if($suma_inscritos>=$cupo_maximo_carpa){
			$queryupdatesession=$this->WorkshopSession->query("update workshop_session SET full=1 where workshop_session.id_workshop_session='$id_workshop_session'");
		}
		
		//register de inscripcion...
		$usuario = $this->Session->read('Auth.User.username');
		$this->set('usuario',$usuario);
		/*
		$institutioniid=$this->Workshop->query("select institution.id_institution from institution inner join user on institution.id_institution = user.institution_id  where user.username = '$usuario'");
		foreach ($institutioniid as $institutioniid):
		$institutioniidp=$institutioniid['institution']['id_institution'];
		
		endforeach;
		$this->set('institutionidp',$institutioniidp);
		
		$condicionn=$this->Workshop->query("select institution_id,workshop_id,workshop_day,workshop_time,travel_time from workshop_session where institution_id = $institutioniidp");
		*/
		$condicionn=$this->Workshop->query("select group_id,workshop_id,workshop_day,workshop_time,travel_time from workshop_session where group_id = $id_group");
		
		
		$this->set('condicionn',$condicionn);
		$condicionnp='';
		foreach ($condicionn as $condicionnes):
		$condicionnp=$condicionnes['workshop_session']['group_id'];
		
		endforeach;
		$this->set('condicionnp',$condicionnp);
			
		foreach ($condicionn as $condicionnes):
		$condicionnid=$condicionnes['workshop_session']['workshop_id'];
		
		endforeach;
		$this->set('$condicionnid',$condicionnid);
		
		$condicionname=$this->Workshop->query("select name from workshop where id_workshop = $condicionnid");
		foreach ($condicionname as $condicionam):
		$condicionnomb=$condicionam['workshop']['name'];
		endforeach;
		$this->set('condicionnomb',$condicionnomb);
		
		$estado = "Inscrito";
		$horas_diferencia= -5;
		$tiempo=time() + ($horas_diferencia * 60 *60);
		list($Mili, $bot) = explode(" ", microtime());
		$DM=substr(strval($Mili),2,4);
		$fecha = date('Y-m-d H:i:s:'. $DM,$tiempo);
		$this->set('fecha',$fecha);
		
		$this->Register->create();
		$this->Register->set(array(
				'date' => $fecha,
				'username' => $usuario,
				'estado' => $estado,
				'workshop' => $condicionnomb
		));
		$this->Register->save();
		/*foreach ($condicion as $condiciones):
		$condiciond=$condiciones['workshop_session']['workshop_day'];
		$condiciont=$condiciones['workshop_session']['workshop_time'];
		$condiciontra=$condiciones['workshop_session']['travel_time'];
		endforeach;
			
		$this->set('condiciond',$condiciond);
		$this->set('condiciont',$condiciont);
		$this->set('condiciontra',$condiciontra);
		*/
		}
		


		return $this->redirect(array('controller' => 'workshops','action' => 'view_inscription',$id_group));	
	}
	
	public function view_inscription($id_group=null)  {
	
	
		//$this->set('institution',$institution);
		//debug($institution);
		$this->set('id_group',$id_group);
	
		$usuario = $this->Session->read('Auth.User.username');
		$this->set('usuario',$usuario);
	
		$groupid=$this->Workshop->query("select groups.id_group,groups.name,groups.members_number,groups.user_id from groups inner join user on groups.user_id = user.id_user  where user.username = '$usuario' and groups.id_group = '$id_group' ");
		//debug($institutionid);
	
		foreach ($groupid as $groupid):
		$groupidp=$groupid['groups']['id_group'];
		$groupname=$groupid['groups']['name'];
		$groupnumber=$groupid['groups']['members_number'];
		$groupuser=$groupid['groups']['user_id'];
		//debug($institutionname);
	
		endforeach;
		$this->set('groupidp',$groupidp);
		$this->set('groupname',$groupname);
		$this->set('groupnumber',$groupnumber);
		$this->set('groupuser',$groupuser);
		//Nombre y Celular del Responsable o Encargado...
		$responsibles=$this->User->find('all', array('conditions'=>array('id_user'=>$groupuser),'fields'=>array('name','celular','id_user')));
		$rname=null;
		$rcelular=null;
		$ruser=null;
		foreach ($responsibles as $responsible){
			$rname=$responsible['User']['name'];
			$rcelular=$responsible['User']['celular'];
			$ruser=$responsible['User']['id_user'];
		}
		$this->set('rname',$rname);
		$this->set('rcelular',$rcelular);
		$this->set('ruser',$ruser);
		//fin
		//$condicion=$this->Workshop->query("select workshop_session.group_id from workshop_session inner join groups on workshop_session.group_id = groups.id_group where workshop_session.group_id = $groupidp");
		$condicion=$this->Workshop->query("select group_id,workshop_id,workshop_day,workshop_time,travel_time from workshop_session where group_id = $groupidp");
	
		//$condicion=";
		//$condicion="select user.institution_id from user inner join (institution inner join workshop_session on institution.id_institution = workshop_session.institution_id) on user.institution_id = institution.id_institution where user.username = $usuario";
		//$queryid="select distinct workshop.id_workshop from specific_condition inner join (specific_condition_workshop inner join (public_type inner join (public_type_workshop inner join (workshop inner join workshop_session on workshop.id_workshop = workshop_session.workshop_id) on public_type_workshop.workshop_id = workshop.id_workshop) on public_type.id_public_type = public_type_workshop.public_type_id) on specific_condition_workshop.workshop_id = workshop.id_workshop) on  specific_condition.id_specific_condition = specific_condition_workshop.specific_condition_id where workshop_session.workshop_day = '$datework' and public_type.name = '$public_typep' and specific_condition.name = ";
		//$condicion=$this->Workshop->query($condicion);
		//$this->set(compact('tallerday'));
	
		$institutionid=$this->Workshop->query("select institution_id from institution_user where user_id='$ruser' group by institution_id limit 1");
		$institutionidn=null;
		foreach ($institutionid as $institutionid):

		$institutionidn=$institutionid['institution_user']['institution_id'];
	
		endforeach;	
		$this->set('condicion',$condicion);
		$condicionp='';
		foreach ($condicion as $condiciones):
		$condicionp=$condiciones['workshop_session']['group_id'];
	
		endforeach;
		$this->set('condicionp',$condicionp);
	
	
		if ($condicionp != 0)
		{
				
			foreach ($condicion as $condiciones):
			$condicionid=$condiciones['workshop_session']['workshop_id'];
			$condiciond=$condiciones['workshop_session']['workshop_day'];
			$condiciont=$condiciones['workshop_session']['workshop_time'];
			$condiciontra=$condiciones['workshop_session']['travel_time'];
			endforeach;
			$this->set('condiciond',$condiciond);
			$this->set('condiciont',$condiciont);
			$this->set('condiciontra',$condiciontra);
			$this->set('condicionid',$condicionid);
				
			$condicioname=$this->Workshop->query("select name from workshop where id_workshop = $condicionid");
			foreach ($condicioname as $condicionm):
			$condicionnom=$condicionm['workshop']['name'];
			endforeach;
			$this->set('condicionnom',$condicionnom);
				
			//$correoi=$this->User->query("select distinct mail from responsible inner join (insitution inner join user on institution.id_institution=user.institution_id)on responsible.institution_id=institution.id_institution where id_responsible = '$crcedula'");
			$correoi=$this->User->find('all', array('conditions'=>array('id_user'=>$groupuser)));
			$correoi2=$this->Institution->find('all', array('conditions'=>array('id_institution'=>$institutionidn)));
			$this->set('correoi',$correoi);
			$this->set('correoi2',$correoi2);
			$Email = new CakeEmail('gmail');
			$Email->from(array('publicos@fiestadellibroylacultura.com' => 'Fiesta del Libro y la Cultura'));
			foreach ($correoi as $correoi):
			$email_c = $correoi['User']['mail'];
			endforeach;
			$email_c2=null;
			foreach ($correoi2 as $correoi2):
			$email_c2 = $correoi2['Institution']['mail'];
			endforeach;
			$Email->to($email_c);
			//$Email->cc($email_c2);  //No enviarle correo a la institución
			$Email->subject('¡CONFIRMACIÓN DE VISITA A LA FIESTA DEL LIBRO Y LA CULTURA!');
			//$link='http://aplicaciones.medellin.co/reservasfiestadellibro/workshops/index_inscription/'.$usuario;
			$mensaje1="\n\n¡Ya está​ lista tu inscripción! Aquí está la información del taller en el que participará el grupo que inscribiste. Recuerda que puedes hacer clic en imprimir para tenerla física.​";
			$mensaje12="Es necesario que la presentes en el ingreso el día de tu visita. Si no tienes disponible impresora en este momento puedes volver a ingresar con tu usuario y contraseña para imprimirlo.​ \n\n";
			$mensaje22="\nNombre del grupo: ";
			$mensaje11="\nEl Día: ";
			$mensaje2="\nNombre Taller: ";
			$mensaje3="\nHora Taller: ";
			$mensaje4="\nHora Recorrido: ";
			$mensaje5="\nNombre del Encargado: ";
			$mensaje6="\nCelular del Encargado: ";
			$mensaje7="\nCantidad de Inscritos: ";
			$recomendaciones="\n\nPara tener en cuenta:";
			$recomendacion10="\n\n • Todos los talleres se realizan en el Jardín Botánico de Medellín.";
			$recomendacion1="\n\n• El ingreso de los grupos es por la entrada peatonal del Jardín Botánico (cerca de la Estación Universidad del Metro, Calle 73). Este es el único acceso donde es posible validar la inscripción a la zona de Jardín Lectura Viva.";
			$recomendacion11="\n\n • Es necesario que el grupo llegue con 30 minutos de anticipación para hacer el registro y no generar retrasos en la actividad.";
			$recomendacion12="\n\n • La actividad tiene una duración de dos (2) horas por grupo, es importante tener en cuenta que esta programación se hace con el fin de atender a todo el público que quiera asistir a la Fiesta, por lo tanto es indispensable respetar las actividades programadas para los demás grupos y no ingresar a las carpas de promoción de lectura sin inscripción previa.";
			$recomendacion21="\n\n• Durante la actividad el grupo contará con el acompañamiento de un guía, pero es indispensable que el responsable del grupo esté  permanentemente.";
			$recomendacion32="\n\n• El Jardín Botánico no se hace responsable del transporte y la alimentación de los grupos, sin embargo está permitido ingresar alimentos a sus instalaciones.";
			$recomendacion41="\n\n• Es necesario que los niños, de los grupos de primera infancia y  hasta los 8 años, estén identificados con escarapela.";
			$recomendacion42="\n\n• Es importante llevar hidratación.";
			$recomendacion43="\n\n• La entrada y participación en el evento no tienen costo, la ciudadanía de Medellín ya pagó con sus impuestos.";
			$recomendacion44="\n\n Gracias por hacer parte de esta gran fiesta de ciudad que durante diez días nos recordará que";
			$recomendacion45="\n\n Medellín es lectura viva";
			$recomendacion46="\n\n ES INDISPENSABLE PRESENTAR ESTE FORMATO AL INGRESO DEL JARDÍN BOTÁNICO";
			$recomendacion47="\n\n Mayores informes:";
			$recomendacion48="\n\n Tatiana Sierra Velásquez";
			$recomendacion49="\n\n Líder Instituciones Educativas Públicas";
			$recomendacion40="\n\n 322 09 97 ext. 113";
			$recomendacion50="\n\n 3108908821";
			$recomendacion51="\n\n inscripciones@fiestadellibroylacultura.com";
			$recomendacion52="\n\n Carolina Cortés Duque";
			$recomendacion53="\n\n Líder Públicos Dirigidos";
			$recomendacion54="\n\n 322 09 97 ext 102";
			$recomendacion55="\n\n 3052569184";
			$recomendacion56="\n\n inscripcionespublicos@fiestadellibroylacultura.com";			
			$norespuesta="\n\n Este correo es informativo, favor no responder a esta dirección de correo, ya que no se encuentra habilitada para recibir mensajes.";
			$dia_taller= date('d M Y', strtotime($condiciond));
			$hora_taller= date('h i a', strtotime($condiciont));
			$hora_recorrido= date('h i a', strtotime($condiciontra));
			$Email->send($mensaje1.$mensaje12.$mensaje11.$dia_taller.
					$mensaje2.$condicionnom.$mensaje3.$hora_taller.$mensaje4.
					$hora_recorrido.$mensaje22.$groupname.$mensaje5.$rname.$mensaje6.$rcelular.$mensaje7.
					$groupnumber.$recomendaciones.$recomendacion10.$recomendacion1.$recomendacion11.$recomendacion12.$recomendacion21.
					$recomendacion32.$recomendacion41.$recomendacion42.$recomendacion43.$recomendacion44.$recomendacion45.$recomendacion46.
					$recomendacion47.$recomendacion48.$recomendacion49.$recomendacion40.$recomendacion50.$recomendacion51.$recomendacion52.
					$recomendacion53.$recomendacion54.$recomendacion55.$recomendacion56.$norespuesta);
	
		}
	}

	
	public function view_inscript($id_group=null)  {
	
	
		//$this->set('institution',$institution);
		//debug($institution);
		$this->set('id_group',$id_group);
	
		$usuario = $this->Session->read('Auth.User.username');
		$this->set('usuario',$usuario);
	
		$groupid=$this->Workshop->query("select groups.id_group,groups.name,groups.members_number,groups.user_id, groups.workshop_session_id from groups inner join user on groups.user_id = user.id_user  where user.username = '$usuario' and groups.id_group = '$id_group' ");
		//debug($institutionid);
	
		foreach ($groupid as $groupid):
		$groupidp=$groupid['groups']['id_group'];
		$groupname=$groupid['groups']['name'];
		$groupnumber=$groupid['groups']['members_number'];
		$groupuser=$groupid['groups']['user_id'];
		$id_workshop_session=$groupid['groups']['workshop_session_id'];
		//debug($institutionname);
	
		endforeach;
		$this->set('groupidp',$groupidp);
		$this->set('groupname',$groupname);
		$this->set('groupnumber',$groupnumber);
		$this->set('groupuser',$groupuser);
		//Nombre y Celular del Responsable o Encargado...
		$responsibles=$this->User->find('all', array('conditions'=>array('id_user'=>$groupuser),'fields'=>array('name','celular','id_user')));
		$rname=null;
		$rcelular=null;
		$ruser=null;
		foreach ($responsibles as $responsible){
			$rname=$responsible['User']['name'];
			$rcelular=$responsible['User']['celular'];
			$ruser=$responsible['User']['id_user'];
		}
		$this->set('rname',$rname);
		$this->set('rcelular',$rcelular);
		$this->set('ruser',$ruser);
		//fin
		//$condicion=$this->Workshop->query("select workshop_session.group_id from workshop_session inner join groups on workshop_session.group_id = groups.id_group where workshop_session.group_id = $groupidp");
		$condicion=$this->Workshop->query("select group_id,workshop_id,workshop_day,workshop_time,travel_time from workshop_session where id_workshop_session = $id_workshop_session");
	
		//$condicion=";
		//$condicion="select user.institution_id from user inner join (institution inner join workshop_session on institution.id_institution = workshop_session.institution_id) on user.institution_id = institution.id_institution where user.username = $usuario";
		//$queryid="select distinct workshop.id_workshop from specific_condition inner join (specific_condition_workshop inner join (public_type inner join (public_type_workshop inner join (workshop inner join workshop_session on workshop.id_workshop = workshop_session.workshop_id) on public_type_workshop.workshop_id = workshop.id_workshop) on public_type.id_public_type = public_type_workshop.public_type_id) on specific_condition_workshop.workshop_id = workshop.id_workshop) on  specific_condition.id_specific_condition = specific_condition_workshop.specific_condition_id where workshop_session.workshop_day = '$datework' and public_type.name = '$public_typep' and specific_condition.name = ";
		//$condicion=$this->Workshop->query($condicion);
		//$this->set(compact('tallerday'));
	
		$institutionid=$this->Workshop->query("select institution_id from institution_user where user_id='$ruser' group by institution_id limit 1");
		foreach ($institutionid as $institutionid):
		$institutionidn=$institutionid['institution_user']['institution_id'];
	
		endforeach;
	
		$this->set('condicion',$condicion);
		
		//Se define una variable para saber si lo se está mostrando es la informaciòn del grupo o si se està mostrando la informaciòn del usuario recien inscrito
		$condicionp='';
		foreach ($condicion as $condiciones):
		//$condicionp=$condiciones['workshop_session']['group_id'];
		$condicionp=$groupidp;
	
		endforeach;
		$this->set('condicionp',$condicionp);
	
		//Si la variable no es igual a cero, es porque se encontró alguna sesión asociada al grupo, entonces se muestra la información del grupo 
		if ($condicionp != 0)
		{
	
			foreach ($condicion as $condiciones):
			$condicionid=$condiciones['workshop_session']['workshop_id'];
			$condiciond=$condiciones['workshop_session']['workshop_day'];
			$condiciont=$condiciones['workshop_session']['workshop_time'];
			$condiciontra=$condiciones['workshop_session']['travel_time'];
			endforeach;
			$this->set('condiciond',$condiciond);
			$this->set('condiciont',$condiciont);
			$this->set('condiciontra',$condiciontra);
			$this->set('condicionid',$condicionid);
	
			$condicioname=$this->Workshop->query("select name from workshop where id_workshop = $condicionid");
			foreach ($condicioname as $condicionm):
			$condicionnom=$condicionm['workshop']['name'];
			endforeach;
			$this->set('condicionnom',$condicionnom);
	
	
		}
	}
	
	public function index_inscription($pass=null)  {
		$iduser = $this->Session->read('Auth.User.id_user');
		$this->set('iduser',$iduser);
	
		$groups=$this->Workshop->query("SELECT
		gs.id_group,		  
  		gs.name, 
  		gs.members_number, 
  		pt.name,
  		wp.name, 
  		ws.workshop_day, 
  		ws.workshop_time, 
  		ws.travel_time, 
  		us.name,
		(SELECT GROUP_CONCAT(CONCAT(' ', name, ' '))
		FROM
		group_specific_condition gsc,
		specific_condition sc
		WHERE	
		gs.id_group= gsc.group_id and
		gsc.specific_condition_id= sc.id_specific_condition) AS specific_conditions  
        
 		FROM
 		workshop_session ws,
 		groups gs,
 		public_type pt,
 		user us,
 		workshop wp
                        
 		WHERE                     
		wp.id_workshop=ws.workshop_id AND
        gs.workshop_session_id=ws.id_workshop_session AND
    	pt.id_public_type=gs.public_type_id AND
    	us.id_user=gs.user_id AND
		gs.user_id= $iduser");
		$this->set(compact('groups'));
		
	}
	
	public function workshop_cancel($id_group=null) {
		
		$usuario = $this->Session->read('Auth.User.username');
		$this->set('usuario',$usuario);
		
		$this->set('id_group',$id_group);
		
		$Groups = new GroupsController();

		//Consulta que recupera la información del grupo a cancelar
		$groupid=$this->Workshop->query("select groups.id_group,groups.name,groups.members_number,groups.user_id, groups.workshop_session_id from groups inner join user on groups.user_id = user.id_user  where user.username = '$usuario' and groups.id_group = '$id_group' ");
		
		$groupidp=null;
		$groupname=null;
		$groupnumber=null;
		$groupuser=null;
		$id_workshop_session=null;
		foreach ($groupid as $groupid):
		$groupidp=$groupid['groups']['id_group'];
		$groupname=$groupid['groups']['name'];
		$groupnumber=$groupid['groups']['members_number'];
		$groupuser=$groupid['groups']['user_id'];
		$id_workshop_session=$groupid['groups']['workshop_session_id'];
		
	
		endforeach;
		$this->set('groupidp',$groupidp);
		$this->set('groupname',$groupname);
		$this->set('groupnumber',$groupnumber);
		$this->set('groupuser',$groupuser);

		//*** Registro en el log

		//Se averigua el nombre del responsable
		$responsibles=$this->User->find('all', array('conditions'=>array('id_user'=>$groupuser),'fields'=>array('name','celular','id_user')));
		$rname=null;
		$rcelular=null;
		$ruser=null;
		foreach ($responsibles as $responsible){
			$rname=$responsible['User']['name'];
			$rcelular=$responsible['User']['celular'];
			$ruser=$responsible['User']['id_user'];
		}
		$this->set('rname',$rname);
		$this->set('rcelular',$rcelular);
		$this->set('ruser',$ruser);

		//Se sacan los datos de la sesión 
		$condicionn=$this->Workshop->query("select group_id,workshop_id,workshop_day,workshop_time,travel_time from workshop_session where id_workshop_session = $id_workshop_session");
		$this->set('condicionn',$condicionn);
		$condicionnp='';
		//Se obtiene el grupo asignado a la sesión
		foreach ($condicionn as $condicionnes):
			//$condicionnp=$condicionnes['workshop_session']['group_id'];
			$condicionnp=$groupidp;
		endforeach;
		$this->set('condicionnp',$condicionnp);
		
		$condicionnid='';
		foreach ($condicionn as $condicionnes):
		$condicionnid=$condicionnes['workshop_session']['workshop_id'];
		$condicionday=$condicionnes['workshop_session']['workshop_day'];
		$condiciontime=$condicionnes['workshop_session']['workshop_time'];
		$condiciontravel=$condicionnes['workshop_session']['travel_time'];
		
		endforeach;
		$this->set('$condicionnid',$condicionnid);
		
		//Se obtiene el nombre del taller.
		$condicionname=$this->Workshop->query("select name from workshop where id_workshop = '$condicionnid'");
		$condicionnomb = '';
		foreach ($condicionname as $condicionam):
		$condicionnomb=$condicionam['workshop']['name'];
		endforeach;
		$this->set('condicionnomb',$condicionnomb);
		$estado = "Cancelado";
		//Se toma el tiempo de la cancelación
		$horas_diferencia= -5;
		$tiempo=time() + ($horas_diferencia * 60 *60);
		list($Mili, $bot) = explode(" ", microtime());
		$DM=substr(strval($Mili),2,4);
		$fecha = date('Y-m-d H:i:s:'. $DM,$tiempo);
		$this->set('fecha',$fecha);
		//Con los datos optenidos, se registra la cancelaciòn
		$this->Register->create();
		$this->Register->set(array(
				'date' => $fecha,
				'username' => $usuario,
				'estado' => $estado,
				'workshop' => $condicionnomb
		));
		$this->Register->save();
		
		//********Envió de Correo...
		//Se consulta toda la informaciòn del grupo
		$groupid=$this->Workshop->query("select groups.id_group,groups.name,groups.members_number,groups.user_id from groups inner join user on groups.user_id = user.id_user  where user.username = '$usuario' and groups.id_group = '$id_group' ");
		
		$groupidp=null;
		$groupname=null;
		$groupmember=null;
		$groupuser=null;

		foreach ($groupid as $groupid):
		$groupidp=$groupid['groups']['id_group'];
		$groupname=$groupid['groups']['name'];
		$groupmember=$groupid['groups']['members_number'];
		$groupuser=$groupid['groups']['user_id'];
			
		endforeach;
		$this->set('groupidp',$groupidp);
		$this->set('groupname',$groupname);
		$this->set('groupmember',$groupmember);
		$this->set('groupuser',$groupuser);
			
		//Se actualiza la información de la sesion del taller	
		$queryupdate=$this->Workshop->query("update workshop_session SET full = '0' where workshop_session.id_workshop_session = '$id_workshop_session'");
		$this->set(compact('queryupdate'));
		
		$Groups = new GroupsController;
		debug($queryupdate);
		
		//Si la actualización de la información en la tabla de sesiones es exitosa
		if($queryupdate == array() or $queryupdate == true)
		{
			//Se borra el grupo
			$Groups_response = $Groups->delete($id_group);
			
			//Envió de Correo...
			
			$user_canc=$this->User->find('all', array('conditions'=>array('id_user'=>$groupuser),'fields'=>array('id_user')));
			$ruser=null;
			
			foreach ($user_canc as $user_cancl)
			{
				$ruser=$user_cancl['User']['id_user'];
			}
			$this->set('ruser',$ruser);
			
			$institutionid=$this->Workshop->query("select institution_id from institution_user where user_id='$ruser' group by institution_id limit 1");
			
			$institutionidn=null;
			foreach ($institutionid as $institutionid):
			$institutionidn=$institutionid['institution_user']['institution_id'];
			endforeach;				
				
			//$correoi=$this->User->query("select distinct mail from responsible inner join (insitution inner join user on institution.id_institution=user.institution_id)on responsible.institution_id=institution.id_institution where id_responsible = '$crcedula'");
			$correoi=$this->User->find('all', array('conditions'=>array('id_user'=>$groupuser)));
			$correoi2=$this->Institution->find('all', array('conditions'=>array('id_institution'=>$institutionidn)));
			$this->set('correoi',$correoi);
			$this->set('correoi2',$correoi2);
			$Email = new CakeEmail('gmail');
			$Email->from(array('publicos@fiestadellibroylacultura.com' => 'Fiesta del Libro y la Cultura'));
			foreach ($correoi as $correoi):
			$email_c = $correoi['User']['mail'];
			endforeach;
			$email_c2=null;
			foreach ($correoi2 as $correoi2):
			$email_c2 = $correoi2['Institution']['mail'];
			endforeach;
			$Email->to($email_c);
			$Email->cc($email_c2);
			$Email->subject('¡CANCELACIÓN DE VISITA A LA FIESTA DEL LIBRO Y LA CULTURA!');
			//$link='http://aplicaciones.medellin.co/reservasfiestadellibro/workshops/index_inscription/'.$usuario;
			$mensaje1="\n\nHemos recibido la cancelación de la inscripción realizada para visitar la zona de Jardín ";
			$mensaje12="\n\nLectura Viva en la Fiesta del Libro y la Cultura. La siguiente inscripción ha sido cancelada,";
			$mensaje13="\n\npor lo tanto no estará este espacio disponible para tu grupo:";
			$mensaje11="\nEl Día: ";
			$mensaje2="\nNombre Taller: ";
			$mensaje3="\nHora Taller: ";
			$mensaje4="\nHora Recorrido: ";
			$mensaje22="\nNombre del grupo: ";
			$mensaje5="\nNombre del Encargado: ";
			$mensaje6="\nCelular del Encargado: ";
			$mensaje7="\nCantidad de Inscritos: ";
			$mensaje8="\n\nEste documento es la confirmación de la cancelación del taller, esperamos que te inscribas ";
			$mensaje9="\nnuevamente en el horario que mejor se ajuste a tus necesidades para que no pierdas esta";
			$mensaje10="\noportunidad.";
			$dia_taller= date('d M Y', strtotime($condicionday));
			$hora_taller= date('h i a', strtotime($condiciontime));
			$hora_recorrido= date('h i a', strtotime($condiciontravel));
			$Email->send($mensaje1.$mensaje12.$mensaje13.$mensaje11.$dia_taller.
					$mensaje2.$condicionnomb.$mensaje3.$hora_taller.$mensaje4.
					$hora_recorrido.$mensaje22.$groupname.$mensaje5.$rname.$mensaje6.$rcelular.$mensaje7.
					$groupmember.$mensaje8.$mensaje9.$mensaje10);
	
			
			//Fin envió de correo...		
			
			if (!$Groups_response['success']){
				$this->Session->setFlash(__($Groups_response['message']));
			}
			else{				
				$this->Session->setFlash(__('El grupo ha sido eliminado.'));
			}
		}
		else { //Si la actualización de la información en la tabla de sesiones no fue exitosa
			$this->Session->setFlash(__('El grupo no pudó ser eliminado. Por favor, Intenta de nuevo.'));
		}
		return $this->redirect(array('action' => 'index_inscription'));							
	}	
	
	public function register(){
	if ($this->request->is('post')) {
		//$datework= $this->request->data['WorkshopSession']['workshop_day'];
		$datos= $this->request->data['User']['username'];
		return $this->redirect(array('controller' => 'workshops', 'action' => 'listado',$datos));
		}
	}
	
	public function listado($datos=null){
		$this->set('datos',$datos);
		$datos_list=$this->Register->query("select date,estado,workshop,username from register where username = '$datos'");
		$this->set('datos_list',$datos_list);	
		$this->Register->recursive = 0;
		$this->set('registers', $this->Paginator->paginate('Register'));
		
		if ($this->request->is('post')) {
			return $this->redirect(array('action' => 'download'));
		}
	}
	
	public function download()
	{
		$this->Register->recursive = 0;
		$this->set('registers', $this->Register->find('all'));
		$this->layout = null;
		//$this->autoLayout = false;
		//Configure::write('debug', '0');
	}
	
	public function index() {
		$usuario_level= $this->Session->read('Auth.User.permission_level');
		if($usuario_level=='2'){
			return $this->redirect(array('controller' => 'users', 'action' => 'login'));
		}
		//$workshop=$this->Workshop->find('all');
		//$this->set('workshops', $workshop);
		$this->Workshop->recursive = 0;
		$this->set('workshops', $this->Paginator->paginate('Workshop'));
		
	}
	

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Workshop->exists($id)) {
			throw new NotFoundException(__('Invalid workshop'));
		}
		
		$options = array('conditions' => array('Workshop.' . $this->Workshop->primaryKey => $id));
		$this->set('workshop', $this->Workshop->find('first', $options));
		
		//Se recupera el listado de todos los grupos que corresponden a la carpa (workshop) actual
		$this->WorkshopSession->recursive = 0;
		$this->set('WorkshopSession', $this->Paginator->paginate());
		//$groups = $this->Workshop->WorkshopSession->Institution->find('all', array('conditions'=>array('WorkshopSession.workshop_id'=>$id)));
		$groups = $this->WorkshopSession->find('all',array('conditions'=>array('Workshop.id_workshop'=>$id)));
		$this->set('groups',$groups);
		//debug($var)		
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$usuario_level= $this->Session->read('Auth.User.permission_level');
		if($usuario_level=='2'){
			return $this->redirect(array('controller' => 'users', 'action' => 'login'));
		}
		if ($this->request->is('post')) {
			$this->Workshop->create();
			if ($this->Workshop->save($this->request->data)) {
				$this->Session->setFlash(__('The workshop has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The workshop could not be saved. Please, try again.'));
			}
		}
		$entities = $this->Workshop->Entity->find('list', array('order'=>array('Entity.name ASC')));
		$publicTypes = $this->Workshop->PublicType->find('list');
		$specificConditions = $this->Workshop->SpecificCondition->find('list', array('order'=>array('SpecificCondition.name ASC')));
		$this->set(compact('entities', 'publicTypes', 'specificConditions'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$usuario_level= $this->Session->read('Auth.User.permission_level');
		if($usuario_level=='2'){
			return $this->redirect(array('controller' => 'users', 'action' => 'login'));
		}
		if (!$this->Workshop->exists($id)) {
			throw new NotFoundException(__('Invalid workshop'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Workshop->save($this->request->data)) {
				$this->Session->setFlash(__('The workshop has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The workshop could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Workshop.' . $this->Workshop->primaryKey => $id));
			$this->request->data = $this->Workshop->find('first', $options);
		}
		$entities = $this->Workshop->Entity->find('list');
		$publicTypes = $this->Workshop->PublicType->find('list');
		$specificConditions = $this->Workshop->SpecificCondition->find('list');
		$this->set(compact('entities', 'publicTypes', 'specificConditions'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$usuario_level= $this->Session->read('Auth.User.permission_level');
		if($usuario_level=='2'){
			return $this->redirect(array('controller' => 'users', 'action' => 'login'));
		}
		$this->Workshop->id = $id;
		if (!$this->Workshop->exists()) {
			throw new NotFoundException(__('Invalid workshop'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Workshop->delete()) {
			$this->Session->setFlash(__('The workshop has been deleted.'));
		} else {
			$this->Session->setFlash(__('The workshop could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
