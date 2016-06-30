<?php
App::uses('AppController', 'Controller');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
App::uses('CakeEmail', 'Network/Email');
/**
 * Responsibles Controller
 *
 * @property Responsible $Responsible
 * @property PaginatorComponent $Paginator
 */
class ResponsiblesController extends AppController {

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
	public function beforeFilter() {
		//parent::beforeFilter();
		// Allow users to register and logout.
		$this->Auth->allow('adduser','finduser','userlist','updateuserlogin','login','enviarcorreo','adduseresp');
	
	}
	
	public function isAuthorized($user) {
		// Any registered user can access public functions
	
	
		if ((isset($user['permission_level']) && $user['permission_level'] == '2')||(isset($user['permission_level']) && $user['permission_level'] === '1')) {
			return true;
		}
			
	
		// Default deny
		//return false;
			
	}
	
	public function index() {
		$usuario_level= $this->Session->read('Auth.User.permission_level');
		if($usuario_level=='2'){
			return $this->redirect(array('action' => 'login'));
		}
		$this->Responsible->recursive = 0;
		$this->set('responsibles', $this->Paginator->paginate());
	}
	
	//Aqui inician las funciones de usuario...
	
	public function login($newpassword=null) {
		/*if($newpassword!='')
			{
		$this->Session->setFlash(__('La contrase�a se ha modificado exitosamente'));
		}*/
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$usuario_level= $this->Session->read('Auth.User.permission_level');
				if($usuario_level=='1'){
						
					return $this->redirect(array('controller' => 'institutions', 'action' => 'index'));
				}
				return $this->redirect(array('controller' => 'Workshops', 'action' => 'index_inscription'));
			}
			$this->Session->setFlash(__('Invalid username or password, try again'));
		}
	}
	
	public function logout() {
		return $this->redirect($this->Auth->logout());
	}
	

	public function finduser() {
		if ($this->request->is('post')) {
			$cedresponsable=$this->request->data['Responsible']['cedresponsable'];
			return $this->redirect(array('action' => 'userlist',$cedresponsable));
		}
	
	}
	
	public function userlist($cedresponsable=null) {
		$this->set('cedresponsable',$cedresponsable);
		$allusers=$this->User->query("select id_responsible,username from responsible where identity = '$cedresponsable'");
		$this->set(compact('allusers'));
	
		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
	}
	
	public function view_user($id = null) {
		$usuario_level= $this->Session->read('Auth.Responsible.permission_level');
		if($usuario_level=='2'){
			return $this->redirect(array('action' => 'login'));
		}
		if (!$this->Responsible->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('Responsible.' . $this->Responsible->primaryKey => $id));
		$this->set('responsible', $this->Responsible->find('first', $options));
	}
	
	public function enviarcorreo($userupd=null,$cedresponsable=null){	
			$this->set('userupd',$userupd);	
			$this->set('cedresponsable',$cedresponsable);	
					
			//$correo = $this->request->data['Responsible']['mail'];
			$correo=$this->Responsible->query("select mail from responsible where identity = '$cedresponsable'");
			$this->set('correo',$correo);		
			$Email = new CakeEmail('gmail');
			$Email->from(array('yypv27@hotmail.com' => 'Fiesta del Libro y la Cultura'));
			
			//Se busca el �ltimo registro correspondiente a la c�dula del responsable.  Esto funciona porque el �ltimo debi� ser justo el que acab� de entrar.  Pero es poco elegante.  Deber�a cambiarse para recupere por el id del responsable actual.
			foreach ($correo as $correo):
			$email_c = $correo['responsible']['mail'];
			endforeach;
			$Email->to($email_c);
			$Email->subject('Link para recuperaci�n de contrase�a');
				$link='http://aplicaciones.medellin.co/reservasfiestadellibro/users/updateuserlogin/'.$userupd;
				$mensaje= "\n\nBIENVENIDO A LA INSCRIPCI�N DE VISITAS GUIADAS PARA LA FIESTA DEL LIBRO Y LA CULTURA 
		\nJard�n Lectura Viva es una estrategia de divulgaci�n art�stica, acad�mica y cultural que tiene en su coraz�n la promoci�n de lectura entre el p�blico m�s joven. Aqu�, instituciones educativas, fundaciones y corporaciones que trabajan por el fomento de la literatura se re�nen para acoger a toda la ciudadan�a con actividades que incluyen la m�sica, la pintura y la ciencia como recursos para dar a conocer libros y escritores de todas las culturas y regiones. 
		\nPor medio de esta herramienta usted asegurar� la participaci�n de sus grupos en una visita guiada a la Fiesta del Libro y la Cultura que contiene un recorrido  y un taller de promoci�n de lectura en Jard�n Lectura Viva. Usted mismo podr� decidir la fecha, la hora y el taller en el que participar�. Para iniciar el proceso debes registrarte y  crear un usuario. Si ya lo creaste, ingresa tus datos y separe su participaci�n.";
				$Email->send("Por favor de clic en el siguiente link o copielo y peguelo en su navegador ".$link.$mensaje);		
	}
	
	//Creacion de funcion para recuperar el usuario y su contrase�a...
	public function updateuserlogin($userupd = null)
	{
		$this->set('userupd',$userupd);
		if ($this->request->is('post')) {
				
			$newpassword = $this->request->data['Responsible']['password'];
			$repitnewpassword= $this->request->data['Responsible']['repit_password'];
				
			if($newpassword==$repitnewpassword)
			{
					
				$passwordHasher = new SimplePasswordHasher();
				$clavencriptada = $passwordHasher->hash($newpassword);
					
					
					
				$this->set('newpassword',$newpassword);
				$update_usuarios=$this->Responsible->query("UPDATE responsible SET password = '$clavencriptada' where username = '$userupd'");
				$this->set(compact('update_usuarios'));
					
				$this->Session->setFlash(__('La contrase�a se ha modificado exitosamente'));
				return $this->redirect(array('controller' => 'responsibles','action' => 'login'));
			}
			else{
				$this->Session->setFlash(__('Las contrase�as no coinciden, por favor ingreselas nuevamente'));
				return $this->redirect(array('controller' => 'responsibles', 'action' => 'updateuserlogin',$userupd));
			}
		}
	}
	
	public function addus($institution = null,$institutionid= null) {
		$this->set('institution',$institution);
		$this->set('institutionid',$institutionid);
		if ($this->request->is('post')) {			
			$username= $this->request->data['Responsible']['username'];
			$verificar_usuario=$this->Responsible->query("select distinct username from responsible where username = '$username'");
			$this->set('verificar_usuario',$verificar_usuario);
			if($verificar_usuario==Array( )){
					
				$this->User->create();
				if ($this->User->save($this->request->data)) {
					$this->Session->setFlash(__('The user has been saved.'));
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
				}
			}
	
			else {
				$this->Session->setFlash(__('El nombre de usuario no est� disponible, por favor ingrese uno nuevo.'));
			}
	
		}
		$institutions = $this->User->Institution->find('list');
		$this->set(compact('institutions'));
	}
	
	public function adduser_us($institution = null,$institutionid= null) {
		$this->set('institution',$institution);
		$this->set('institutionid',$institutionid);
		if ($this->request->is('post')) {
				
			$username= $this->request->data['Responsible']['username'];
			$pass= $this->request->data['Responsible']['password'];
				
			$verificar_usuario=$this->Responsible->query("select distinct username from responsible where username = '$username'");
			$this->set('verificar_usuario',$verificar_usuario);
			if($verificar_usuario==Array( )){
					
				$this->Responsible->create();
				if ($this->Responsible->save($this->request->data)) {
					$this->Session->setFlash(__('The user has been saved.'));
					if ($this->Auth->login()) {
						return $this->redirect($this->Auth->redirect(array('controller' => 'workshops', 'action' => 'index_inscription',$pass)));
					}
	
				} else {
					$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
				}
			}
			else {
				$this->Session->setFlash(__('El nombre de usuario no est� disponible, por favor ingrese uno nuevo.'));
			}
		}
		$institutions = $this->Responsible->Institution->find('list');
		$this->set(compact('institutions'));
	}
	
	public function edit_user($id = null) {
		$usuario_level= $this->Session->read('Auth.Responsible.permission_level');
		if($usuario_level=='2'){
			return $this->redirect(array('controller' => 'responsibles', 'action' => 'login'));
		}
		if (!$this->Responsible->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Responsible->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Responsible.' . $this->Responsible->primaryKey => $id));
			$this->request->data = $this->Responsible->find('first', $options);
		}
		$institutions = $this->Responsible->Institution->find('list');
		$this->set(compact('institutions'));
	}
	
	public function delete_user($id = null) {
		$usuario_level= $this->Session->read('Auth.Responsible.permission_level');
		if($usuario_level=='2'){
			return $this->redirect(array('controller' => 'responsibles', 'action' => 'login'));
		}
		$this->Responsible->id = $id;
		if (!$this->Responsible->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Responsible->delete()) {
			$this->Session->setFlash(__('The user has been deleted.'));
		} else {
			$this->Session->setFlash(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}	
	
	//Hasta aqui llegan todas la funciones de usuario...

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$usuario_level= $this->Session->read('Auth.User.permission_level');
		if($usuario_level=='2'){
			return $this->redirect(array('controller' => 'users', 'action' => 'login'));
		}
		if (!$this->Responsible->exists($id)) {
			throw new NotFoundException(__('Inv�lido responsable'));
		}
		$options = array('conditions' => array('Responsible.' . $this->Responsible->primaryKey => $id));
		$this->set('responsible', $this->Responsible->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function adduseresp($institution=null,$institutionid= null) {
		$this->set('institution',$institution);
		$this->set('institutionid',$institutionid);
		if ($this->request->is('post')) {
			$this->Responsible->create();
			//$id_respons_adduser = $this->request->data['Responsible']['id_responsible'];
			//$responsable_adduser_id = $this->Responsible->find('first', array('conditions'=>array('Responsible.id_responsible' => $id_respons_adduser)));
			//if($responsable_adduser_id != array())
			//{
				//$this->Session->setFlash(__('El documento ya existe.Ingrese uno nuevo por favor!'));
				//return $this->redirect(array('controller' => 'responsibles', 'action' => 'adduser',$institution,$institutionid));
			//}
			//else
			
			//Verificaci�n por si el usuario se devuelve en el navegador y vuelve a intentar crear el responsable asociado a la misma instituci�n.  Esto igual hace que existan regstros de responsables repetidos, se genera basura, pero esa basura se podr�a limpiar.
			$existeinstitucion=$this->Responsible->find('first', array('conditions'=>array('Responsible.institution_id' => $institutionid)));
			if ($existeinstitucion==array()){
				if ($this->Responsible->save($this->request->data)) {
					$this->Session->setFlash(__('El responsable ha sido guardado.'));
					//return $this->redirect(array('action' => 'index'));
					return $this->redirect(array('controller' => 'users', 'action' => 'adduser',$institution,$institutionid));
				} else {
					$this->Session->setFlash(__('El responsable no pud� ser guardado. Por favor, int�ntelo de nuevo.'));
				}
			}
			else{
				//Si se devolvi�, pero quiere volver a avanzar utilizando la misma c�dula, se puede permitir que vaya a crear el usuario.  Sino tiene la misma c�dula se devuelve al formulario de instituci�n.  Esta operaci�n puede generar basura, pero se puede limpiar.
				if ($existeinstitucion['Responsible']['identity']!=$this->request->data['Responsible']['identity']){
					$this->Session->setFlash(__('El responsable con su instituci�n no pudieron ser guardados. Por favor, int�ntelo de nuevo.'));
					return $this->redirect(array('controller' => 'institutions', 'action' => 'add'));
				}
				else{
					return $this->redirect(array('controller' => 'users', 'action' => 'adduser',$institution,$institutionid));
				}
			}
		}		
		$institutions = $this->Responsible->Institution->find('list',array('order'=>array('Institution.name ASC')));
		$this->set(compact('institutions'));
	}
	
	
	public function addresp() {
		if ($this->request->is('post')) {
			$this->Responsible->create();
			//$id_responsable = $this->request->data['Responsible']['id_responsible'];
			//$responsable_id = $this->Responsible->find('first', array('conditions'=>array('Responsible.id_responsible' => $id_responsable)));
			//if($responsable_id != array())
			//{
				//$this->Session->setFlash(__('El documento ya existe.Ingrese uno nuevo por favor!'));
			//}
			//else 
			if ($this->Responsible->save($this->request->data)) {			
				$this->Session->setFlash(__('El responsable ha sido guardado.'));
				
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El responsable no pud� ser guardado. Por favor, int�ntelo de nuevo.'));
			}
		}
		$institutions = $this->Responsible->Institution->find('list');
		$this->set(compact('institutions'));
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
		/*if($usuario_level=='2'){
			return $this->redirect(array('controller' => 'users', 'action' => 'login'));
		}*/
		if (!$this->Responsible->exists($id)) {
			throw new NotFoundException(__('Invalid responsible'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Responsible->save($this->request->data)) {
				$this->Session->setFlash(__('The responsible has been saved.'));
				if($usuario_level=='1')
				{
					return $this->redirect(array('action' => 'index'));
				}
				else if($usuario_level=='2')
				{
					return $this->redirect(array('controller'=>'workshops','action' => 'index_inscription'));
				}
			} else {
				$this->Session->setFlash(__('The responsible could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Responsible.' . $this->Responsible->primaryKey => $id));
			$this->request->data = $this->Responsible->find('first', $options);
		}
		$educationalInstitutions = $this->Responsible->Institution->find('list');
		$this->set(compact('Institutions'));
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
		$this->Responsible->id = $id;
		if (!$this->Responsible->exists()) {
			throw new NotFoundException(__('Invalid responsible'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Responsible->delete()) {
			$this->Session->setFlash(__('The responsible has been deleted.'));
		} else {
			$this->Session->setFlash(__('The responsible could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
