<?php

namespace ERP\CRMBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\EntityManager;

class TimeZoneService
{
    private $router;
    private $em; 
    private $container;
    
    /**
     * Constructor
     *
     * @param 	RouterInterface $router
     * @param 	Session $session
     */
    public function __construct(Container $container, RouterInterface $router, EntityManager $em)
    {
            $this->router  = $router;
            $this->em = $em;
            $this->container = $container;
    }
    
    public function getTimeZone(){
        $timeZone = $this->em->getRepository('ERPCRMBundle:CtlZonaHoraria')->findBy(array('estado'=>1));
        return $timeZone[0];
    }

}