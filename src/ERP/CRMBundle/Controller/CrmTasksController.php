<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CrmActividad;
use ERP\CRMBundle\Entity\CrmCuenta;
use ERP\CRMBundle\Entity\CrmAsignacionActividad;
use ERP\CRMBundle\Form\CrmActividadType;

/**
 * CrmActividad controller.
 *
 * @Route("/admin/tasks")
 */
class CrmTasksController extends Controller
{
    /**
     * Lists all CrmActividad entities.
     *
     * @Route("/", name="admin_tasks_index")
     * @Method("GET")
     */
    public function indexAction()
    {

            // $calendarId =$serverSave = $this->getParameter('app.googlecalendar');
            // $superGoogle= $this->get('calendar.google')->getFirstSyncToken($calendarId);
            // // $eventId = $crmActividadObj->getGoogleId();
            // // $calendarId =$serverSave = $this->getParameter('app.googlecalendar');
            // //                 $superGoogle= $this->get('calendar.google')->getFirstSyncToken($calendarId);
            // //                 $eventId = 'r59fhrog8on7gljt7i4nkoh9co';
            // //                 $eventAttendee = array();
                            
                            
            //                 // Recuperar información del evento de google calendar, la url es necesaria para poder consultar, lleva el id del calendario, id del evento y el token de acceso
            //                 $superGoogle= $this->get('calendar.google')->getToken();
            // $url = 'https://www.googleapis.com/calendar/v3/calendars/fbk7pcdkcncqo0f3ur264nimsk%40group.calendar.google.com/events/i3s7m1oim79tlacs38sodu5c6c/?access_token='.str_replace('"', '', $superGoogle);
            // var_dump($url);
            // $data = file_get_contents('https://www.googleapis.com/calendar/v3/calendars/fbk7pcdkcncqo0f3ur264nimsk%40group.calendar.google.com/events/raeb85ku40t9l8orn0qjtclkjo/?access_token='.str_replace('"', '', $superGoogle));
            // var_dump(get_class_methods($superGoogle));
            // var_dump(json_decode($data));
            // die();
            // $eventAttendee = array();
            // $dateInicio = new \DateTime('now');
            // $dateFin = new \DateTime('now');
            // $eventStart = $dateInicio;
            // $eventEnd = $dateFin;
            
            // $eventSummary = '';
            // $eventDescription = '';
                    
            // $optionalParams = [];

            // $superGoogle= $this->get('calendar.google')->addEvent($calendarId,$eventStart,$eventEnd,$eventSummary,$eventDescription,$eventAttendee,$optionalParams = []);
            // var_dump($superGoogle);
            // die();


        // $em = $this->getDoctrine()->getManager();

        // $crmActividads = $em->getRepository('ERPCRMBundle:CrmActividad')->findAll();

        // return $this->render('crmactividad/index.html.twig', array(
        //     'crmActividads' => $crmActividads,
        // ));
        try{
            $em = $this->getDoctrine()->getManager();

            // $crmCuentas = $em->getRepository('ERPCRMBundle:CrmCuenta')->findAll();
            $response = new JsonResponse();

            //Persona-usuarios
            $personas = $em->getRepository('ERPCRMBundle:CtlUsuario')->findBy(array('estado' => 1));
            //Estado
            $estados = $em->getRepository('ERPCRMBundle:CrmEstadoActividad')->findAll();
            //Tipo recordatorio
            $recordatorios = $em->getRepository('ERPCRMBundle:CtlTipoRecordatorio')->findAll();
            //Tipo recordatorio
            $tiempos = $em->getRepository('ERPCRMBundle:CtlTiempoNotificacion')->findBy(array('estado' => 1), array('tiempoReal' => 'ASC'));
            //Prioridad
            $prioridad = $em->getRepository('ERPCRMBundle:CtlPrioridad')->findBy(array('estado' => 1));
            //Actividades
            $actividades = $em->getRepository('ERPCRMBundle:CrmTipoActividad')->findBy(array('estado' => 1));

            // //$personas = $em->getRepository('ERPCRMBundle:CtlTipoEntidad')->findBy(array('estado'=>1));
            // $personas = $em->getRepository('ERPCRMBundle:CtlTipoEntidad')->findAll();
            // //Tipo industria
            // $industrias = $em->getRepository('ERPCRMBundle:CtlIndustria')->findAll(array('estado'=>1));
            // //Tipos telefono
            // $tiposTelefono = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->findAll();
            return $this->render('crmactividad/index_tasks.html.twig', array(
                // 'crmCuentas' => $crmCuentas,
                'personas'=>$personas,
                'estados'=>$estados,
                'recordatorios'=>$recordatorios,
                'tiempos'=>$tiempos,
                'prioridad'=>$prioridad,
                'actividades'=>$actividades,
                'menuTasksA'=>true,
                // 'personas'=>$personas,
                // 'industrias'=>$industrias,
                // 'tiposTelefono'=>$tiposTelefono,
                // 'menuProveedorA' => true,
            ));
        
        } catch (\Exception $e) {  
            if(method_exists($e,'getErrorCode')){ 
                    switch (intval($e->getErrorCode()))
                    {
                        case 2003: 
                            $serverOffline = $this->getParameter('app.serverOffline');
                            $data['error'] = $serverOffline.'. CODE: '.$e->getErrorCode();
                        break;
                        default :
                            $data['error'] = $serverOffline.'. CODE: '.$e->getErrorCode();
                            $row['data'][0]['name'] = $e->getMessage();                           
                        break;
                    }
            }                                    
            else{
                $data['error']=$e->getMessage();
            }
            $response->setData($data);
            return $response;
           
        }
    }

