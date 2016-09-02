<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CtlEtapaVenta;
use ERP\CRMBundle\Form\CtlEtapaVentaType;

/**
 * CtlEtapaVenta controller.
 *
 * @Route("/admin/ctletapaventa")
 */
class CtlEtapaVentaController extends Controller
{
    /**
     * Lists all CtlEtapaVenta entities.
     *
     * @Route("/", name="admin_ctletapaventa_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        //$ctlEtapaVentas = $em->getRepository('ERPCRMBundle:CtlEtapaVenta')->findAll();

        return $this->render('ctletapaventa/index.html.twig', array(
            //'ctlEtapaVentas' => $ctlEtapaVentas,
        ));
    }

    /**
     * Creates a new CtlEtapaVenta entity.
     *
     * @Route("/new", name="admin_ctletapaventa_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ctlEtapaVentum = new CtlEtapaVenta();
        $form = $this->createForm('ERP\CRMBundle\Form\CtlEtapaVentaType', $ctlEtapaVentum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlEtapaVentum);
            $em->flush();

            return $this->redirectToRoute('admin_ctletapaventa_show', array('id' => $ctlEtapaVentum->getId()));
        }

        return $this->render('ctletapaventa/new.html.twig', array(
            'ctlEtapaVentum' => $ctlEtapaVentum,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CtlEtapaVenta entity.
     *
     * @Route("/{id}", name="admin_ctletapaventa_show")
     * @Method("GET")
     */
    public function showAction(CtlEtapaVenta $ctlEtapaVentum)
    {
        $deleteForm = $this->createDeleteForm($ctlEtapaVentum);

        return $this->render('ctletapaventa/show.html.twig', array(
            'ctlEtapaVentum' => $ctlEtapaVentum,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CtlEtapaVenta entity.
     *
     * @Route("/{id}/edit", name="admin_ctletapaventa_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CtlEtapaVenta $ctlEtapaVentum)
    {
        $deleteForm = $this->createDeleteForm($ctlEtapaVentum);
        $editForm = $this->createForm('ERP\CRMBundle\Form\CtlEtapaVentaType', $ctlEtapaVentum);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlEtapaVentum);
            $em->flush();

            return $this->redirectToRoute('admin_ctletapaventa_edit', array('id' => $ctlEtapaVentum->getId()));
        }

        return $this->render('ctletapaventa/edit.html.twig', array(
            'ctlEtapaVentum' => $ctlEtapaVentum,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CtlEtapaVenta entity.
     *
     * @Route("/{id}", name="admin_ctletapaventa_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CtlEtapaVenta $ctlEtapaVentum)
    {
        $form = $this->createDeleteForm($ctlEtapaVentum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ctlEtapaVentum);
            $em->flush();
        }

        return $this->redirectToRoute('admin_ctletapaventa_index');
    }

    /**
     * Creates a form to delete a CtlEtapaVenta entity.
     *
     * @param CtlEtapaVenta $ctlEtapaVentum The CtlEtapaVenta entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CtlEtapaVenta $ctlEtapaVentum)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_ctletapaventa_delete', array('id' => $ctlEtapaVentum->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }




    /**
     * 
     *
     * @Route("/sales/data/as", name="admin_etapa_ventas_data")
     */
    public function datapacienteAction(Request $request)
    {
        
    
        
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');
        
        $em = $this->getDoctrine()->getEntityManager();
        $rowsTotal = $em->getRepository('ERPCRMBundle:CtlEtapaVenta')->findAll();
        
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
            case 1:
                $orderByText = "probability";
                break;
            case 2:
                $orderByText = "state";
                break;
        }
        
        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        if($busqueda['value']!=''){
            
                    
                    $dql = "SELECT pac.nombre as name,pac.probabilidad as probability,pac.estado as state, '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>' as link FROM ERPCRMBundle:CtlEtapaVenta pac "
                                                . "WHERE pac.estado=1 AND CONCAT(upper(pac.nombre),' ',upper(pac.probabilidad),' ',upper(pac.estado)) LIKE upper(:busqueda) "
                        . "AND pac.estado=1 ORDER BY ".$orderByText." ".$orderDir;
                    $row['data'] = $em->createQuery($dql)
                            ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                            ->getResult();
                    
                    $row['recordsFiltered']= count($row['data']);
                    
                    $dql = "SELECT pac.nombre as name,pac.probabilidad as probability,pac.estado as state, '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>' as link FROM ERPCRMBundle:CtlEtapaVenta pac "
                        . "WHERE pac.estado=1 AND CONCAT(upper(pac.nombre),' ',upper(pac.probabilidad),' ',upper(pac.estado)) LIKE upper(:busqueda) "
                        . "AND pac.estado=1 ORDER BY ".$orderByText." ".$orderDir;
                    $row['data'] = $em->createQuery($dql)
                            ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                            ->setFirstResult($start)
                            ->setMaxResults($longitud)
                            ->getResult();
              
        }
        else{
            $dql = "SELECT pac.nombre as name,pac.probabilidad as probability,pac.estado as state, '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>' as link FROM ERPCRMBundle:CtlEtapaVenta pac "
                        . " WHERE pac.estado=1 ORDER BY ".$orderByText." ".$orderDir;
            $row['data'] = $em->createQuery($dql)
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();
        }
        
        return new Response(json_encode($row));
    }

}
