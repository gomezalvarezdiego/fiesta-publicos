﻿<?php
App::uses('AppController', 'Controller');
/**
 * Groups Controller
 *
 * @property Group $Group
 * @property PaginatorComponent $Paginator
 */
class GroupsController extends AppController {

	var $uses = array('Group','Institution','WorkshopSession','User','InstitutionUser','GroupSpecificCondition','SpecificCondition');
	var $helpers = array('Html','Form','Csv','Js');	
/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	
	
	public function beforeFilter() {
		//parent::beforeFilter();
		// Allow users to register and logout.
		$this->Auth->allow('addresp','index','edit','view');
	
	}
	
	public function isAuthorized($user) {
		// Any registered user can access public functions
	
	
		if ((isset($user['permission_level']) && $user['permission_level'] == '2')||(isset($user['permission_level']) && $user['permission_level'] == '1')) {
			return true;
		}
			
	
		// Default deny
		//return false;
			
	}
	
	
	
	
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Group->recursive = 1;
		$this->set('groups', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Group->exists($id)) {
			throw new NotFoundException(__('Invalid group'));
		}
		$options = array('conditions' => array('Group.' . $this->Group->primaryKey => $id));
		$this->set('group', $this->Group->find('first', $options));

		//Se recupera el listado de todos los grupos que corresponden a la carpa (workshop) actual
		$this->WorkshopSession->recursive = 0;
		$this->set('WorkshopSession', $this->Paginator->paginate());
	}
	
	public function view_admin($idgro = null) {
		$iduser = $this->Session->read('Auth.User.id_user');
		$this->set('iduser',$iduser);
		$this->set('idgro',$idgro);
		$groups=$this->Group->query("SELECT
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
				gs.id_group=ws.group_id AND
				pt.id_public_type=gs.public_type_id AND
				us.id_user=gs.user_id AND
				gs.user_id= $iduser");
		$this->set(compact('groups'));
	}
	

	public function addresp() { //$id_user=null

		$publictype = $this->Group->PublicType->find('list');
		$specificConditions = $this->Group->SpecificCondition->find('list');
		$this->set(compact('publictype','specificConditions'));		     	
		

		if ($this->request->is('post')) {
			//validación si no existe el taller.(Debe sacar setflash en la misma vista)...
			date_default_timezone_set('America/Bogota');
			$data=$this->request->data;
			$id_public_type_id=$data['Group']['public_type_id'];
			$SpecificCondition = $data['SpecificCondition']['SpecificCondition'];
			$initialConditions=count($SpecificCondition);
			//debug($data);

			//Busca las carpas que cazan con el tipo de público que tiene el grupo
			$query="SELECT DISTINCT workshop_id
		    FROM
			public_type_workshop
		    WHERE
			public_type_id = '".$id_public_type_id."'";
			$workshop = $this->Group->PublicType->query($query);
    		
    		if($workshop != array()) //Si el resultado de la consulta no es vacío
      		{
      			$work_ids = array();
      			$reals_id = array();
      			foreach ($workshop as $value) {
      				$work_ids[] = $value['public_type_workshop']['workshop_id'];
      			}
      			$s_work_ids = implode(",", $work_ids);

      			//Consulta que devuelve todos los ids de los grupos asociados con las carpas que cumplen el tipo de público
      			$query_val = "SELECT group_id FROM workshop_session WHERE workshop_id in  (" . $s_work_ids .")";
		 		$sql = $this->Group->PublicType->query($query_val);
		 		$flag = false; //bandera...

  				if($SpecificCondition != ''){

  					$s_SpecificCondition = implode(",", $SpecificCondition);
  					$hasWorkshopSpecific=false;

  					foreach ($work_ids as $key => $value) {
  						//Consulta por cada carpa para verificar que cumple con la condición específica
	  					$query = "SELECT t1.*, ws.id_workshop_session
						FROM
						    specific_condition_workshop t1, 
						    workshop_session ws
						WHERE
							t1.workshop_id = $value
					        AND ws.workshop_id = $value
					        AND ws.group_id = 0
					        AND specific_condition_id IN ($s_SpecificCondition)
						group by workshop_id , specific_condition_id					
	  							 ";

	  					
	  					$scq = $this->Group->PublicType->query($query);
	  					
	  					$foundConditions=count($scq);
	  					//debug($foundConditions);
	  					//debug($initialConditions);

	  					if($initialConditions==$foundConditions){
	  						$hasWorkshopSpecific=true;
	  						break;
	  					}
  					}
 
	      			if(!$hasWorkshopSpecific){
	      				$this->Session->setFlash(__('No hay taller para estas condiciones.'));
	      				return false;
	      			}
	      			else{ //Si el tipo de público y las condiciones especificas se cumplen, el grupo se puede crear
	      				$this->Group->create();
					    $id_user = $this->Session->read('Auth.User.id_user');
			 		 	$this->set('id_user',$id_user);
			 		 	$data=$this->request->data;
			 		 	$data['Group']['user_id']=$id_user;
			 		 	$data['Group']['creation_date']=date('Y-m-d H:i:s');

			 			//debug($id_user);
				
			 			if ($this->Group->save($data)) {
			 				$id_group = $this->Group->id;
			 				$this->Session->setFlash(__('The group has been saved.'));
			 				foreach ($sql as $value) {
			 					if($value['workshop_session']['group_id'] == '0'){
			 						$flag = true;
			 					}
		 					}
		 				if($flag){
		 					return $this->redirect(array('controller' => 'WorkshopSessions', 'action' => 'addworkshop',$id_group));
		 				} else {
		 					$this->Session->setFlash(__('Lo sentimos, No hay cupos disponibles para los talleres asignados a este tipo de público.'));
		 				}
			 			} 		 		
			 			else {
			 				$this->Session->setFlash(__('The group could not be saved. Please, try again.'));
			 			}
			 		}
			 	}
			 	else{
		 			$this->Group->create();
				    $id_user = $this->Session->read('Auth.User.id_user');
		 		 	$this->set('id_user',$id_user);
		 		 	$data=$this->request->data;
		 		 	$data['Group']['user_id']=$id_user;
		 		 	$data['Group']['creation_date']=date('Y-m-d H:i:s');
		 			//debug($work_ids);
			
		 			if ($this->Group->save($data)) {
		 				$id_group = $this->Group->id;
		 				$this->Session->setFlash(__('The group has been saved.'));
		 				//debug($id_group);
		 				// $query_val = "SELECT group_id FROM workshop_session WHERE workshop_id in  (" . $s_work_ids .")";
		 				// $sql = $this->Group->PublicType->query($query_val);
		 				// $flag = false; //bandera...
		 				foreach ($sql as $value) {
		 					if($value['workshop_session']['group_id'] == '0'){
		 						$flag = true;
		 					}
		 				}
		 				if($flag){
		 					return $this->redirect(array('controller' => 'WorkshopSessions', 'action' => 'addworkshop',$id_group));
		 				} else {
		 					$this->Session->setFlash(__('Lo sentimos, No hay cupos disponibles para los talleres asignados a este tipo de público.'));
		 				}
		 			} 		 		
		 			else {
		 				$this->Session->setFlash(__('The group could not be saved. Please, try again.'));
		 			}
			 	}
      		}
      		else{
      			$this->Session->setFlash(__('No workshop for this kind of public.'));

 			}     	
		}     	
//fin validación...

		
}
	
	
/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Group->create();
			date_default_timezone_set('America/Bogota');
			$id_user = $this->Session->read('Auth.User.id_user');
		 	$this->set('id_user',$id_user);
		 	$data=$this->request->data;
		 	$data['Group']['user_id']=$id_user;
		 	$data['Group']['creation_date']=date('Y-m-d H:i:s');
			if ($this->Group->save($data)) {
				$this->Session->setFlash(__('The group has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.'));
			}
		}
		
		$publictype = $this->Group->PublicType->find('list');
		$specificConditions = $this->Group->SpecificCondition->find('list');
		$this->set(compact('publictype','specificConditions'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Group->exists($id)) {
			throw new NotFoundException(__('Invalid group'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Group->save($this->request->data)) {
				$this->Session->setFlash(__('The group has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Group.' . $this->Group->primaryKey => $id));
			$this->request->data = $this->Group->find('first', $options);
		}
		$publictype = $this->Group->PublicType->find('list');
		$specificConditions = $this->Group->SpecificCondition->find('list');
		$this->set(compact('publictype','specificConditions'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	/*public function download()
	{
		//set_time_limit(0);
		$this->Group->recursive = 0;
		$this->set('groups',$this->Group->find('all'));
		$this->set('institutions', $this->Institution->find('all'));
		$this->set('workshopSessions',$this->WorkshopSession->find('all'));
		$this->set('users',$this->User->find('all'));
		$this->set('institutionUsers',$this->InstitutionUser->find('all'));		
		$this->set('groupSpecificConditions',$this->GroupSpecificCondition->find('all'));
		$this->set('specificConditions',$this->SpecificCondition->find('all'));
		$this->layout = null;
		//$this->autoLayout = false;
		//Configure::write('debug', '0');
	}*/

public function download()
	{
		//$this->Group->recursive = 0;
		/*$this->set('groups',$this->Group->find('all'));
	    $this->set('institutions', $this->Institution->find('all'));
		$this->set('workshopSessions',$this->WorkshopSession->find('all'));
		$this->set('users',$this->User->find('all'));
		$this->set('institutionUsers',$this->InstitutionUser->find('all'));		
		$this->set('groupSpecificConditions',$this->GroupSpecificCondition->find('all'));
		$this->set('specificConditions',$this->SpecificCondition->find('all'));*/
		
		$queryinforme="SELECT DISTINCT
					    g.id_group AS id_grupo,
					    g.creation_date AS creation_date,
					    g.name AS nombre_grupo,
					    g.members_number AS numero_integrantes,
					    (SELECT 
					            GROUP_CONCAT(CONCAT(' ',name, ' '))
					        FROM
					            group_specific_condition gs,
					            specific_condition s
					        WHERE
					            g.id_group = gs.group_id
					                AND gs.specific_condition_id = s.id_specific_condition ) AS Codiciones_especificas,
					    u.username AS usuario,
					    u.name AS nombre_responsable,
					    u.identity AS cedula,
					    u.celular AS celular,
					    u.mail AS correo,
					    ws.workshop_day AS dia_taller,
					    ws.workshop_time AS hora_taller,
					    ws.travel_time AS hora_recorrido,
					    p.name AS tipo_publico,
					    w.name AS nombre_taller,
					    w.description AS descripcion_taller,
					    e.name AS nombre_entidad,
					    e.description AS descripcion_entidad,
					    i.name AS nombre_institucion,
					    i.mail AS correo_institucion,
					    i.address AS direccion_institucion,
					    i.phone AS telefono_institucion,
					    i.neighborhood AS barrio_institucion,
					    i.comune AS comuna_institucion,
					    i.city AS ciudad_institucion,
					    i.code_education AS codigo_institucion,
					    i.inst_type AS tipo_institucion,
					    i.educational_inst_type AS tipo_institucion_educativa,
					    i.modification_timestamp AS modificacion
					FROM
					    groups g,
					    user u,
					    entity e,
					    institution i,
					    institution_user iu,
					    public_type p,
					    workshop w,
					    workshop_session ws
					WHERE
					    	g.user_id = u.id_user
					        AND g.public_type_id = p.id_public_type
					        AND ws.group_id = g.id_group
					        AND i.id_institution = iu.institution_id
					        AND iu.user_id = u.id_user
					        AND ws.workshop_id = w.id_workshop
					        AND w.entity_id = e.id_entity
					    ORDER BY id_grupo ASC    
     ";
		$informe=$this->Group->query($queryinforme);
		$this->set(compact('informe'));

		//debug($informe);
		//debug($queryinforme);
		
		$this->layout = null;
		//$this->autoLayout = false;
		//Configure::write('debug', '0');
	}
	



	
	public function delete($id_group = null) {
		if ($this->Group->delete($id_group)) {		
			$response['success']=true;
		    
		} else {
			$response['success']=false;
			$response['message']='El Grupo no pudó ser eliminado';
		}
		
		return $response;
	}}
