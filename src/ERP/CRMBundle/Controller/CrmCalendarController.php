<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CrmActividad;
use ERP\CRMBundle\Entity\CrmAsignacionActividad;
// use ERP\CRMBundle\Entity\GoogleCalendarService;
use ERP\CRMBundle\Form\CrmActividadType;
use Djamy\GoogleCalendarBundle\Service\GoogleCalendarService;

/**
 * CrmActividad controller.
 *
 * @Route("/admin/calendar/event")
 */
class CrmCalendarController extends Controller
{


    protected $calendarId = 'fbk7pcdkcncqo0f3ur264nimsk@group.calendar.google.com';


    public function getCalendarId(){
        return $this->calendarId;
    }






    /**
     * Lists all CrmActividad entities.
     *
     * @Route("/", name="admin_calendar_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        // $em = $this->getDoctrine()->getManager();

        // $crmActividads = $em->getRepository('ERPCRMBundle:CrmActividad')->findAll();

        // return $this->render('crmactividad/index.html.twig', array(
        //     'crmActividads' => $crmActividads,
        // ));
        try{
            // $em = $this->getDoctrine()->getManager();
            $response = new JsonResponse();
            
            //$calendarId = 'digitalitygarage.com_lsqj0gmej50b4p5m12b88e06kc@group.calendar.google.com';
            
            // $calendarId = '808817475899-88lt3318sti1m8114v8f1nu284chugc4.apps.googleusercontent.com';
            // $calendarId = '906188066175-7qasgqoi3d5dvlto8v36kckkc7bkovhg.apps.googleusercontent.com';
            // $syncToken = '5QyRNRL3wnswRP5ji1yHU7Pi';
            // $syncToken = 'AIzaSyCwjnvWuVj28EtPJ1GK01leVjNkOLRpjEM';
            //$superGoogle= $this->get('calendar.google');
            // $superGoogle= $this->get('calendar.google')->initEventsList($calendarId);
            // Djamy\Service\GoogleCalendarService\Service;
            // $goo = new GoogleCalendarService(); 
            // var_dump($goo);
            
            //$superGoogle= $this->get('calendar.google')->getFirstSyncToken($this->getCalendarId());
            //$superGoogle= $this->get('calendar.google')->getEvents($calendarId,$superGoogle);
            //$superGoogle= $this->get('calendar.google')->initEventsList($this->getCalendarId());
            
            // $response=file_get_contents("https://accounts.google.com/o/oauth2/token");

            // $superGoogle= $this->get('calendar.google')->getClient();
            $eventStart = new \DateTime('2016-09-20 12:00');
            $eventEnd = new \DateTime('2016-09-20 13:00');
            // var_dump($eventStart);
            // var_dump($eventEnd);
            $eventSummary = "Evento de prueba";
            $eventDescription = "Evento creado desde app";
            $eventAttendee = "mkstro.3@gmail.com";
            $optionalParams = [];
            // $superGoogle= $this->get('calendar.google')->addEvent($calendarId,$eventStart,$eventEnd,$eventSummary,$eventDescription,$eventAttendee,$optionalParams = []);
            // $superGoogle= $this->get('calendar.google')->getCalendarService();
            // $superGoogle= $this->get('calendar.google')->listCalendars();
            // var_dump($response);
            // var_dump($superGoogle[0]->getId());
            // var_dump($superGoogle[2]->getAttendees());
            
            // // $crmCuentas = $em->getRepository('ERPCRMBundle:CrmCuenta')->findAll();
            
            $em = $this->getDoctrine()->getManager();

            // $crmCuentas = $em->getRepository('ERPCRMBundle:CrmCuenta')->findAll();
            $response = new JsonResponse();

            //Persona-usuarios
            $personas = $em->getRepository('ERPCRMBundle:CtlUsuario')->findAll();
            //Estado
            $estados = $em->getRepository('ERPCRMBundle:CrmEstadoActividad')->findAll();
            //Tipo recordatorio
            $recordatorios = $em->getRepository('ERPCRMBundle:CtlTipoRecordatorio')->findAll();
            //Tipo recordatorio
            $tiempos = $em->getRepository('ERPCRMBundle:CtlTiempoNotificacion')->findAll();
            //Prioridad
            $prioridad = $em->getRepository('ERPCRMBundle:CtlPrioridad')->findAll();
            //Actividades
            $actividades = $em->getRepository('ERPCRMBundle:CrmTipoActividad')->findAll();
            //Estados actividades
            $estActividades = $em->getRepository('ERPCRMBundle:CrmEstadoActividad')->findBy(array('estado'=>1));
            // //$personas = $em->getRepository('ERPCRMBundle:CtlTipoEntidad')->findBy(array('estado'=>1));
            // $personas = $em->getRepository('ERPCRMBundle:CtlTipoEntidad')->findAll();
            // //Tipo industria
            // $industrias = $em->getRepository('ERPCRMBundle:CtlIndustria')->findAll(array('estado'=>1));
            // //Tipos telefono
            // $tiposTelefono = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->findAll();
            
          
            return $this->render('crmcalendar/index.html.twig', array(
                // 'crmCuentas' => $crmCuentas,
                'personas'=>$personas,
                'estados'=>$estados,
                'recordatorios'=>$recordatorios,
                'tiempos'=>$tiempos,
                'prioridad'=>$prioridad,
                'actividades'=>$actividades,
                'estActividades'=>$estActividades,
                // 'personas'=>$personas,
                // 'industrias'=>$industrias,
                // 'tiposTelefono'=>$tiposTelefono,
                // 'menuProveedorA' => true,
            ));
        
        } catch (\Exception $e) {  
            // var_dump($e);
            if(method_exists($e,'getErrorCode')){ 
                
                    $serverOffline='';
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
     * Get event details
     *
     * @Route("/event/get", name="admin_calendar_get_event_index", options={"expose"=true}))
     * @Method("POST")
     */
    public function geteventAction(Request $request)
    {
        try{
            $idEventGoogle = $request->get("param1");
            $start = $request->get("param2");
            $end = $request->get("param3");
            $url = $request->get("param4");
            $response = new JsonResponse();            
            $superGoogle= $this->get('calendar.google')->getFirstSyncToken($this->getCalendarId());
            $superGoogle= $this->get('calendar.google')->getEvents($this->getCalendarId(),$superGoogle);
            // $superGoogle= $this->get('calendar.google')->getEventsOnRange($this->getCalendarId(),new \DateTime($start),new \DateTime($end));
            // $superGoogle= $this->get('calendar.google')->watch($this->getCalendarId(),'https://www.googleapis.com/calendar/v3/calendars/fbk7pcdkcncqo0f3ur264nimsk%40group.calendar.google.com/events/sfbi40nquvoqsnr61mfqib1jec?key=AIzaSyBcQtDPhk5fjQmvkkuKBjMbwjhzVOdyeSY');
            
            // $xml = file_get_contents("https://www.googleapis.com/calendar/v3/calendars/fbk7pcdkcncqo0f3ur264nimsk%40group.calendar.google.com/events/sfbi40nquvoqsnr61mfqib1jec?key=AIzaSyBcQtDPhk5fjQmvkkuKBjMbwjhzVOdyeSY");
            // var_dump($xml);
            //var_dump($superGoogle);
            $data['error'] = "";  
            $response->setData($xml);
            return $response;
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






}
