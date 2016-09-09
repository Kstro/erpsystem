<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CrmTipoCampania;
use ERP\CRMBundle\Form\CrmTipoCampaniaType;

/**
 * CrmTipoCampania controller.
 *
 * @Route("/admin/crmtipocampania")
 */
class CrmTipoCampaniaController extends Controller
{
    /**
     * Lists all CrmTipoCampania entities.
     *
     * @Route("/", name="admin_crmtipocampania_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $crmTipoCampanias = $em->getRepository('ERPCRMBundle:CrmTipoCampania')->findAll();

        return $this->render('crmtipocampania/index.html.twig', array(
            'crmTipoCampanias' => $crmTipoCampanias,
        ));
    }

    /**
     * Creates a new CrmTipoCampania entity.
     *
     * @Route("/new", name="admin_crmtipocampania_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $crmTipoCampanium = new CrmTipoCampania();
        $form = $this->createForm('ERP\CRMBundle\Form\CrmTipoCampaniaType', $crmTipoCampanium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crmTipoCampanium);
            $em->flush();

            return $this->redirectToRoute('admin_crmtipocampania_show', array('id' => $crmTipoCampanium->getId()));
        }

        return $this->render('crmtipocampania/new.html.twig', array(
            'crmTipoCampanium' => $crmTipoCampanium,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CrmTipoCampania entity.
     *
     * @Route("/{id}", name="admin_crmtipocampania_show")
     * @Method("GET")
     */
    public function showAction(CrmTipoCampania $crmTipoCampanium)
    {
        $deleteForm = $this->createDeleteForm($crmTipoCampanium);

        return $this->render('crmtipocampania/show.html.twig', array(
            'crmTipoCampanium' => $crmTipoCampanium,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CrmTipoCampania entity.
     *
     * @Route("/{id}/edit", name="admin_crmtipocampania_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CrmTipoCampania $crmTipoCampanium)
    {
        $deleteForm = $this->createDeleteForm($crmTipoCampanium);
        $editForm = $this->createForm('ERP\CRMBundle\Form\CrmTipoCampaniaType', $crmTipoCampanium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crmTipoCampanium);
            $em->flush();

            return $this->redirectToRoute('admin_crmtipocampania_edit', array('id' => $crmTipoCampanium->getId()));
        }

        return $this->render('crmtipocampania/edit.html.twig', array(
            'crmTipoCampanium' => $crmTipoCampanium,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CrmTipoCampania entity.
     *
     * @Route("/{id}", name="admin_crmtipocampania_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CrmTipoCampania $crmTipoCampanium)
    {
        $form = $this->createDeleteForm($crmTipoCampanium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($crmTipoCampanium);
            $em->flush();
        }

        return $this->redirectToRoute('admin_crmtipocampania_index');
    }

    /**
     * Creates a form to delete a CrmTipoCampania entity.
     *
     * @param CrmTipoCampania $crmTipoCampanium The CrmTipoCampania entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CrmTipoCampania $crmTipoCampanium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_crmtipocampania_delete', array('id' => $crmTipoCampanium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }







    /**
     * 
     *
     * @Route("/campaigns/data/list", name="admin_campaigns_data")
     */
    public function datacampaignsAction(Request $request)
    {
        
    
        
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');
        
        $em = $this->getDoctrine()->getEntityManager();
        $rowsTotal = $em->getRepository('ERPCRMBundle:CrmTipoCampania')->findAll();
        
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
            case 0:
                $orderByText = "name";
                break;
            case 2:
                $orderByText = "probability";
                break;
            case 3:
                $orderByText = "state";
                break;
        }
        
        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        if($busqueda['value']!=''){
            
                    
                    // $sql = "SELECT pac.nombre as name, 
                    //             CASE
                    //             WHEN pac.estado =1 THEN 'Active'
                    //             ELSE 'Inactive'
                    //             END AS state FROM ERPCRMBundle:CtlEtapaVenta pac "
                    //             . "WHERE pac.estado=1 AND CONCAT(upper(pac.nombre),' ',upper(pac.probabilidad),' ',upper(pac.estado)) LIKE upper(:busqueda) "
                    //             . "AND pac.estado=1 ORDER BY ".$orderByText." ".$orderDir;
                                $sql = "SELECT pac.nombre as name, 
                                FROM ERPCRMBundle:CtlEtapaVenta pac "
                                . "WHERE pac.estado=1 AND upper(pac.nombre) LIKE upper(:busqueda) "
                                . "AND pac.estado=1 ORDER BY ".$orderByText." ".$orderDir;
                    $row['data'] = $em->createQuery($dql)
                            ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                            ->getResult();
                    
                    $row['recordsFiltered']= count($row['data']);
                    
                    $sql = "SELECT pac.nombre as name,
                                FROM ERPCRMBundle:CtlEtapaVenta pac "
                                        . "WHERE pac.estado=1 AND upper(pac.nombre) LIKE upper(:busqueda) "
                                        . "AND pac.estado=1 ORDER BY ".$orderByText." ".$orderDir;
                    $row['data'] = $em->createQuery($dql)
                            ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                            ->setFirstResult($start)
                            ->setMaxResults($longitud)
                            ->getResult();
              
        }
        else{

            $sql = "SELECT CONCAT('<div id=\"',pac.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',pac.nombre,'</div>') as name, CONCAT('<div style=\"text-align:right;\">',pac.probabilidad,' %</div>') as probability, '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>' as actions,
                CASE
                WHEN pac.estado =1 THEN 'Active'
                ELSE 'Inactive'
                END AS state FROM ERPCRMBundle:CtlEtapaVenta pac "
                        . " WHERE pac.estado=1 ORDER BY ".$orderByText." ".$orderDir;
            
            $row['data'] = $em->createQuery($sql)
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();
        }
        
        return new Response(json_encode($row));
    }




}
