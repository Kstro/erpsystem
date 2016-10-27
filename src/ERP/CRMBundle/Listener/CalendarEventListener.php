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
        $showCancel = $request->get('param4');
        $estadoAct= $request->get('param5');
        $filter = $request->get('filter');
        $startString = $request->get('startDate');
        $endString = $request->get('endDate');
        
        // var_dump($tipoActividad);
        // var_dump($usuario);
        $startDate = new \DateTime($startString);
        $endDate = new \DateTime($endString);
        $stringFilters = "";

        if ($estadoAct==0) {
            $stringFilters .= 0;
        }
        else{
            $stringFilters .= 1;  
        }
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
        /////De izquierda a derecha
        /////Primera posicion filter tipo de actividad
        /////Segunda posicion filter usuario
        /////Tercera posicion filter cuenta
        /////0000
        //echo $stringFilters;
        switch ($stringFilters) {
            case '0000':
                if($showCancel==1){
                    //echo "cancel 000";
                    $events = $this->entityManager->getRepository('ERPCRMBundle:CrmActividad')
                            ->createQueryBuilder('events')
                            ->where('events.fechaInicio >= :startDate')
                            ->andwhere('events.fechaFin <= :endDate')
                            ->setParameter('startDate', $startString ." 00:00:00")
                            ->setParameter('endDate', $endString." 00:00:00")
                            ->getQuery()->getResult();
                }
                else{
                    //echo "no cancel 000";
                    $events = $this->entityManager->getRepository('ERPCRMBundle:CrmActividad')
                            ->createQueryBuilder('events')
                            ->join('events.estadoActividad','estAct')
                            ->where('events.fechaInicio >= :startDate')
                            ->andwhere('events.fechaFin <= :endDate')
                            ->andwhere('estAct.id <> :eId')
                            ->setParameter('startDate', $startString ." 00:00:00")
                            ->setParameter('endDate', $endString." 00:00:00")
                            ->setParameter('eId', 3)//3 es cancelado
                            ->getQuery()->getResult();
                }
            break;

            case '0001':
                if($showCancel==1){
                    //echo "cancel 001";
                    $events = $this->entityManager->getRepository('ERPCRMBundle:CrmActividad')
                            ->createQueryBuilder('events')
                            ->where('events.fechaInicio >= :startDate')
                            ->andwhere('events.fechaFin <= :endDate')
                            ->andwhere('events.cuenta = :cuenta')
                            ->setParameter('startDate', $startString ." 00:00:00")
                            ->setParameter('endDate', $endString." 00:00:00")
                            ->setParameter('cuenta', $filterCuenta." 00:00:00")
                            ->getQuery()->getResult();
                }
                else{
                    //echo "no cancel 001";
                    $events = $this->entityManager->getRepository('ERPCRMBundle:CrmActividad')
                            ->createQueryBuilder('events')
                            ->join('events.estadoActividad','estAct')
                            ->where('events.fechaInicio >= :startDate')
                            ->andwhere('events.fechaFin <= :endDate')
                            ->andwhere('events.cuenta = :cuenta')
                            ->andwhere('estAct.id <> :eId')
                            ->setParameter('startDate', $startString ." 00:00:00")
                            ->setParameter('endDate', $endString." 00:00:00")
                            ->setParameter('cuenta', $filterCuenta." 00:00:00")
                            ->setParameter('eId', 3)//3 es cancelado
                            ->getQuery()->getResult();
                }
            break;

            case '0010':
                
                if($showCancel==1){
                    //echo "no cancel 010";
                    $sql = "SELECT act.nombre, act.id,act.fechaInicio, act.fechaFin, asigAct.id, tip.id as tipId, estAct.id estado
                                        FROM ERPCRMBundle:CrmAsignacionActividad asigAct
                                        JOIN asigAct.actividad act
                                        JOIN asigAct.usuarioAsignado user
                                        JOIN act.tipoActividad tip
                                        JOIN act.estadoActividad estAct
                                        WHERE user.id = :usuario";
                    $events = $em->createQuery($sql)
                                    ->setParameters(array('usuario'=>$filterUsuario))
                                    ->getResult();
                }
                else{
                    //echo "cancel 010";
                    $sql = "SELECT act.nombre, act.id,act.fechaInicio, act.fechaFin, asigAct.id, tip.id as tipId, estAct.id estado
                                        FROM ERPCRMBundle:CrmAsignacionActividad asigAct
                                        JOIN asigAct.actividad act
                                        JOIN asigAct.usuarioAsignado user
                                        JOIN act.tipoActividad tip
                                        JOIN act.estadoActividad estAct
                                        WHERE user.id = :usuario AND estAct.id <> :eId";
                    $events = $em->createQuery($sql)
                                    ->setParameters(array('usuario'=>$filterUsuario,'eId'=>3))
                                    ->getResult();
                }
            break;

            case '0011':
                if($showCancel==1){
                    //echo "no cancel 011";
                    $sql = "SELECT act.nombre, act.id,act.fechaInicio, act.fechaFin, asigAct.id, tip.id as tipId, estAct.id estado
                                        FROM ERPCRMBundle:CrmAsignacionActividad asigAct
                                        JOIN asigAct.actividad act
                                        JOIN act.cuenta cue
                                        JOIN asigAct.usuarioAsignado user
                                        JOIN act.tipoActividad tip
                                        JOIN act.estadoActividad estAct
                                        WHERE user.id = :usuario AND cue.id=:cuenta";
                    $events = $em->createQuery($sql)
                                    ->setParameters(array('usuario'=>$filterUsuario,'cuenta'=>$cuenta))
                                    ->getResult();
                }
                else{
                    //echo "cancel 011";
                    $sql = "SELECT act.nombre, act.id,act.fechaInicio, act.fechaFin, asigAct.id, tip.id as tipId, estAct.id estado
                                        FROM ERPCRMBundle:CrmAsignacionActividad asigAct
                                        JOIN asigAct.actividad act
                                        JOIN act.cuenta cue
                                        JOIN asigAct.usuarioAsignado user
                                        JOIN act.tipoActividad tip
                                        JOIN act.estadoActividad estAct
                                        WHERE user.id = :usuario AND cue.id=:cuenta AND estAct.id <>:eId ";
                    $events = $em->createQuery($sql)
                                    ->setParameters(array('usuario'=>$filterUsuario,'cuenta'=>$cuenta,'eId'=>3))
                                    ->getResult();
                }
            break;
            case '0100':
                if($showCancel==1){
                    $events = $this->entityManager->getRepository('ERPCRMBundle:CrmActividad')
                                        ->createQueryBuilder('events')
                                        ->where('events.fechaInicio >= :startDate')
                                        ->andwhere('events.fechaFin <= :endDate')
                                        ->andwhere('events.tipoActividad = :tipoActividad')
                                        ->andwhere('estAct.id <> :eId')
                                        ->setParameter('startDate', $startString ." 00:00:00")
                                        ->setParameter('endDate', $endString." 00:00:00")
                                        ->setParameter('tipoActividad', $filterTipoActividad)
                                        ->getQuery()->getResult();
                }
                else{
                    $events = $this->entityManager->getRepository('ERPCRMBundle:CrmActividad')
                                        ->createQueryBuilder('events')
                                        ->join('events.estadoActividad','estAct')
                                        ->where('events.fechaInicio >= :startDate')
                                        ->andwhere('events.fechaFin <= :endDate')
                                        ->andwhere('events.tipoActividad = :tipoActividad')
                                        ->setParameter('startDate', $startString ." 00:00:00")
                                        ->setParameter('endDate', $endString." 00:00:00")
                                        ->setParameter('tipoActividad', $filterTipoActividad)
                                        ->getQuery()->getResult();
                    
                }

            break;
            case '0101':
                if($showCancel==1){
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
                }
                else{
                    $events = $this->entityManager->getRepository('ERPCRMBundle:CrmActividad')
                                        ->createQueryBuilder('events')
                                        ->join('events.estadoActividad','estAct')
                                        ->where('events.fechaInicio >= :startDate')
                                        ->andwhere('events.fechaFin <= :endDate')
                                        ->andwhere('events.tipoActividad = :tipoActividad')
                                        ->andwhere('events.cuenta = :cuenta')
                                        ->andwhere('estAct.id <> :eId')
                                        ->setParameter('startDate', $startString ." 00:00:00")
                                        ->setParameter('endDate', $endString." 00:00:00")
                                        ->setParameter('tipoActividad', $filterTipoActividad)
                                        ->setParameter('cuenta', $filterCuenta)
                                        ->setParameter('eId', 3)//3 es cancelado
                                        ->getQuery()->getResult();
                }
            break;

            case '0110':
                if($showCancel==1){
                    $sql = "SELECT act.nombre, act.id,act.fechaInicio, act.fechaFin, asigAct.id, tip.id as tipId, estAct.id estado
                                        FROM ERPCRMBundle:CrmAsignacionActividad asigAct
                                        JOIN asigAct.actividad act
                                        JOIN asigAct.usuarioAsignado user
                                        JOIN act.tipoActividad tip
                                        WHERE user.id = :usuario AND tip.id=:tipo";
                    $events = $em->createQuery($sql)
                                    ->setParameters(array('usuario'=>$filterUsuario,'tipo'=>$filterTipoActividad))
                                    ->getResult();
                }
                else{
                    $sql = "SELECT act.nombre, act.id,act.fechaInicio, act.fechaFin, asigAct.id, tip.id as tipId, estAct.id estado
                                        FROM ERPCRMBundle:CrmAsignacionActividad asigAct
                                        JOIN asigAct.actividad act
                                        JOIN asigAct.usuarioAsignado user
                                        JOIN act.tipoActividad tip
                                        JOIN act.estadoActividad estAct
                                        WHERE user.id = :usuario AND tip.id=:tipo AND estAct.id <>:eId ";
                    $events = $em->createQuery($sql)
                                    ->setParameters(array('usuario'=>$filterUsuario,'tipo'=>$filterTipoActividad,'eId'=>3))
                                    ->getResult();
                }
            break;

            case '0111':
                if($showCancel==1){
                    $sql = "SELECT act.nombre, act.id,act.fechaInicio, act.fechaFin, asigAct.id, tip.id as tipId, estAct.id estado
                                        FROM ERPCRMBundle:CrmAsignacionActividad asigAct
                                        JOIN asigAct.actividad act
                                        JOIN asigAct.cuenta cue
                                        JOIN asigAct.usuarioAsignado user
                                        JOIN act.tipoActividad tip
                                        JOIN act.estadoActividad estAct
                                        WHERE user.id = :usuario AND tip.id=:tipo AND cue.id=:cuenta";
                    $events = $em->createQuery($sql)
                                    ->setParameters(array('usuario'=>$filterUsuario,'tipo'=>$filterTipoActividad,'cuenta'=>$filterCuenta))
                                    ->getResult();
                }
                else{
                    $sql = "SELECT act.nombre, act.id,act.fechaInicio, act.fechaFin, asigAct.id, tip.id as tipId, estAct.id estado
                                        FROM ERPCRMBundle:CrmAsignacionActividad asigAct
                                        JOIN asigAct.actividad act
                                        JOIN asigAct.cuenta cue
                                        JOIN asigAct.usuarioAsignado user
                                        JOIN act.tipoActividad tip
                                        JOIN act.estadoActividad estAct
                                        WHERE user.id = :usuario AND tip.id=:tipo AND cue.id=:cuenta AND estAct.id <>:eId";
                    $events = $em->createQuery($sql)
                                    ->setParameters(array('usuario'=>$filterUsuario,'tipo'=>$filterTipoActividad,'cuenta'=>$filterCuenta,'eId'=>3))
                                    ->getResult();
                }
            break;
            
            
            
            case '1000':
                if($showCancel==1){
                    //echo "cancel 000";
                    $events = $this->entityManager->getRepository('ERPCRMBundle:CrmActividad')
                            ->createQueryBuilder('events')
                            ->join('events.estadoActividad','estAct')
                            ->where('events.fechaInicio >= :startDate')
                            ->andwhere('events.fechaFin <= :endDate')
                            ->andwhere('estAct.id = :eId')
                            ->orwhere('estAct.id = :eIdC')
                            ->setParameter('startDate', $startString ." 00:00:00")
                            ->setParameter('endDate', $endString." 00:00:00")
                            ->setParameter('eIdC', 3)
                            ->setParameter('eId', $estadoAct)
                            ->getQuery()->getResult();
                }
                else{
                    //echo "no cancel 000";
                    $events = $this->entityManager->getRepository('ERPCRMBundle:CrmActividad')
                            ->createQueryBuilder('events')
                            ->join('events.estadoActividad','estAct')
                            ->where('events.fechaInicio >= :startDate')
                            ->andwhere('events.fechaFin <= :endDate')
                            ->andwhere('estAct.id =:eId')
                            ->setParameter('startDate', $startString ." 00:00:00")
                            ->setParameter('endDate', $endString." 00:00:00")
                            ->setParameter('eId', $estadoAct)//3 es cancelado
                            ->getQuery()->getResult();
                }
            break;

            case '1001':
                if($showCancel==1){
                    //echo "cancel 001";
                    $events = $this->entityManager->getRepository('ERPCRMBundle:CrmActividad')
                            ->createQueryBuilder('events')
                            ->where('events.fechaInicio >= :startDate')
                            ->andwhere('events.fechaFin <= :endDate')
                            ->andwhere('events.cuenta = :cuenta')
                            ->setParameter('startDate', $startString ." 00:00:00")
                            ->setParameter('endDate', $endString." 00:00:00")
                            ->setParameter('cuenta', $filterCuenta." 00:00:00")
                            ->getQuery()->getResult();
                }
                else{
                    //echo "no cancel 001";
                    $events = $this->entityManager->getRepository('ERPCRMBundle:CrmActividad')
                            ->createQueryBuilder('events')
                            ->join('events.estadoActividad','estAct')
                            ->where('events.fechaInicio >= :startDate')
                            ->andwhere('events.fechaFin <= :endDate')
                            ->andwhere('events.cuenta = :cuenta')
                            ->andwhere('estAct.id <> :eId')
                            ->setParameter('startDate', $startString ." 00:00:00")
                            ->setParameter('endDate', $endString." 00:00:00")
                            ->setParameter('cuenta', $filterCuenta." 00:00:00")
                            ->setParameter('eId', 3)//3 es cancelado
                            ->getQuery()->getResult();
                }
            break;

            case '1010':
                
                if($showCancel==1){
                    //echo "no cancel 010";
                    $sql = "SELECT act.nombre, act.id,act.fechaInicio, act.fechaFin, asigAct.id, tip.id as tipId, estAct.id estado
                                        FROM ERPCRMBundle:CrmAsignacionActividad asigAct
                                        JOIN asigAct.actividad act
                                        JOIN asigAct.usuarioAsignado user
                                        JOIN act.tipoActividad tip
                                        JOIN act.estadoActividad estAct
                                        WHERE user.id = :usuario";
                    $events = $em->createQuery($sql)
                                    ->setParameters(array('usuario'=>$filterUsuario))
                                    ->getResult();
                }
                else{
                    //echo "cancel 010";
                    $sql = "SELECT act.nombre, act.id,act.fechaInicio, act.fechaFin, asigAct.id, tip.id as tipId, estAct.id estado
                                        FROM ERPCRMBundle:CrmAsignacionActividad asigAct
                                        JOIN asigAct.actividad act
                                        JOIN asigAct.usuarioAsignado user
                                        JOIN act.tipoActividad tip
                                        JOIN act.estadoActividad estAct
                                        WHERE user.id = :usuario AND estAct.id <> :eId";
                    $events = $em->createQuery($sql)
                                    ->setParameters(array('usuario'=>$filterUsuario,'eId'=>3))
                                    ->getResult();
                }
            break;

            case '1011':
                if($showCancel==1){
                    //echo "no cancel 011";
                    $sql = "SELECT act.nombre, act.id,act.fechaInicio, act.fechaFin, asigAct.id, tip.id as tipId, estAct.id estado
                                        FROM ERPCRMBundle:CrmAsignacionActividad asigAct
                                        JOIN asigAct.actividad act
                                        JOIN act.cuenta cue
                                        JOIN asigAct.usuarioAsignado user
                                        JOIN act.tipoActividad tip
                                        JOIN act.estadoActividad estAct
                                        WHERE user.id = :usuario AND cue.id=:cuenta";
                    $events = $em->createQuery($sql)
                                    ->setParameters(array('usuario'=>$filterUsuario,'cuenta'=>$cuenta))
                                    ->getResult();
                }
                else{
                    //echo "cancel 011";
                    $sql = "SELECT act.nombre, act.id,act.fechaInicio, act.fechaFin, asigAct.id, tip.id as tipId, estAct.id estado
                                        FROM ERPCRMBundle:CrmAsignacionActividad asigAct
                                        JOIN asigAct.actividad act
                                        JOIN act.cuenta cue
                                        JOIN asigAct.usuarioAsignado user
                                        JOIN act.tipoActividad tip
                                        JOIN act.estadoActividad estAct
                                        WHERE user.id = :usuario AND cue.id=:cuenta AND estAct.id <>:eId ";
                    $events = $em->createQuery($sql)
                                    ->setParameters(array('usuario'=>$filterUsuario,'cuenta'=>$cuenta,'eId'=>3))
                                    ->getResult();
                }
            break;
            case '1100':
                if($showCancel==1){
                    $events = $this->entityManager->getRepository('ERPCRMBundle:CrmActividad')
                                        ->createQueryBuilder('events')
                                        ->where('events.fechaInicio >= :startDate')
                                        ->andwhere('events.fechaFin <= :endDate')
                                        ->andwhere('events.tipoActividad = :tipoActividad')
                                        ->andwhere('estAct.id = :eId')
                                        ->orwhere('estAct.id = :eIdC')
                                        ->setParameter('startDate', $startString ." 00:00:00")
                                        ->setParameter('endDate', $endString." 00:00:00")
                                        ->setParameter('tipoActividad', $filterTipoActividad)
                                        ->setParameter('eId', $estadoAct)
                                        ->setParameter('eIdC', 3)
                                        ->getQuery()->getResult();
                }
                else{
                    $events = $this->entityManager->getRepository('ERPCRMBundle:CrmActividad')
                                        ->createQueryBuilder('events')
                                        ->join('events.estadoActividad','estAct')
                                        ->where('events.fechaInicio >= :startDate')
                                        ->andwhere('events.fechaFin <= :endDate')
                                        ->andwhere('events.tipoActividad = :tipoActividad')
                                        ->andwhere('estAct.id = :eId')
                                        ->setParameter('startDate', $startString ." 00:00:00")
                                        ->setParameter('endDate', $endString." 00:00:00")
                                        ->setParameter('tipoActividad', $filterTipoActividad)
                                        ->setParameter('eId', $estadoAct)
                                        ->getQuery()->getResult();
                    
                }

            break;
            case '1101':
                if($showCancel==1){
                    $events = $this->entityManager->getRepository('ERPCRMBundle:CrmActividad')
                                        ->createQueryBuilder('events')
                                        ->where('events.fechaInicio >= :startDate')
                                        ->andwhere('events.fechaFin <= :endDate')
                                        ->andwhere('events.tipoActividad = :tipoActividad')
                                        ->andwhere('events.cuenta = :cuenta')
                                        ->andwhere('estAct.id = :eId')
                                        ->orwhere('estAct.id = :eIdC')
                                        ->setParameter('startDate', $startString ." 00:00:00")
                                        ->setParameter('endDate', $endString." 00:00:00")
                                        ->setParameter('tipoActividad', $filterTipoActividad)
                                        ->setParameter('cuenta', $filterCuenta)
                                        ->setParameter('eId', $estadoAct)
                                        ->setParameter('eIdC', 3)
                                        ->getQuery()->getResult();
                }
                else{
                    $events = $this->entityManager->getRepository('ERPCRMBundle:CrmActividad')
                                        ->createQueryBuilder('events')
                                        ->join('events.estadoActividad','estAct')
                                        ->where('events.fechaInicio >= :startDate')
                                        ->andwhere('events.fechaFin <= :endDate')
                                        ->andwhere('events.tipoActividad = :tipoActividad')
                                        ->andwhere('events.cuenta = :cuenta')
                                        ->andwhere('estAct.id = :eId')
                                        ->setParameter('startDate', $startString ." 00:00:00")
                                        ->setParameter('endDate', $endString." 00:00:00")
                                        ->setParameter('tipoActividad', $filterTipoActividad)
                                        ->setParameter('cuenta', $filterCuenta)
                                        ->setParameter('eId', $estadoAct)
                                        ->getQuery()->getResult();
                }
            break;

            case '1110':
                if($showCancel==1){
                    $sql = "SELECT act.nombre, act.id,act.fechaInicio, act.fechaFin, asigAct.id, tip.id as tipId, estAct.id estado
                                        FROM ERPCRMBundle:CrmAsignacionActividad asigAct
                                        JOIN asigAct.actividad act
                                        JOIN asigAct.usuarioAsignado user
                                        JOIN act.tipoActividad tip
                                        JOIN act.estadoActividad estAct
                                        WHERE user.id = :usuario AND tip.id=:tipo AND estAct.id=:eId OR estAct.id=:eIdC";
                    $events = $em->createQuery($sql)
                                    ->setParameters(array('usuario'=>$filterUsuario,'tipo'=>$filterTipoActividad,'eId'=>$estadoAct,'eIdC'=>3))
                                    ->getResult();
                }
                else{
                    $sql = "SELECT act.nombre, act.id,act.fechaInicio, act.fechaFin, asigAct.id, tip.id as tipId, estAct.id estado
                                        FROM ERPCRMBundle:CrmAsignacionActividad asigAct
                                        JOIN asigAct.actividad act
                                        JOIN asigAct.usuarioAsignado user
                                        JOIN act.tipoActividad tip
                                        JOIN act.estadoActividad estAct
                                        WHERE user.id = :usuario AND tip.id=:tipo AND estAct.id <>:eId ";
                    $events = $em->createQuery($sql)
                                    ->setParameters(array('usuario'=>$filterUsuario,'tipo'=>$filterTipoActividad,'eId'=>3))
                                    ->getResult();
                }
            break;

            case '1111':
                if($showCancel==1){
                    $sql = "SELECT act.nombre, act.id,act.fechaInicio, act.fechaFin, asigAct.id, tip.id as tipId, estAct.id estado
                                        FROM ERPCRMBundle:CrmAsignacionActividad asigAct
                                        JOIN asigAct.actividad act
                                        
                                        JOIN asigAct.usuarioAsignado user
                                        JOIN act.tipoActividad tip
                                        JOIN act.estadoActividad estAct
                                        WHERE user.id = :usuario AND tip.id=:tipo AND estAct.id=:eId OR estAct.id=:eIdC";
                    $events = $em->createQuery($sql)
                                    ->setParameters(array('usuario'=>$filterUsuario,'tipo'=>$filterTipoActividad,'cuenta'=>$filterCuenta,'eId'=>$estadoAct,'eIdC'=>3))
                                    ->getResult();
                }
                else{
                    $sql = "SELECT act.nombre, act.id,act.fechaInicio, act.fechaFin, asigAct.id, tip.id as tipId, estAct.id estado
                                        FROM ERPCRMBundle:CrmAsignacionActividad asigAct
                                        JOIN asigAct.actividad act
                                        
                                        JOIN asigAct.usuarioAsignado user
                                        JOIN act.tipoActividad tip
                                        JOIN act.estadoActividad estAct
                                        WHERE user.id = :usuario AND tip.id=:tipo AND estAct.id <>:eId";
                    $events = $em->createQuery($sql)
                                    ->setParameters(array('usuario'=>$filterUsuario,'tipo'=>$filterTipoActividad,'cuenta'=>$filterCuenta,'eId'=>3))
                                    ->getResult();
                }
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
                $title = /*$event['fechaInicio']->format("n/j G:i")."\n".$event['fechaFin']->format("n/j G:i")."\n".*/$event['nombre'].' | '.$event['fechaInicio']->format("G:i").' - '.$event['fechaFin']->format("G:i");
                $eventEntity = new EventEntity($title, $event['fechaInicio'], $event['fechaFin'], false);
                $eventEntity->setId($event['id']);
                
                switch ($event['estado']) {
                    case 1://///Complete
                            $eventEntity->setBgColor('#69BD45');
                            $eventEntity->setFgColor('#FFFFFF !important');
                        break;
                    case 2://///Pendiente
                            $eventEntity->setBgColor('#3852A4');
                            $eventEntity->setFgColor('#FFFFFF !important');
                        break;
                    case 3://///Cancelado
                            $eventEntity->setBgColor('#F00');
                            $eventEntity->setFgColor('#FFFFFF !important');
                        break;
                    case 4://///Perdida
                            $eventEntity->setBgColor('#421E5B');
                            $eventEntity->setFgColor('#FFFFFF !important');
                        break;
                }
                
                
                switch ($event['tipId']) {
                    case 1://///Tasks
                        # code...
                            $eventEntity->setTitle('<i class="fa fa-tasks" aria-hidden="true"></i> '.$title);
                            // $eventEntity->setAllDay(true); // default is false, set to true if this is an all day event
                            //$eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
                            //$eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
                        break;
                    case 2://///Calls
                        # code...
                            //$eventEntity->setBgColor('#5CC8ED'); //set the background color of the event's label
                            $eventEntity->setTitle('<i class="fa fa-phone" aria-hidden="true"></i> '.$title);
                            //$eventEntity->setBgColor('#2699BB'); //set the background color of the event's label
                            //$eventEntity->setFgColor('#FFFFFF !important'); //set the foreground color of the event's label
                            // $eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
                            //$eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
                        break;
                    case 3://///Meetings
                        # code...
                            $eventEntity->setTitle('<i class="fa fa-users" aria-hidden="true"></i> '.$title);    
                            //$eventEntity->setBgColor('#398339'); //set the background color of the event's label
                            //$eventEntity->setFgColor('#FFFFFF !important'); //set the foreground color of the event's label
                            // $eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
                            //$eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
                        break;
                    case 4://///Others
                        # code...
                            $eventEntity->setTitle('<i class="fa fa-list-ul" aria-hidden="true"></i> '.$title);    
                            //$eventEntity->setBgColor('#D58512'); //set the background color of the event's label
                            //$eventEntity->setFgColor('#FFFFFF !important'); //set the foreground color of the event's label
                            // $eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
                            $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
                        break;
                    default:
                        # code...
                            $eventEntity->setTitle('<i class="fa fa-tasks"> </i> '.$title);
                            //$eventEntity->setBgColor('#673293'); //set the background color of the event's label
                            //$eventEntity->setFgColor('#FFFFFF !important'); //set the foreground color of the event's label
                            // $eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
                            $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
                        break;
                }
            } else {
                $title =/*$event->getFechaInicio()->format("n/j G:i")."\n".$event->getFechaFin()->format("n/j G:i")."\n".*/$event->getNombre().' | '.$event->getFechaInicio()->format("G:i")." - ".$event->getFechaFin()->format("G:i");
                $eventEntity = new EventEntity($title, $event->getFechaInicio(), $event->getFechaFin(), false);
                $eventEntity->setId($event->getId());
                switch ($event->getEstadoActividad()->getId()) {
                    case 1://///Finalizado
                            $eventEntity->setBgColor('#69BD45');
                            $eventEntity->setFgColor('#FFFFFF !important');
                        break;
                    case 2://///Pendiente
                            $eventEntity->setBgColor('#3852A4');
                            $eventEntity->setFgColor('#FFFFFF !important');
                        break;
                    case 3://///Cancelado
                            $eventEntity->setBgColor('#F00');
                            $eventEntity->setFgColor('#FFFFFF !important');
                        break;
                    case 4://///Perdida
                            $eventEntity->setBgColor('#421E5B');
                            $eventEntity->setFgColor('#FFFFFF !important');
                        break;
                }
                        
                        
                switch ($event->getTipoActividad()->getId()) {
                    case 1://///Tasks
                        # code...
                            $eventEntity->setTitle('<i class="fa fa-tasks" aria-hidden="true"></i> '.$title);
                            // $eventEntity->setAllDay(true); // default is false, set to true if this is an all day event
                            //$eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
                            //$eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
                        break;
                    case 2://///Calls
                        # code...
                            //$eventEntity->setBgColor('#5CC8ED'); //set the background color of the event's label
                            $eventEntity->setTitle('<i class="fa fa-phone" aria-hidden="true"></i> '.$title);
                            //$eventEntity->setBgColor('#2699BB'); //set the background color of the event's label
                            //$eventEntity->setFgColor('#FFFFFF !important'); //set the foreground color of the event's label
                            // $eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
                            //$eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
                        break;
                    case 3://///Meetings
                        # code...
                            $eventEntity->setTitle('<i class="fa fa-users" aria-hidden="true"></i> '.$title);    
                            //$eventEntity->setBgColor('#398339'); //set the background color of the event's label
                            //$eventEntity->setFgColor('#FFFFFF !important'); //set the foreground color of the event's label
                            // $eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
                            //$eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
                        break;
                    case 4://///Others
                        # code...
                            $eventEntity->setTitle('<i class="fa fa-list-ul" aria-hidden="true"></i> '.$title);    
                            $eventEntity->setBgColor('#D58512'); //set the background color of the event's label
                            $eventEntity->setFgColor('#FFFFFF !important'); //set the foreground color of the event's label
                            // $eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
                            $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
                        break;
                    default:
                        # code...
                            $eventEntity->setTitle('<i class="fa fa-tasks"> </i> '.$title);
                            //$eventEntity->setBgColor('#673293'); //set the background color of the event's label
                            //$eventEntity->setFgColor('#FFFFFF !important'); //set the foreground color of the event's label
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