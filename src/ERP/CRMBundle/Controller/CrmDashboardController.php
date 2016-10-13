<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CtlRol;


/**
 * CtlRol controller.
 *
 * @Route("/admin/dashboard2")
 */
class CrmDashboardController extends Controller
{
    /**
     * CRM Dashboard
     *
     * @Route("/", name="admin_crm_dashboard_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // $ctlRols = $em->getRepository('ERPCRMBundle:CtlRol')->findAll();

        return $this->render('crm_dashboard/index.html.twig', array(
            // 'ctlRols' => $ctlRols,
        ));
    }

    
}
