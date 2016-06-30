<?php
 //Se crea la linea de encabezado
 //Se obtienen los nombres que están dentro del array de groups
 //$line= $groups[0]['Group'];

 $line=(array('id_grupo'=>"",'creation_date'=>"",'name_grupo'=>"",'members_number'=>"",'specific_condition'=>"",'workshop_day'=>"",'workshop_time'=>"",'travel_time'=>"",'workshop_name'=>"",'description_workshop'=>"",'entity_name'=>"",'entity_description'=>"",'username'=>"",'name_user'=>"",'identity'=>"",'celular_user'=>"",'mail_user'=>"",'name_specicondition'=>"",'Public_Type_name'=>"",'name_institution'=>"",'mail_inst'=>"",'address_inst'=>"",'phone_inst'=>"",'neighborhood_inst'=>"",'comune_inst'=>"",'city_inst'=>"",'code_education_inst'=>"",'inst_type_inst'=>"",'educational_inst_type_inst'=>"",'modification_timestamp_inst'=>"",'specific_conditioninstitution'=>""));
 //Se mezclan los encabezados obtenidos más otros adicionales.
 //$line= array_merge($line, array('workshop_day'=>"",'workshop_time'=>"",'travel_time'=>"",'name_workshop'=>"",'description'=>"",'username'=>"",'name_user'=>"",'identity'=>"",'celular_user'=>"",'mail_user'=>"",'name_specicondition'=>"",'Public_Type_name'=>"",'name_institution'=>"",'mail_inst'=>"",'address_inst'=>"",'phone_inst'=>"",'neighborhood_inst'=>"",'comune_inst'=>"",'city_inst'=>"",'code_education_inst'=>"",'inst_type_inst'=>"",'educational_inst_type_inst'=>"",'modification_timestamp_inst'=>"",'specific_conditioninstitution'=>""));
 //Se adiciona la linea de encabezados.
 
	  $line['id_grupo']='Id grupo';
	  $line['creation_date']='Fecha de creación';
	  $line['name_grupo']='Nombre Grupo';
	  $line['members_number']='Numero integrantes grupo';
	  $line['specific_condition']='Condiciones específicas grupo';
	  $line['Public_Type_name']='Tipo público grupo';
	  $line['workshop_day']='Día taller';
	  $line['workshop_time']='Hora taller';
	  $line['travel_time']='Hora recorrido';
	  $line['workshop_name']='Nombre taller';
	  $line['description_workshop']='Descripción taller';
	  $line['entity_name']='Nombre entidad';
	  $line['entity_description']='Descripción entidad';
	  $line['username']='Usuario';
	  $line['name_user']='Nombre responsable';
	  $line['identity']='Cédula responsable';
	  $line['celular_user']='Celular responsable';
	  $line['mail_user']='Correo responsable';
	  $line['name_institution']='Nombre institución';
	  $line['mail_inst']='Correo institución';
	  $line['address_inst']='Dirección Institución';
	  $line['phone_inst']='Teléfono institución';
	  $line['neighborhood_inst']='Barrio institución';
	  $line['comune_inst']='Comuna institución';
	  $line['city_inst']='Ciudad institución';
	  $line['code_education_inst']='Código institución';
	  $line['inst_type_inst']='Tipo institución';
	  $line['educational_inst_type_inst']='Tipo institución educativa';
	  $line['modification_timestamp_inst']='Fecha de modificación';
 
 $line=recursiveSanitize($line);
 $this->Csv->addRow($line);
 
 //debug($groups);
 
 //Por cada groups se obtienen todos los datos
 foreach ($informe as $infor)
 {
	  //Se define la linea que se creará creando primero el array con los datos de la isntitución de la iteración actual
 	  //$line = $institution['Institution'];
	  $line['id_grupo']=$infor['g']['id_grupo'];
	  $line['creation_date']=$infor['g']['creation_date'];
	  $line['name_grupo']=$infor['g']['nombre_grupo'];
	  $line['members_number']=$infor['g']['numero_integrantes'];
	  $line['specific_condition']=$infor['0']['Codiciones_especificas'];
	  $line['Public_Type_name']=$infor['p']['tipo_publico'];
	  $line['workshop_day']=$infor['ws']['dia_taller'];
	  $line['workshop_time']=$infor['ws']['hora_taller'];
	  $line['travel_time']=$infor['ws']['hora_recorrido'];
	  $line['workshop_name']=$infor['w']['nombre_taller'];
	  $line['description_workshop']=$infor['w']['descripcion_taller'];
	  $line['entity_name']=$infor['e']['nombre_entidad'];
	  $line['entity_description']=$infor['e']['descripcion_entidad'];
	  $line['username']=$infor['u']['usuario'];
	  $line['name_user']=$infor['u']['nombre_responsable'];
	  $line['identity']=$infor['u']['cedula'];
	  $line['celular_user']=$infor['u']['celular'];
	  $line['mail_user']=$infor['u']['correo'];
	  $line['name_institution']=$infor['i']['nombre_institucion'];
	  $line['mail_inst']=$infor['i']['correo_institucion'];
	  $line['address_inst']=$infor['i']['direccion_institucion'];
	  $line['phone_inst']=$infor['i']['telefono_institucion'];
	  $line['neighborhood_inst']=$infor['i']['barrio_institucion'];
	  $line['comune_inst']=$infor['i']['comuna_institucion'];
	  $line['city_inst']=$infor['i']['ciudad_institucion'];
	  $line['code_education_inst']=$infor['i']['codigo_institucion'];
	  $line['inst_type_inst']=$infor['i']['tipo_institucion'];
	  $line['educational_inst_type_inst']=$infor['i']['tipo_institucion_educativa'];
	  $line['modification_timestamp_inst']=$infor['i']['modificacion'];
	  
	  
 	  //Se mezcla el array obtenido anteriormente con un nuevo array que contiene los demás campos, los cuales por ahora se ponen vacios.
     // $line= array_merge($line, array('workshop_day'=>"",'workshop_time'=>"",'travel_time'=>"",'name_workshop'=>"",'description'=>"",'username'=>"",'name_user'=>"",'identity'=>"",'celular_user'=>"",'mail_user'=>"",'name_specicondition'=>"",'Public_Type_name'=>"",'name'=>"",'name'=>"",'mail'=>"",'address'=>"",'phone'=>"",'neighborhood'=>"",'comune'=>"",'city'=>"",'code_education'=>"",'inst_type'=>"",'educational_inst_type'=>"",'modification_timestamp'=>"",'name'=>"",'description'=>""));
      
      
      
      //debug($line);
      
      //Se adiciona la linea que resulta de la iteración actual
	   $line=recursiveSanitize($line);
       $this->Csv->addRow($line);
       //debug($$informe);
 }
 $date=date("Y-m-d-H-i-s");
 $filename='grupos_'.$date;
 echo  $this->Csv->render($filename);
?>