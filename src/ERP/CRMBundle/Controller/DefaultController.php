<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
//        $em = $this->getDoctrine()->getManager();
//
//        $company = $em->getRepository('ERPCRMBundle:CtlEmpresa')->findAll();
//        
//        if($company === NULL || $company == NULL ){
//            return $this->redirect($this->generateUrl('admin_company_configuration'));            
//        } elseif (!$company[0]->getWizard()) {
//            return $this->redirect($this->generateUrl('admin_company_configuration'));            
//        } else {    
            return $this->redirect($this->generateUrl('admin_dashboard'));
//        }                
    }
    
    /**
     * @Route("/admin/dashboard", name="admin_dashboard", options={"expose"=true})
     * 
     */
    public function dashboardAction()
    {
        return $this->render('dashboard/dashboard.html.twig');
    }
    
    /**
     * Configuracion inicial del sistema
     *
     * @Route("/configuration", name="admin_company_configuration")
     * @Method("GET")
     */
    public function wizardAction()
    {
        $em = $this->getDoctrine()->getManager();

        $company = $em->getRepository('ERPCRMBundle:CtlEmpresa')->findAll();
        
        
        if($company === NULL || $company == NULL){
            return $this->render('ctlempresa/wizard.html.twig');           
         } elseif (!$company[0]->getWizard()) {    
             return $this->render('ctlempresa/wizard.html.twig');           
        } else {
            return $this->redirect($this->generateUrl('erpdg_login'));
        }                
    }
}
