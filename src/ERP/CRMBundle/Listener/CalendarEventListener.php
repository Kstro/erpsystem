<?php 

namespace ERP\CRMBundle\Listener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;

class CalendarEventListener
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function loadEvents(CalendarEvent $calendarEvent)
    {
        $em = $this->entityManager;
        $request = $calendarEvent->getRequest();
        $filterTipoActividad = $request->get('param1');
        $filterUsuario = $request->get('param2');
        $filterCuenta = $request->get('param3');
        $filter = $request->get('filter');
        $startString = $request->get('startDate');
        $endString = $request->get('endDate');

        // var_dump($tipoActividad);
        // var_dump($usuario);
        $startDate = new \DateTime($startString);
        $endDate = new \DateTime($endString);
        $stringFilters = "";

        if ($filterTipoActividad==0) {
            $stringFilters .= 0;
        }
        else{
            $stringFilters .= 1;  
        }

        if ($filterUsuario==0) {
            $stringFilters .= 0;
        }
        else{
            $stringFilters .= 1;  
        }

        if ($filterCuenta==0) {
            $stringFilters .= 0;
        }
        else{
            $stringFilters .= 1;  
        }
        

        // $stringFilters= "".$filterTipoActividad.$filterUsuario.$filterCuenta;

        /////Primera posicion filter tipo de actividad
        /////Segunda posicion filter usuario
        /////Tercera posicion filter cuenta
        /////000
        switch ($stringFilters) {
            case '000':
                    $events = $this->entityManager->getRepository('ERPCRMBundle:CrmActividad')
                          ->createQueryBuilder('events')
                          ->where('events.fechaInicio >= :startDate')
                          ->andwhere('events.fechaFin <= :endDate')
                          ->setParameter('startDate', $startString ." 00:00:00")
                          ->setParameter('endDate', $endString." 00:00:00")
                          ->getQuery()->getResult();
            break;

            case '001':
                    $events = $this->entityManager->getRepository('ERPCRMBundle:CrmActividad')
                          ->createQueryBuilder('events')
                          ->where('events.fechaInicio >= :startDate')
                          ->andwhere('events.fechaFin <= :endDate')
                          ->andwhere('events.cuenta = :cuenta')
                          ->setParameter('startDate', $startString ." 00:00:00")
                          ->setParameter('endDate', $endString." 00:00:00")
                          ->setParameter('cuenta', $filterCuenta." 00:00:00")
                          ->getQuery()->getResult();
            break;

            case '010':
                    $sql = "SELECT act.nombre, act.id,act.fechaInicio, act.fechaFin, asigAct.id, tip.id as tipId
                                        FROM ERPCRMBundle:CrmAsignacionActividad asigAct
                                        JOIN asigAct.actividad act
                                        JOIN asigAct.usuarioAsignado user
                                        JOIN act.tipoActividad tip
                                        WHERE user.id = :usuario";
                    $events = $em->createQuery($sql)
                                    ->setParameters(array('usuario'=>$filterUsuario))
                                    ->getResult();  
            break;

            case '011':
                    $sql = "SELECT act.nombre, act.id,act.fechaInicio, act.fechaFin, asigAct.id, tip.id as tipId
                                        FROM ERPCRMBundle:CrmAsignacionActividad asigAct
                                        JOIN asigAct.actividad act
                                        JOIN act.cuenta cue
                                        JOIN asigAct.usuarioAsignado user
                                        JOIN act.tipoActividad tip
                                        WHERE user.id = :usuario AND cue.id=:cuenta";
                    $events = $em->createQuery($sql)
                                    ->setParameters(array('usuario'=>$filterUsuario,'cuenta'=>$cuenta))
                                    ->getResult();
            break;
            case '100':
                    $events = $this->entityManager->getRepository('ERPCRMBundle:CrmActividad')
                                      ->createQueryBuilder('events')
                                      ->where('events.fechaInicio >= :startDate')
                                      ->andwhere('events.fechaFin <= :endDate')
                                      ->andwhere('events.tipoActividad = :tipoActividad')
                                      ->setParameter('startDate', $startString ." 00:00:00")
                                      ->setParameter('endDate', $endString." 00:00:00")
                                      ->setParameter('tipoActividad', $filterTipoActividad)
                                      ->getQuery()->getResult();

            break;
            case '101':
                    $events = $this->entityManager->getRepository('ERPCRMBundle:CrmActividad')
                                      ->createQueryBuilder('events')
                                      ->where('events.fechaInicio >= :startDate')
                                      ->andwhere('events.fechaFin <= :endDate')
                                      ->andwhere('events.tipoActividad = :tipoActividad')
                                      ->andwhere('events.cuenta = :cuenta')
                                      ->setParameter('startDate', $startString ." 00:00:00")
                                      ->setParameter('endDate', $endString." 00:00:00")
                                      ->setParameter('tipoActividad', $filterTipoActividad)
                                      ->setParameter('cuenta', $filterCuenta)
                                      ->getQuery()->getResult();
            break;

            case '110':
                    $sql = "SELECT act.nombre, act.id,act.fechaInicio, act.fechaFin, asigAct.id, tip.id as tipId
                                        FROM ERPCRMBundle:CrmAsignacionActividad asigAct
                                        JOIN asigAct.actividad act
                                        JOIN asigAct.usuarioAsignado user
                                        JOIN act.tipoActividad tip
                                        WHERE user.id = :usuario AND tip.id=:tipo";
                    $events = $em->createQuery($sql)
                                    ->setParameters(array('usuario'=>$filterUsuario,'tipo'=>$filterTipoActividad))
                                    ->getResult();
            break;

            case '111':
                    $sql = "SELECT act.nombre, act.id,act.fechaInicio, act.fechaFin, asigAct.id, tip.id as tipId
                                        FROM ERPCRMBundle:CrmAsignacionActividad asigAct
                                        JOIN asigAct.actividad act
                                        JOIN asigAct.cuenta cue
                                        JOIN asigAct.usuarioAsignado user
                                        JOIN act.tipoActividad tip
                                        WHERE user.id = :usuario AND tip.id=:tipo AND cue.id=:cuenta";
                    $events = $em->createQuery($sql)
                                    ->setParameters(array('usuario'=>$filterUsuario,'tipo'=>$filterTipoActividad,'cuenta'=>$filterCuenta))
                                    ->getResult();
            break;
            
            default:

                break;
        }








        // if ($filterTipoActividad ==0 && $filterUsuario ==0 ) {
            
        // } else {
        //     if ($filterTipoActividad==0 && $filterUsuario !=0) {
                    
        //     }
        //     else {
        //             if ($filterTipoActividad !=0 && $filterUsuario ==0) {
                        
        //             }
        //             else{
                        
        //             }
        //     }
        // }
        foreach($events as $event) {
            if (is_array($event)){
                $title = /*$event['fechaInicio']->format("n/j G:i")."\n".$event['fechaFin']->format("n/j G:i")."\n".*/$event['nombre'].' | '.$event->getFechaInicio()->format("G:i")." - ".$event->getFechaFin()->format("G:i");
                $eventEntity = new EventEntity($title, $event['fechaInicio'], $event['fechaFin'], false);
                $eventEntity->setId($event['id']);
                switch ($event['tipId']) {
                    case 1://///Tasks
                            // $eventEntity->setAllDay(true); // default is false, set to true if this is an all day event
                            $eventEntity->setBgColor('#DD4645'); //set the background color of the event's label
                            $eventEntity->setFgColor('#FFFFFF !important'); //set the foreground color of the event's label
                            //$eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
                            $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
                        break;
                    case 2:
                    default:
                            $eventEntity->setBgColor('#5CC8ED'); //set the background color of the event's label
                            $eventEntity->setFgColor('#FFFFFF !important'); //set the foreground color of the event's label
                            // $eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
                            $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
                        break;
                }
            } else {
                $title = /*$event->getFechaInicio()->format("n/j G:i")."\n".$event->getFechaFin()->format("n/j G:i")."\n".*/$event->getNombre().' | '.$event->getFechaInicio()->format("G:i")." - ".$event->getFechaFin()->format("G:i");
                $eventEntity = new EventEntity($title, $event->getFechaInicio(), $event->getFechaFin(), false);
                $eventEntity->setId($event->getId());
                switch ($event->getTipoActividad()->getId()) {
                    case 1://///Tasks
                        # code...
                            // $eventEntity->setAllDay(true); // default is false, set to true if this is an all day event
                            $eventEntity->setBgColor('#AB2925'); //set the background color of the event's label
                            $eventEntity->setFgColor('#FFFFFF !important'); //set the foreground color of the event's label
                            //$eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
                            $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
                        break;
                    case 2://///Calls
                        # code...
                            //$eventEntity->setBgColor('#5CC8ED'); //set the background color of the event's label
                            $eventEntity->setBgColor('#2699BB'); //set the background color of the event's label
                            $eventEntity->setFgColor('#FFFFFF !important'); //set the foreground color of the event's label
                            // $eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
                            $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
                        break;
                    case 3://///Meetings
                        # code...
                            $eventEntity->setBgColor('#398339'); //set the background color of the event's label
                            $eventEntity->setFgColor('#FFFFFF !important'); //set the foreground color of the event's label
                            // $eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
                            $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
                        break;
                    case 4://///Others
                        # code...
                            $eventEntity->setBgColor('#D58512'); //set the background color of the event's label
                            $eventEntity->setFgColor('#FFFFFF !important'); //set the foreground color of the event's label
                            // $eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
                            $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
                        break;
                    default:
                        # code...
                            $eventEntity->setBgColor('#673293'); //set the background color of the event's label
                            $eventEntity->setFgColor('#FFFFFF !important'); //set the foreground color of the event's label
                            // $eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
                            $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
                        break;
                }
            }
            // }
            //optional calendar event settings
            // //$eventEntity->setAllDay(true); // default is false, set to true if this is an all day event
            // $eventEntity->setBgColor('#DD4645'); //set the background color of the event's label
            // $eventEntity->setFgColor('#FFFFFF'); //set the foreground color of the event's label
            // $eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
            // $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
            //finally, add the event to the CalendarEvent for displaying on the calendar
            $calendarEvent->addEvent($eventEntity);
        }
    }
}

 ?>