    /**
     * Creates a new CrmActividad entity.
     *
     * @Route("/new", name="admin_tasks_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $crmActividad = new CrmActividad();
        $form = $this->createForm('ERP\CRMBundle\Form\CrmActividadType', $crmActividad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crmActividad);
            $em->flush();

            return $this->redirectToRoute('admin_tasks_show', array('id' => $crmActividad->getId()));
        }

        return $this->render('crmactividad/new.html.twig', array(
            'crmActividad' => $crmActividad,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CrmActividad entity.
     *
     * @Route("/{id}", name="admin_tasks_show")
     * @Method("GET")
     */
    public function showAction(CrmActividad $crmActividad)
    {
        $deleteForm = $this->createDeleteForm($crmActividad);

        return $this->render('crmactividad/show.html.twig', array(
            'crmActividad' => $crmActividad,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CrmActividad entity.
     *
     * @Route("/{id}/edit", name="admin_tasks_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CrmActividad $crmActividad)
    {
        $deleteForm = $this->createDeleteForm($crmActividad);
        $editForm = $this->createForm('ERP\CRMBundle\Form\CrmActividadType', $crmActividad);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crmActividad);
            $em->flush();

            return $this->redirectToRoute('admin_tasks_edit', array('id' => $crmActividad->getId()));
        }

        return $this->render('crmactividad/edit.html.twig', array(
            'crmActividad' => $crmActividad,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CrmActividad entity.
     *
     * @Route("/{id}", name="admin_tasks_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CrmActividad $crmActividad)
    {
        $form = $this->createDeleteForm($crmActividad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($crmActividad);
            $em->flush();
        }

        return $this->redirectToRoute('admin_tasks_index');
    }

    /**
     * Creates a form to delete a CrmActividad entity.
     *
     * @param CrmActividad $crmActividad The CrmActividad entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CrmActividad $crmActividad)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_tasks_delete', array('id' => $crmActividad->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }





    /////Actividades

    /**
     * List level of activities
     *
     * @Route("/activities-tasks/data/list", name="admin_activities_data")
     */
    public function dataactivitiesAction(Request $request)
    {
            try {
                $start = $request->query->get('start');
                $draw = $request->query->get('draw');
                $longitud = $request->query->get('length');
                $busqueda = $request->query->get('search');
                
                $em = $this->getDoctrine()->getEntityManager();
                
                $sql = "SELECT obj.id as id FROM ERPCRMBundle:CrmActividad obj "
                            ."JOIN obj.tipoActividad tact WHERE tact.id=1";
                $rowsTotal = $em->createQuery($sql)
                            ->getResult();
               
                $row['draw']=$draw++;  
                $row['recordsTotal'] = count($rowsTotal);
                $row['recordsFiltered']= count($rowsTotal);
                $row['data']= array();

                $arrayFiltro = explode(' ',$busqueda['value']);
                
                $orderParam = $request->query->get('order');
                $orderBy = $orderParam[0]['column'];
                $orderDir = $orderParam[0]['dir'];
                $orderByText="";
                switch(intval($orderBy)){
                    case 1:
                        $orderByText = "name";
                        break;
                    
                    case 2:
                        $orderByText = "priority";
                        break;
                    case 3:
                        $orderByText = "responsable";
                        break;
                    case 4:
                        $orderByText = "dateStart";
                        break;
                    case 5:
                        $orderByText = "dateCancel";
                        break;
                    case 6:
                        $orderByText = "estado";
                        break;
                }
                // var_dump($orderByText);
                $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
                if($busqueda['value']!=''){
                            $sql = "SELECT CONCAT('<div id=\"',act.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',act.nombre,'</div>') as name, CONCAT('<div style=\"text-align:left\">',p.nombre,'</div>')  as priority, est.nombre as estado,
                                                        (SELECT CONCAT('<div style=\"text-align:left\">',per.nombre,'<br>',per.apellido,'</div>') 
                                                                    FROM crm_asignacion_actividad asig
                                                                    INNER JOIN ctl_usuario user on(asig.usuario_asignado=user.id)
                                                                    INNER JOIN ctl_persona per on(user.persona=per.id)
                                                        WHERE asig.actividad=act.id LIMIT 0,1 ) as responsable,CONCAT('<div style=\"text-align:left\">',DATE_FORMAT(act.fecha_inicio, '%Y-%m-%d'),'<br>',DATE_FORMAT(act.fecha_inicio, '%H:%i'),'</div>')  as 'dateStart', CONCAT('<div style=\"text-align:left\">',DATE_FORMAT(act.fecha_inicio, '%Y-%m-%d'),'<br>',DATE_FORMAT(act.fecha_fin, '%H:%i'),'</div>')  as 'dateEnd',CONCAT('<div style=\"text-align:left\">',DATE_FORMAT(act.fecha_inicio, '%Y-%m-%d'),'<br>',DATE_FORMAT(act.fecha_cancelacion, '%H:%i'),'</div>')  as 'dateCancel'
                                        FROM crm_actividad act
                                        INNER JOIN ctl_prioridad p on(act.prioridad = p.id)
                                        INNER JOIN crm_tipo_actividad tact on(act.tipo_actividad = tact.id)       
                                        INNER JOIN crm_estado_actividad est on(act.estado_actividad = est.id)    
                                        WHERE tact.id=1
                                        GROUP BY 1
                                        HAVING estado LIKE upper('%".$busqueda['value']."%') OR dateStart LIKE upper('%".$busqueda['value']."%') OR dateEnd LIKE upper('%".$busqueda['value']."%') OR priority LIKE upper('%".$busqueda['value']."%') OR name LIKE upper('%".$busqueda['value']."%') OR responsable LIKE upper('%".$busqueda['value']."%')
                                        ORDER BY ". $orderByText." ".$orderDir;
                            $stmt = $em->getConnection()->prepare($sql);
                            $stmt->execute();
                            $row['data'] = $stmt->fetchAll();
                            $row['recordsFiltered']= count($row['data']);
                            $sql = "SELECT CONCAT('<div id=\"',act.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',act.nombre,'</div>') as name, CONCAT('<div style=\"text-align:left\">',p.nombre,'</div>')  as priority, est.nombre as estado,
                                                        (SELECT CONCAT('<div style=\"text-align:left\">',per.nombre,'<br>',per.apellido,'</div>') 
                                                                    FROM crm_asignacion_actividad asig
                                                                    INNER JOIN ctl_usuario user on(asig.usuario_asignado=user.id)
                                                                    INNER JOIN ctl_persona per on(user.persona=per.id)
                                                        WHERE asig.actividad=act.id LIMIT 0,1 ) as responsable, CONCAT('<div style=\"text-align:left\">',DATE_FORMAT(act.fecha_inicio, '%Y-%m-%d'),'<br>',DATE_FORMAT(act.fecha_inicio, '%H:%i'),'</div>')  as 'dateStart', CONCAT('<div style=\"text-align:left\">',DATE_FORMAT(act.fecha_inicio, '%Y-%m-%d'),'<br>',DATE_FORMAT(act.fecha_fin, '%H:%i'),'</div>')  as 'dateEnd',CONCAT('<div style=\"text-align:left\">',DATE_FORMAT(act.fecha_inicio, '%Y-%m-%d'),'<br>',DATE_FORMAT(act.fecha_cancelacion, '%H:%i'),'</div>')  as 'dateCancel'
                                        FROM crm_actividad act
                                        INNER JOIN ctl_prioridad p on(act.prioridad = p.id)
                                        INNER JOIN crm_tipo_actividad tact on(act.tipo_actividad = tact.id)       
                                        INNER JOIN crm_estado_actividad est on(act.estado_actividad = est.id)
                                        WHERE tact.id=1
                                        GROUP BY 1
                                        HAVING estado LIKE upper('%".$busqueda['value']."%') OR dateStart LIKE upper('%".$busqueda['value']."%') OR dateEnd LIKE upper('%".$busqueda['value']."%') OR priority LIKE upper('%".$busqueda['value']."%') OR name LIKE upper('%".$busqueda['value']."%') OR responsable LIKE upper('%".$busqueda['value']."%')
                                        ORDER BY ". $orderByText." ".$orderDir." LIMIT " . $start . "," . $longitud;
                            $stmt = $em->getConnection()->prepare($sql);
                            $stmt->execute();
                            $row['data'] = $stmt->fetchAll();
                }
                else{
                    $sql = "SELECT CONCAT('<div id=\"',act.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',act.nombre,'</div>') as name, CONCAT('<div style=\"text-align:left\">',p.nombre,'</div>')  as priority, est.nombre as estado, 
                                                        (SELECT CONCAT('<div style=\"text-align:left\">',per.nombre,'<br>',per.apellido,'</div>') 
                                                                    FROM crm_asignacion_actividad asig 
                                                                    INNER JOIN ctl_usuario user on(asig.usuario_asignado=user.id) 
                                                                    INNER JOIN ctl_persona per on(user.persona=per.id) 
                                                        WHERE asig.actividad=act.id LIMIT 0,1 ) as responsable, CONCAT('<div style=\"text-align:left\">',DATE_FORMAT(act.fecha_inicio, '%Y-%m-%d'),'<br>',DATE_FORMAT(act.fecha_inicio, '%H:%i'),'</div>')  as 'dateStart', CONCAT('<div style=\"text-align:left\">',DATE_FORMAT(act.fecha_inicio, '%Y-%m-%d'),'<br>',DATE_FORMAT(act.fecha_fin, '%H:%i'),'</div>')  as 'dateEnd',CONCAT('<div style=\"text-align:left\">',DATE_FORMAT(act.fecha_inicio, '%Y-%m-%d'),'<br>',DATE_FORMAT(act.fecha_cancelacion, '%H:%i'),'</div>')  as 'dateCancel'
                                        FROM crm_actividad act
                                        INNER JOIN ctl_prioridad p on(act.prioridad = p.id)
                                        INNER JOIN crm_tipo_actividad tact on(act.tipo_actividad = tact.id)                                        
                                        INNER JOIN crm_estado_actividad est on(act.estado_actividad = est.id)   
                                        WHERE tact.id=1                                     
                                        GROUP BY 1
                                        ORDER BY ". $orderByText." ".$orderDir." LIMIT " . $start . "," . $longitud;
                            $stmt = $em->getConnection()->prepare($sql);
                            $stmt->execute();
                            $row['data'] = $stmt->fetchAll();
                            // $row['recordsFiltered']= count($row['data']);
                            // var_dump($row);
                }
                return new Response(json_encode($row));
            } catch (\Exception $e) {  
                // var_dump($e);
                if(method_exists($e,'getErrorCode')){ 
                    switch (intval($e->getErrorCode()))
                    {
                        case 2003: 
                            $serverOffline= $this->getParameter('app.serverOffline');
                            $row['data'][0]['name'] =$serverOffline.'. CODE: '.$e->getErrorCode();
                        break;
                        default :
                            $row['data'][0]['name'] = $e->getMessage();                           
                        break;
                    }               
                    $row['data'][0]['chk'] ='';
                    
                    $row['recordsFiltered']= 0;
                    }                                    
                    else{
                            $data['error']=$e->getMessage();
                    }
                return new Response(json_encode($row));            
        }
    
        
                
    }

    //////Fin de actividades




    /**
     * Save tasks
     *
     * @Route("/tasks/save", name="admin_tasks_save_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function saveajaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $em = $this->getDoctrine()->getEntityManager();
                $em->getConnection()->beginTransaction();
                $response = new JsonResponse();
                $imgData = $_FILES;
                //Captura de parametros

                // var_dump($_POST);


                //tasks
                $idTasks = $_POST['id'];//id crmActividad
                $nombreTasks= $_POST['nombre'];//id crmActividad
                $estado = $_POST['estado'];//Estado tasks
                $descripcionTasks = $_POST['descripcion'];//descripcion
                $tipoTasks =  $_POST['tipoActividades'];//id de las actividades para tareas/tasks
                
                $cuentaId =  $_POST['cuentaActividades'];//id de las cuentas

                $fechaInicio = $_POST['inicio'];//Inicio de actividad, fecha
                $fechaFin = $_POST['fin'];//Fin de actividad, fecha

                // var_dump($fechaInicio);
                // var_dump($fechaInicio);

                $dateInicio = new \DateTime($fechaInicio[0]);
                $dateFin = new \DateTime($fechaFin[0]);
                // var_dump($dateInicio);
                // var_dump($dateFin);
                // die();

                //Se buscan foraneas de otras tablas, a partir de estos valores
                $responsableArray = $_POST['responsable'];//responsable
                $tipoRecordatorioArray = $_POST['tipoRecordatorio'];//tipoRecordatorio
                $tiempoRecordatorioArray = $_POST['tiempoRecordatorio'];//tiempoRecordatorio
                $prioridad = $_POST['prioridad'];//prioridad tasks
                
                $fechaRegistro = new \DateTime('now');//descripcion

                //Busqueda objetos a partir de ids
                $estadoObj = $em->getRepository('ERPCRMBundle:CrmEstadoActividad')->find($estado);
                $prioridadObj = $em->getRepository('ERPCRMBundle:CtlPrioridad')->find($prioridad);
                

                $cuentaObj = $em->getRepository('ERPCRMBundle:CrmCuenta')->find($cuentaId);
                // var_dump($cuentaObj);
                // die();

                $tipoActividadObj = $em->getRepository('ERPCRMBundle:CrmTipoActividad')->find($tipoTasks);//Task o tareas
                
                // var_dump($tipoActividadObj);
                // die();

                if($idTasks==''){

                    //Tabla crmActividad
                    $crmActividadObj = new CrmActividad();
                    
                    $crmActividadObj->setTipoActividad($tipoActividadObj);
                    $crmActividadObj->setPrioridad($prioridadObj);
                    $crmActividadObj->setEstadoActividad($estadoObj);
                    $crmActividadObj->setSucursal(null);
                    $crmActividadObj->setNombre($nombreTasks);
                    $crmActividadObj->setDescripcion($descripcionTasks);
                    $crmActividadObj->setFechaRegistro($fechaRegistro);
                    $crmActividadObj->setFechaInicio($dateInicio);
                    $crmActividadObj->setFechaFin($dateFin);
                    $crmActividadObj->setFechaCancelacion(null);

                    $crmActividadObj->setCuenta($cuentaObj);
                    
                    $calendarId =$serverSave = $this->getParameter('app.googlecalendar');
                    $superGoogle= $this->get('calendar.google')->getFirstSyncToken($calendarId);
                    //$superGoogle= $this->get('calendar.google')->getEvents($calendarId,$superGoogle);

                    
                                                            
                    //Persist crmActividadObj
                    $em->persist($crmActividadObj);
                    $em->flush();

                    
                    $eventAttendee = array();
                    //Tabla crmAsignacionActividad
                    foreach ($responsableArray as $key => $per) {
                        //Tabla crmAsignacionActividad
                        $responsableUsuarioObj = $em->getRepository('ERPCRMBundle:CtlUsuario')->find($per);
                        
                        $idPersona = $responsableUsuarioObj->getPersona()->getId();
                        $correosPersona = $em->getRepository('ERPCRMBundle:CtlCorreo')->findBy(array('persona'=>$idPersona));
                        if(count($correosPersona)!=0){
                            array_push($eventAttendee, $correosPersona[0]->getEmail());
                        }
                        

                        $crmAsignacionActividad = new CrmAsignacionActividad();
                        $crmAsignacionActividad->setActividad($crmActividadObj);
                        $crmAsignacionActividad->setUsuarioAsignado($responsableUsuarioObj);

                        $tiempoNotificacionUsuarioObj = $em->getRepository('ERPCRMBundle:CtlTiempoNotificacion')->find($tiempoRecordatorioArray[$key]);

                        $crmAsignacionActividad->setTiempoNotificacion($tiempoNotificacionUsuarioObj);

                        $tipoRecordatorioObj = $em->getRepository('ERPCRMBundle:CtlTipoRecordatorio')->find($tipoRecordatorioArray[$key]);
                        $crmAsignacionActividad->setTipoRecordatorio($tipoRecordatorioObj);
                        
                        //Persist crmAsignacionActividad
                        $em->persist($crmAsignacionActividad);
                        $em->flush();
                    }

                    $eventStart = $dateInicio;
                    $eventEnd = $dateFin;
                    // var_dump($eventStart);
                    // var_dump($eventEnd);
                    $eventSummary = $nombreTasks;
                    $eventDescription = $descripcionTasks;
                    
                    $optionalParams = [];

                    $superGoogle= $this->get('calendar.google')->addEvent($calendarId,$eventStart,$eventEnd,$eventSummary,$eventDescription,$eventAttendee,$optionalParams = []);

                    $crmActividadObj->setGoogleId($superGoogle->getId());
                    $em->merge($crmAsignacionActividad);
                    $em->flush();
                    //$superGoogle= $this->get('calendar.google')->initEventsList($this->getCalendarId());


                    $serverSave = $this->getParameter('app.serverMsgSave');
                    $data['id']=$crmActividadObj->getId();
                    $data['msg']=$serverSave;
                }//Fin de if id, inserción
                //else para la modificación del objeto crmCuenta(proveedores) y sus tablas dependientes
                else{
                    // var_dump($phoneTypeArray);
                    // die();
                        $crmActividadObj = $em->getRepository('ERPCRMBundle:CrmActividad')->find($idTasks);
                        // $crmActividadObjDuplicate = $em->getRepository('ERPCRMBundle:CrmActividad')->findBy(array('nombre'=>$nombreTasks));
                        // var_dump($crmActividadObjDuplicate);
                        // die();
                        // if ($crmActividadObj[0]->getId()==$crmActividadObjDuplicate[0]->getId()) {
                        
                        
                            // $crmActividadObj->setTipoActividad($tipoActividadObj);
                            $crmActividadObj->setPrioridad($prioridadObj);
                            $crmActividadObj->setEstadoActividad($estadoObj);
                            $crmActividadObj->setNombre($nombreTasks);
                            $crmActividadObj->setDescripcion($descripcionTasks);
                            $crmActividadObj->setFechaInicio($dateInicio);
                            $crmActividadObj->setFechaFin($dateFin);
                            $crmActividadObj->setCuenta($cuentaObj);
                                            
                            //Persist crmCuentaObj
                            $em->merge($crmActividadObj);
                            $em->flush();
                            


                            //Eliminar personal asignado
                            $crmAsignacionArrayObj = $em->getRepository('ERPCRMBundle:CrmAsignacionActividad')->findBy(array('actividad'=>$idTasks));
                            foreach ($crmAsignacionArrayObj as $key => $value) {
                                $em->remove($value);
                                $em->flush();
                            }

                            $calendarId =$serverSave = $this->getParameter('app.googlecalendar');
                            $superGoogle= $this->get('calendar.google')->getFirstSyncToken($calendarId);
                            $eventId = $crmActividadObj->getGoogleId();
                            $eventAttendee = array();
                            
                            
                            //Recuperar información del evento de google calendar, la url es necesaria para poder consultar, lleva el id del calendario, id del evento y el token de acceso
            //                 $superGoogle= $this->get('calendar.google')->getToken();
            // $url = 'https://www.googleapis.com/calendar/v3/calendars/fbk7pcdkcncqo0f3ur264nimsk%40group.calendar.google.com/events/i3s7m1oim79tlacs38sodu5c6c/?access_token='.str_replace('"', '', $superGoogle);
            // var_dump($url);
            // $data = file_get_contents('https://www.googleapis.com/calendar/v3/calendars/fbk7pcdkcncqo0f3ur264nimsk%40group.calendar.google.com/events/i3s7m1oim79tlacs38sodu5c6c/?access_token='.str_replace('"', '', $superGoogle));
            // var_dump( json_decode($data));
            // die();


                            //Tabla crmAsignacionActividad
                            foreach ($responsableArray as $key => $per) {
                                //Tabla crmAsignacionActividad
                                $responsableUsuarioObj = $em->getRepository('ERPCRMBundle:CtlUsuario')->find($per);

                                $idPersona = $responsableUsuarioObj->getPersona()->getId();
                                $correosPersona = $em->getRepository('ERPCRMBundle:CtlCorreo')->findBy(array('persona'=>$idPersona));
                                if(count($correosPersona)!=0){
                                    array_push($eventAttendee, $correosPersona[0]->getEmail());
                                }
                                // var_dump($eventAttendee);
                                $crmAsignacionActividad = new CrmAsignacionActividad();
                                $crmAsignacionActividad->setActividad($crmActividadObj);
                                $crmAsignacionActividad->setUsuarioAsignado($responsableUsuarioObj);
                                $tiempoNotificacionUsuarioObj = $em->getRepository('ERPCRMBundle:CtlTiempoNotificacion')->find($tiempoRecordatorioArray[$key]);
                                $crmAsignacionActividad->setTiempoNotificacion($tiempoNotificacionUsuarioObj);
                                $tipoRecordatorioObj = $em->getRepository('ERPCRMBundle:CtlTipoRecordatorio')->find($tipoRecordatorioArray[$key]);
                                $crmAsignacionActividad->setTipoRecordatorio($tipoRecordatorioObj);
                                //Persist crmAsignacionActividad
                                $em->persist($crmAsignacionActividad);
                                $em->flush();
                            }

                            /////Manejo de excepciones para comprobar que el evento existe en google calendar
                            try {
                                //Eliminar evento en google calendar
                                $superGoogle= $this->get('calendar.google')->deleteEvent($calendarId,$eventId);    
                            } catch (\Exception $e) {
                                  
                            }
                            $eventStart = $dateInicio;
                            $eventEnd = $dateFin;
                            // var_dump($eventStart);
                            // var_dump($eventEnd);
                            $eventSummary = $nombreTasks;
                            $eventDescription = $descripcionTasks;
                            
                            $optionalParams = [];

                            $superGoogle= $this->get('calendar.google')->addEvent($calendarId,$eventStart,$eventEnd,$eventSummary,$eventDescription,$eventAttendee,$optionalParams = []);

                            $crmActividadObj->setGoogleId($superGoogle->getId());
                            $em->merge($crmAsignacionActividad);
                            $em->flush();
                            // $serverDuplicate = $this->getParameter('app.serverDuplicateName');
                            // $data['error'] = $serverDuplicate."! CODE: ".$e->getErrorCode();
                            $serverSave = $this->getParameter('app.serverMsgSave');
                            $data['msg']=$serverSave;
                            $data['id']=$idTasks;
                        // }
                        // else{
                        //     $serverSave = $this->getParameter('app.serverMsgUpdate');
                        //     $data['msg']=$serverSave;
                        //     $data['id']=$idTasks;
                        // }
                        
                }
                $em->getConnection()->commit();
                $em->close();
                $response->setData($data); 
            } catch (\Exception $e) {
                    $em->getConnection()->rollback();
                    $em->close();
                     // var_dump($e);
                    if(method_exists($e,'getErrorCode')){
                        switch (intval($e->getErrorCode())){
                            case 2003: 
                                $serverOffline = $this->getParameter('app.serverOffline');
                                $data['error'] = $serverOffline.'. CODE: '.$e->getErrorCode();
                            break;
                            case 1062: 
                                $serverDuplicate = $this->getParameter('app.serverDuplicateName');
                                $data['error'] = $serverDuplicate."! CODE: ".$e->getErrorCode();
                            break;
                            default :
                                $data['error'] = "Error CODE: ".$e->getMessage();
                            break;
                            }      
                    }
                    else{
                            $data['error']=$e->getMessage();
                    }
                    $response->setData($data);
            }
        
            
        } else {   
            $data['error']='Ajax request';
            $response->setData($data);
            
        }
        return $response;
        
    }






    /**
     * Retrieve tasks
     *
     * @Route("/tasks/retrieve", name="admin_task_retrieve_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function retrieveajaxAction(Request $request)
    {
        try {
            $idAct=$request->get("param1");
            
            $response = new JsonResponse();
            
            $em = $this->getDoctrine()->getManager();
            $crmActividadObj = $em->getRepository('ERPCRMBundle:CrmActividad')->find($idAct);
            // $calendarId =$serverSave = $this->getParameter('app.googlecalendar');
            // $superGoogle= $this->get('calendar.google')->getFirstSyncToken($calendarId);
            // var_dump($superGoogle);
            



            // $calendarId =$serverSave = $this->getParameter('app.googlecalendar');
            //                 $superGoogle= $this->get('calendar.google')->getFirstSyncToken($calendarId);
            //                 $eventId = $crmActividadObj->getGoogleId();
            //                 $eventAttendee = array();
                            
                            
            //         //Recuperar información del evento de google calendar, la url es necesaria para poder consultar, lleva el id del calendario, id del evento y el token de acceso
            // $superGoogle= $this->get('calendar.google')->getToken();
            // var_dump($superGoogle);
            // $url = 'https://www.googleapis.com/calendar/v3/calendars/fbk7pcdkcncqo0f3ur264nimsk%40group.calendar.google.com/events/i3s7m1oim79tlacs38sodu5c6c/?access_token='.str_replace('"', '', $superGoogle);
            // //var_dump($url);
            // $data = file_get_contents('https://www.googleapis.com/calendar/v3/calendars/fbk7pcdkcncqo0f3ur264nimsk%40group.calendar.google.com/events/i3s7m1oim79tlacs38sodu5c6c/?access_token='.str_replace('"', '', $superGoogle));
            // var_dump( json_decode($data));
            // die();



            // var_dump($ctlTelefonoObj);
            if(count($crmActividadObj)!=0){
                
                //$object->setProbabilidad($);
                //$em->merge($object);
                //$em->flush();    
                //var_dump($crmActividadObj);
                // die();
                $data['nombre']=$crmActividadObj->getNombre();
                $data['estado']=$crmActividadObj->getEstadoActividad()->getId();
                $data['descripcion']=$crmActividadObj->getDescripcion();

                if ($crmActividadObj->getCuenta()!=null) {
                    $data['cuentaNombre']=$crmActividadObj->getCuenta()->getNombre();
                    $data['cuentaId']=$crmActividadObj->getCuenta()->getId();    
                } else {
                    $data['cuentaNombre']=0;
                    $data['cuentaId']=0;
                }
                
                
                
                $fechaInicio=$crmActividadObj->getFechaInicio();
                $fechaFin=$crmActividadObj->getFechaFin();

                $data['fechaInicio']=$fechaInicio->format('Y/m/d H:i');
                $data['fechaFin']=$fechaFin->format('Y/m/d H:i');
                $data['prioridad']=$crmActividadObj->getPrioridad()->getId();
                // var_dump($data);
                // die();
                $crmAsignacionArrayObj = $em->getRepository('ERPCRMBundle:CrmAsignacionActividad')->findBy(array('actividad'=>$idAct));
                // var_dump($crmAsignacionArrayObj);
                if(count($crmAsignacionArrayObj)!=0){
                    $personaArray=array();
                    $tipoRecordatorioArray=array();
                    $tiempoRecordatorioArray=array();
                    foreach ($crmAsignacionArrayObj as $key => $value) {
                        array_push($personaArray, $value->getUsuarioAsignado()->getId());
                        array_push($tipoRecordatorioArray, $value->getTipoRecordatorio()->getId());
                        array_push($tiempoRecordatorioArray, $value->getTiempoNotificacion()->getId());
                    }
                    // $data['addressArray']=$ctlDireccionObj[0];
                    $data['personaArray']=$personaArray;
                    $data['tipoRecordatorioArray']=$tipoRecordatorioArray;
                    $data['tiempoRecordatorioArray']=$tiempoRecordatorioArray;
                }
                else{
                    $data['personaArray']=[];
                    $data['tipoRecordatorioArray']=[];
                    $data['tiempoRecordatorioArray']=[];
                }
                $data['id']=$idAct;
                $sql = "SELECT doc.id as id, doc.src as nombre, doc.estado FROM ERPCRMBundle:CrmDocumentoAdjuntoActividad doc"
                            ." JOIN doc.actividad c "
                            ." WHERE c.id=:idAct ORDER BY doc.fechaRegistro DESC";
                $docs = $em->createQuery($sql)
                                    ->setParameters(array('idAct'=>$idAct))
                                    ->getResult();
                
                $data['docs']=$docs;
            }
            else{
                $data['error']="Error";
            }
                        
            $response->setData($data); 
            
        } catch (\Exception $e) {
            var_dump($e);
            if(method_exists($e,'getErrorCode')){
                switch (intval($e->getErrorCode()))
                        {
                            case 2003: 
                                $serverOffline = $this->getParameter('app.serverOffline');
                                $data['error'] = $serverOffline.'. CODE: '.$e->getErrorCode();
                            break;
                            default :
                                $data['error'] = "Error CODE: ".$e->getMessage();                     
                            break;
                        }      
            }
            else{
                    $data['error']=$e->getMessage();
            }
            $response->setData($data);
        }
        
        return $response;
        
    }






    /**
     * Delete tasks
     *
     * @Route("/tasks/cancel", name="admin_task_cancel_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function cancelajaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $ids=$request->get("param1");
                $response = new JsonResponse();
                 // var_dump($ids);
                // die();
                $em = $this->getDoctrine()->getManager();
                $serverCancel = "¡";
                foreach ($ids as $key => $id) {

                    $object = $em->getRepository('ERPCRMBundle:CrmActividad')->find(intval($id));
                    // var_dump($id);
                    // die();
                     
                        if(count($object)!=0){
                            if ($object->getEstadoActividad()->getId()!=3) {//Estado cancelado, por defecto
                                $object->setFechaCancelacion(new  \DateTime('now'));
                                $estatus = $em->getRepository('ERPCRMBundle:CrmEstadoActividad')->find(3);
                                $object->setEstadoActividad($estatus);
                                $em->merge($object);
                                $em->flush();    

                                $calendarId =$serverSave = $this->getParameter('app.googlecalendar');
                                $eventId = $object->getGoogleId();
                                $superGoogle= $this->get('calendar.google')->getFirstSyncToken($calendarId);
                                try {
                                    $superGoogle= $this->get('calendar.google')->deleteEvent($calendarId,$eventId);    
                                } catch (\Exception $e) {/////Solo para que no estalle
                                    
                                }
                                    

                                $serverCancel = $this->getParameter('app.serverMsgCancel');
                                $data['msg']=$serverCancel;
                            } else {
                                $serverCancel .= $object->getNombre().", ";
                                $data['error'] = $serverCancel;
                                if($key==count($ids)-1)
                                    $data['error'] .= $this->getParameter('app.serverCancel');
                            }
                        }
                        else{
                            $data['error']="Error";
                        }
                }
                
                $response->setData($data); 
            } catch (\Exception $e) {
                //var_dump($e);
                if(method_exists($e,'getErrorCode')){
                    switch (intval($e->getErrorCode()))
                        {
                            case 2003: 
                                $serverOffline = $this->getParameter('app.serverOffline');
                                $data['error'] = $serverOffline.'. CODE: '.$e->getErrorCode();
                            break;
                            default :
                                $data['error'] = $e->getMessage();                     
                            break;
                        }      
                 }
                else{
                        $data['error']=$e->getMessage();
                }
                $response->setData($data);
            }
        } else {   
            $data['error']='Ajax request';
            $response->setData($data);
            
        }
        return $response;
        
    }







    /**
     * Check users conflicts 
     *
     * @Route("/check/availability/user/", name="admin_check_availability_user_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function checkusersajaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $idActividad=$request->get("param0");
                $ids=$request->get("param1");
                $fechaInicio=$request->get("param2");
                $fechaFin=$request->get("param3");
                // $idEvent=$request->get("param4");
                $response = new JsonResponse();
                // var_dump($idActividad);
                //$fechaInicio .=':00';
                // var_dump($fechaFin);
                // die();
                // var_dump($ids);
                // var_dump(new \DateTime($fechaInicio));
                // var_dump(new \DateTime($fechaFin));
                // die();
                $em = $this->getDoctrine()->getManager();
                $serverCancel = "¡";
                if(count($ids)==0){ /////La petición viene de una reprogramación de evento y es necesario obtener los ids de usuarios a partir del evento
                    $ids=[];
                    $sql = "SELECT  usr.id FROM ERPCRMBundle:CrmAsignacionActividad asigAct "
                                    ." JOIN asigAct.usuarioAsignado usr "
                                    ." WHERE asigAct.actividad=:id ";
                            $row = $em->createQuery($sql)
                                    ->setParameters(array('id'=>$idActividad))
                                    ->getResult();
                    // var_dump($row);
                    foreach ($row as $key => $value) {
                        array_push($ids, $value['id']);
                    }
                }
                
                    foreach ($ids as $key => $id) {
                        // echo "for";
                        // $object = $em->getRepository('ERPCRMBundle:CrmActividad')->findBy(array(''));
                        if($idActividad!=''){
                            // echo "d";
                            $sql = "SELECT act.nombre,act.fechaInicio,act.fechaFin, asigAct.id FROM ERPCRMBundle:CrmAsignacionActividad asigAct "
                                    ." JOIN asigAct.actividad act "
                                    ." WHERE act.id<>:id AND asigAct.usuarioAsignado =:user AND (act.fechaInicio>=:fechaInicio AND act.fechaFin<=:fechaFin)";
                            $row = $em->createQuery($sql)
                                    ->setParameters(array('id'=>$idActividad,'user'=>$id,'fechaInicio'=>$fechaInicio.':00','fechaFin'=>$fechaFin.':00'))
                                    ->getResult();
                        }
                        else{
                            // echo "cdsc";
                            // $sql = "SELECT act.nombre,act.fechaInicio,act.fechaFin, asigAct.id "
                            //         ."FROM ERPCRMBundle:CrmAsignacionActividad asigAct "
                            //         ." JOIN asigAct.actividad act "
                            //         ." JOIN asigAct.usuarioAsignado usr "
                            //         ." WHERE usr.id =:user AND (act.fechaInicio<=:fechaInicio AND act.fechaFin>:fechaInicio)";

                            $sql = "SELECT act.nombre,act.fechaInicio,act.fechaFin, asigAct.id "
                                    ."FROM ERPCRMBundle:CrmAsignacionActividad asigAct "
                                    ." JOIN asigAct.actividad act "
                                    ." JOIN asigAct.usuarioAsignado usr "
                                    ." WHERE usr.id =:user AND ((act.fechaInicio BETWEEN :fechaInicio AND :fechaFin) OR (act.fechaFin BETWEEN :fechaInicio AND :fechaFin) OR (:fechaInicio BETWEEN act.fechaInicio AND act.fechaFin))";
                                    // existing_start BETWEEN $newStart AND $newEnd OR 
    // existing_end BETWEEN $newStart AND $newEnd OR
    // $newStart BETWEEN existing_start AND existing_end
                                    // (ScopeStartDate <= EndDate AND ScopeEndDate >= StartDate)
                            $row = $em->createQuery($sql)
                                    ->setParameters(array('user'=>$id,'fechaInicio'=>$fechaInicio,'fechaFin'=>$fechaFin))
                                    ->getResult();  
                        }
                        // var_dump($em->createQuery($sql));
                        // var_dump($row);
                        // die();
                         
                            if(count($row)!=0){
                                $serverConflictRow = $this->getParameter('app.serverConflictRow');
                                $data['conflict']=$serverConflictRow;
                            }
                            else{
                                $data['msga']="";
                            }
                    }
                
                
                
                $response->setData($data); 
            } catch (\Exception $e) {
                var_dump($e);
                if(method_exists($e,'getErrorCode')){
                    switch (intval($e->getErrorCode()))
                        {
                            case 2003: 
                                $serverOffline = $this->getParameter('app.serverOffline');
                                $data['error'] = $serverOffline.'. CODE: '.$e->getErrorCode();
                            break;
                            default :
                                $data['error'] = $e->getMessage();                     
                            break;
                        }      
                 }
                else{
                        $data['error']=$e->getMessage();
                }
                $response->setData($data);
            }
        } else {   
            $data['error']='Ajax request';
            $response->setData($data);
            
        }
        return $response;
        
    }



/**
     * Reprogramar eventos, guardar cambios
     *
     * @Route("/task/rescheduled/tasks/", name="admin_tasks_rescheduled_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function rescheduledajaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $idActividad=$request->get("param1");
                $fechaInicio=$request->get("param2");
                $fechaFin=$request->get("param3");
                // var_dump($idActividad);
                // var_dump($fechaInicio);
                // var_dump($fechaFin);
                $response = new JsonResponse();
                

                $em = $this->getDoctrine()->getManager();
                $em->getConnection()->beginTransaction();
                $crmActividadObj = $em->getRepository('ERPCRMBundle:CrmActividad')->find($idActividad);
                
                $dateInicio = new \DateTime($fechaInicio);
                $dateFin = new \DateTime($fechaFin);

                $crmActividadObj->setFechaInicio($dateInicio);
                $crmActividadObj->setFechaFin($dateFin);

                $currentDate = date('Y-m-d H:i:s');
                
                if ($fechaInicio<=$currentDate) {
                    $serverDate = $this->getParameter('app.serverDateConflict');
                    $data['error']=$serverDate;
                    //$data['title']=$crmActividadObj->getFechaInicio()->format('m/d H:i')." - ".$crmActividadObj->getFechaFin()->format('m/d H:i')." \n".$crmActividadObj->getNombre();
                    $data['title']=$crmActividadObj->getFechaInicio()->format('n/j G:i')."\n".$crmActividadObj->getFechaFin()->format('n/j G:i')."\n".$crmActividadObj->getNombre();
                    $response->setData($data); 
                    return $response;
                }
                // var_dump($currentDate);
                // var_dump($result);
                // die();

                $calendarId =$serverSave = $this->getParameter('app.googlecalendar');

                $superGoogle= $this->get('calendar.google')->getFirstSyncToken($calendarId);
                /////Manejo de excepciones para comprobar que el evento existe en google calendar
                try {
                    //Eliminar evento en google calendar
                    $eventId = $crmActividadObj->getGoogleId();
                    $superGoogle= $this->get('calendar.google')->deleteEvent($calendarId,$eventId);    
                } catch (\Exception $e) {
                    
                }


                
                $ids=array();
                $eventAttendee = array();
                $sql = "SELECT  usr.id FROM ERPCRMBundle:CrmAsignacionActividad asigAct "
                                    ." JOIN asigAct.usuarioAsignado usr "
                                    ." WHERE asigAct.actividad=:id ";
                $row = $em->createQuery($sql)
                                    ->setParameters(array('id'=>$idActividad))
                                    ->getResult();
                foreach ($row as $key => $value) {
                    array_push($ids, $value['id']);
                }

                //Tabla crmAsignacionActividad
                foreach ($ids as $key => $per) {
                                //Tabla crmAsignacionActividad
                    $responsableUsuarioObj = $em->getRepository('ERPCRMBundle:CtlUsuario')->find($per);

                    $idPersona = $responsableUsuarioObj->getPersona()->getId();
                    $correosPersona = $em->getRepository('ERPCRMBundle:CtlCorreo')->findBy(array('persona'=>$idPersona));
                    if(count($correosPersona)!=0){
                        array_push($eventAttendee, $correosPersona[0]->getEmail());
                    }
                }

                $eventStart = $dateInicio;
                $eventEnd = $dateFin;
                // var_dump($eventStart);
                // var_dump($eventEnd);
                $eventSummary = $crmActividadObj->getNombre();
                $eventDescription = $crmActividadObj->getDescripcion();
                
                $optionalParams = [];

                $superGoogle= $this->get('calendar.google')->addEvent($calendarId,$eventStart,$eventEnd,$eventSummary,$eventDescription,$eventAttendee,$optionalParams = []);

                $crmActividadObj->setGoogleId($superGoogle->getId());
                //Persist crmCuentaObj
                $em->merge($crmActividadObj);
                $em->flush();
                $em->getConnection()->commit();
                $em->close();
                $serverSave = $this->getParameter('app.serverMsgSave');
                $data['id']=$crmActividadObj->getId();
                $data['msg']=$serverSave;
                //$data['title']=$crmActividadObj->getFechaInicio()->format('m/d H:i')." - ".$crmActividadObj->getFechaFin()->format('m/d H:i')." \n".$crmActividadObj->getNombre();
                $data['title']=$crmActividadObj->getFechaInicio()->format('n/j G:i')."\n".$crmActividadObj->getFechaFin()->format('n/j G:i')."\n".$crmActividadObj->getNombre();

                $response->setData($data); 
            } catch (\Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                // var_dump($e);
                if(method_exists($e,'getErrorCode')){
                    switch (intval($e->getErrorCode()))
                        {
                            case 2003: 
                                $serverOffline = $this->getParameter('app.serverOffline');
                                $data['error'] = $serverOffline.'. CODE: '.$e->getErrorCode();
                            break;
                            default :
                                $data['error'] = $e->getMessage();                     
                            break;
                        }      
                 }
                else{
                        $data['error']=$e->getMessage();
                }
                $response->setData($data);
            }
        } else {   
            $data['error']='Ajax request';
            $response->setData($data);
            
        }
        return $response;
        
    }



}
