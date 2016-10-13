<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('admin_dashboard'));
    }
    
    /**
     * @Route("/admin/dashboard", name="admin_dashboard", options={"expose"=true})
     * 
     */
    public function dashboardAction()
    {
        return $this->render('dashboard/dashboard.html.twig');
    }
}
