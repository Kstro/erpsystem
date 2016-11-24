<?php

namespace ERP\CRMBundle\Services;


use ERP\CRMBundle\Entity\Bitacora;
use Doctrine\ORM\EntityManager;
/**
 * Bitacora
 *
 * 
 */
class GuardarBitacora
// class Bitacora 
{
    protected $em;
    public function __construct(EntityManager $em){
        $this->em=$em;
    }
    
    /**
     * 
     * @param string $mensaje
     * @param int $id
     */
    public function escribirbitacora($mensaje,$id){
        
        // $em Doctrine\ORM\EntityManager;
        // $em = EntityManager;
        $entity  = new Bitacora();
        //var_dump($id);
        $usuario = $this->em->getRepository('ERPCRMBundle:CtlUsuario')->find($id);
        $entity->setAccion($mensaje);
        $entity->setFechaAccion(new \DateTime ('now'));
        $entity->setUsuario($usuario);
        // var_dump($entity);
        $this->em->persist($entity);
        $this->em->flush();
    }       
}