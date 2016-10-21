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
        $items = $em->getRepository('ERPCRMBundle:CtlTratamientoProtocolario')->findAll();
        $tiposTelefono = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->findAll();
        $industrias = $em->getRepository('ERPCRMBundle:CtlIndustria')->findAll(array('estado'=>1));
        $estados = $em->getRepository('ERPCRMBundle:CtlEstado')->findAll();
        $ciudades = $em->getRepository('ERPCRMBundle:CtlCiudad')->findAll();
        
        if($company === NULL || $company == NULL){
            return $this->render('ctlempresa/wizard.html.twig', array(
                'items'=>$items,
                'tiposTelefono'=>$tiposTelefono,
                'industrias'=>$industrias,
                'estados'=>$estados,
                'ciudades'=>$ciudades
            ));           
         } elseif (!$company[0]->getWizard()) {    
             return $this->render('ctlempresa/wizard.html.twig', array(
                'items'=>$items,
                'tiposTelefono'=>$tiposTelefono,
                'industrias'=>$industrias,
                'estados'=>$estados,
                'ciudades'=>$ciudades
            ));           
        } else {
            return $this->redirect($this->generateUrl('erpdg_login'));
        }                
    }
}
