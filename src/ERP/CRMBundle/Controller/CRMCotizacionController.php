<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ERP\CRMBundle\Entity\CrmCotizacion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Crmcotizacion controller.
 *
 * @Route("admin/quotes")
 */
class CRMCotizacionController extends Controller
{
    /**
     * Lists all CrmCotizacion entities.
     *
     * @Route("/", name="admin_quotes_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        //Persona-usuarios
        $personas = $em->getRepository('ERPCRMBundle:CtlUsuario')->findBy(array('estado'=>1));
        
        //Productos
        $productos = $em->getRepository('ERPCRMBundle:CtlProducto')->findBy(array('estado'=>1));
        
        // Estados de la cotización
        $statusCot = $em->getRepository('ERPCRMBundle:CrmEstadoCotizacion')->findAll();
        
        //Tipos de etiqueta
        $sql = "SELECT DISTINCT tag.id as id, tag.nombre as nombre "
                . "FROM ERPCRMBundle:CrmEtiquetaCotizacion obj "
                ."JOIN obj.etiqueta tag";

        $etiquetas = $em->createQuery($sql)
                    ->getResult();
        
            //var_dump($sql);
            //die();
        
        return $this->render('crmcotizacion/index.html.twig', array(
            'personas'=>$personas,
            'productos'=>$productos,
            'statusCot'=>$statusCot,
            'etiquetas'=>$etiquetas,
            'menuCotizacionA' => true,
        ));
    }

    /**
     * Creates a new CrmCotizacion entity.
     *
     * @Route("/new", name="admin_quotes_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $cRMCotizacion = new CrmCotizacion();
        $form = $this->createForm('ERP\CRMBundle\Form\CRMCotizacionType', $cRMCotizacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cRMCotizacion);
            $em->flush($cRMCotizacion);

            return $this->redirectToRoute('admin_quotes_show', array('id' => $cRMCotizacion->getId()));
        }

        return $this->render('crmcotizacion/new.html.twig', array(
            'cRMCotizacion' => $cRMCotizacion,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CrmCotizacion entity.
     *
     * @Route("/{id}", name="admin_quotes_show")
     * @Method("GET")
     */
    public function showAction(CrmCotizacion $cRMCotizacion)
    {
        $deleteForm = $this->createDeleteForm($cRMCotizacion);

        return $this->render('crmcotizacion/show.html.twig', array(
            'cRMCotizacion' => $cRMCotizacion,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CrmCotizacion entity.
     *
     * @Route("/{id}/edit", name="admin_quotes_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CrmCotizacion $cRMCotizacion)
    {
        $deleteForm = $this->createDeleteForm($cRMCotizacion);
        $editForm = $this->createForm('ERP\CRMBundle\Form\CRMCotizacionType', $cRMCotizacion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_quotes_edit', array('id' => $cRMCotizacion->getId()));
        }

        return $this->render('crmcotizacion/edit.html.twig', array(
            'cRMCotizacion' => $cRMCotizacion,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CrmCotizacion entity.
     *
     * @Route("/{id}", name="admin_quotes_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CrmCotizacion $cRMCotizacion)
    {
        $form = $this->createDeleteForm($cRMCotizacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cRMCotizacion);
            $em->flush($cRMCotizacion);
        }

        return $this->redirectToRoute('admin_quotes_index');
    }

    /**
     * Creates a form to delete a CrmCotizacion entity.
     *
     * @param CrmCotizacion $cRMCotizacion The cRMCotizacion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CrmCotizacion $cRMCotizacion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_quotes_delete', array('id' => $cRMCotizacion->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     *  Se obtienen las cotizaciones registradas en el sistema
     *
     * @Route("/get/data/all", name="admin_adm_quotes_data", options={"expose"=true})
     */
    public function dataQuotesAction(Request $request)
    {        
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');
        $tagId = $request->query->get('param1');
        
        $em = $this->getDoctrine()->getManager();
        $rowsTotal = $em->getRepository('ERPCRMBundle:CrmCotizacion')->findBy(array('estado' => TRUE));
        
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
            case 1:
                $orderByText = "quo.fechaRegistro";
                break;
            case 2:
                $orderByText = "assigned";
                break;
            case 3:
                $orderByText = "est.nombre";
                break;
            case 4:
                $orderByText = "close";
                break;
            case 5:
                $orderByText = "quo.fechaVencimiento";
                break;
        }
        
        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        if($busqueda['value']!=''){ 
            if ($tagId==0) {
                $dql = "SELECT CONCAT('<div id=\"',quo.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, "
                        . "CONCAT('<div style=\"text-align: left;\">', quo.fechaRegistro, '</div>') as created, "
                        . "CONCAT('<div style=\"text-align: left;\">', per.nombre, ' ', per.apellido, '</div>') as assigned, "
                        . "CONCAT('<div style=\"text-align: left;\">', est.nombre, '</div>') as status, "
                        . "CONCAT('<div style=\"text-align: right;\">', ROUND(SUM((det.cantidad * det.valorUnitario) + (det.cantidad * det.valorUnitario * (det.tax/100))),2), '</div>') as total, "
                        . "CONCAT('<div style=\"text-align: left;\">', quo.fechaVencimiento, '</div>') as close "
                        . "FROM ERPCRMBundle:CrmCotizacion quo "
                        . "JOIN quo.usuario us "
                        . "JOIN us.persona per "
                        . "JOIN quo.estadoCotizacion est "
                        . "JOIN quo.detalleCotizacion det "
                        . "WHERE quo.estado = 1 AND CONCAT(upper(quo.fechaRegistro), ' ' , upper(CONCAT(per.nombre, ' ', per.apellido)), ' ' , upper(quo.fechaVencimiento), ' ' , upper(est.nombre)) LIKE upper(:busqueda) "
                        . "GROUP BY quo.id "
                        . "ORDER BY ".$orderByText." ".$orderDir;

                $row['data'] = $em->createQuery($dql)
                        ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                        ->getResult();

                $row['recordsFiltered']= count($row['data']);

                $dql = "SELECT CONCAT('<div id=\"',quo.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, "
                        . "CONCAT('<div style=\"text-align: left;\">', quo.fechaRegistro, '</div>') as created, "
                        . "CONCAT('<div style=\"text-align: left;\">', per.nombre, ' ', per.apellido, '</div>') as assigned, "
                        . "CONCAT('<div style=\"text-align: left;\">', est.nombre, '</div>') as status, "
                        . "CONCAT('<div style=\"text-align: right;\">', ROUND(SUM((det.cantidad * det.valorUnitario) + (det.cantidad * det.valorUnitario * (det.tax/100))),2), '</div>') as total, "
                        . "CONCAT('<div style=\"text-align: left;\">', quo.fechaVencimiento, '</div>') as close "
                        . "FROM ERPCRMBundle:CrmCotizacion quo "
                        . "JOIN quo.usuario us "
                        . "JOIN us.persona per "
                        . "JOIN quo.estadoCotizacion est "
                        . "JOIN quo.detalleCotizacion det "
                        . "WHERE quo.estado = 1 AND CONCAT(upper(opo.nombre), ' ' , upper(quo.fechaRegistro), ' ' , upper(CONCAT(per.nombre, ' ', per.apellido)), ' ' , upper(quo.fechaVencimiento), ' ' , upper(est.nombre)) LIKE upper(:busqueda) "
                        . "GROUP BY quo.id "
                        . "ORDER BY ".$orderByText." ".$orderDir;

                $row['data'] = $em->createQuery($dql)
                        ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                        ->setFirstResult($start)
                        ->setMaxResults($longitud)
                        ->getResult();    
            } else {
                $dql = "SELECT CONCAT('<div id=\"',quo.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, "
                        . "CONCAT('<div style=\"text-align: left;\">', quo.fechaRegistro, '</div>') as created, "
                        . "CONCAT('<div style=\"text-align: left;\">', per.nombre, ' ', per.apellido, '</div>') as assigned, "
                        . "CONCAT('<div style=\"text-align: left;\">', est.nombre, '</div>') as status, "
                        . "CONCAT('<div style=\"text-align: right;\">', ROUND(SUM((det.cantidad * det.valorUnitario) + (det.cantidad * det.valorUnitario * (det.tax/100))),2), '</div>') as total, "
                        . "CONCAT('<div style=\"text-align: left;\">', quo.fechaVencimiento, '</div>') as close "
                        . "FROM ERPCRMBundle:CrmCotizacion quo "
                        . "JOIN quo.usuario us "
                        . "JOIN us.persona per "
                        . "JOIN quo.estadoCotizacion est "
                        . "JOIN quo.tagCotizacion tquo "
                        . "JOIN tquo.etiqueta tag "
                        . "JOIN quo.detalleCotizacion det "
                        . "WHERE quo.estado = 1 AND CONCAT(upper(quo.fechaRegistro), ' ' , upper(CONCAT(per.nombre, ' ', per.apellido)), ' ' , upper(quo.fechaVencimiento), ' ' , upper(est.nombre)) LIKE upper(:busqueda) "
                        . " AND tag.id = " . $tagId
                        . "GROUP BY quo.id "
                        . "ORDER BY ".$orderByText." ".$orderDir;

                $row['data'] = $em->createQuery($dql)
                        ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                        ->getResult();

                $row['recordsFiltered']= count($row['data']);

                $dql = "SELECT CONCAT('<div id=\"',quo.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, "
                        . "CONCAT('<div style=\"text-align: left;\">', quo.fechaRegistro, '</div>') as created, "
                        . "CONCAT('<div style=\"text-align: left;\">', per.nombre, ' ', per.apellido, '</div>') as assigned "
                        . "CONCAT('<div style=\"text-align: left;\">', est.nombre, '</div>') as status, "
                        . "CONCAT('<div style=\"text-align: right;\">', ROUND(SUM((det.cantidad * det.valorUnitario) + (det.cantidad * det.valorUnitario * (det.tax/100))),2), '</div>') as total, "
                        . "CONCAT('<div style=\"text-align: left;\">', quo.fechaVencimiento, '</div>') as close "
                        . "FROM ERPCRMBundle:CrmCotizacion quo "
                        . "JOIN quo.usuario us "
                        . "JOIN us.persona per "
                        . "JOIN quo.estadoCotizacion est "
                        . "JOIN quo.tagCotizacion tquo "
                        . "JOIN tquo.etiqueta tag "
                        . "JOIN quo.detalleCotizacion det "
                        . "WHERE quo.estado = 1 AND CONCAT(upper(opo.nombre), ' ' , upper(quo.fechaRegistro), ' ' , upper(CONCAT(per.nombre, ' ', per.apellido)), ' ' , upper(quo.fechaVencimiento), ' ' , upper(est.nombre)) LIKE upper(:busqueda) "
                        . " AND tag.id = " . $tagId
                        . "GROUP BY quo.id "
                        . " ORDER BY ".$orderByText." ".$orderDir;

                $row['data'] = $em->createQuery($dql)
                        ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                        ->setFirstResult($start)
                        ->setMaxResults($longitud)
                        ->getResult();   
            }
        }
        else{
            if ($tagId==0) {
                $dql = "SELECT CONCAT('<div id=\"',quo.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, "
                        . "CONCAT('<div style=\"text-align: left;\">', quo.fechaRegistro, '</div>') as created, "
                        . "CONCAT('<div style=\"text-align: left;\">', per.nombre, ' ', per.apellido, '</div>') as assigned, "
                        . "CONCAT('<div style=\"text-align: left;\">', est.nombre, '</div>') as status, "
                        . "CONCAT('<div style=\"text-align: right;\">', ROUND(SUM((det.cantidad * det.valorUnitario) + (det.cantidad * det.valorUnitario * (det.tax/100))),2), '</div>') as total, "
                        . "CONCAT('<div style=\"text-align: left;\">', quo.fechaVencimiento, '</div>') as close "
                        . "FROM ERPCRMBundle:CrmCotizacion quo "
                        . "JOIN quo.usuario us "
                        . "JOIN us.persona per "
                        . "JOIN quo.estadoCotizacion est "
                        . "JOIN quo.detalleCotizacion det "
                        . "WHERE quo.estado = 1 "
                        . "GROUP BY quo.id "
                        . "ORDER BY ".$orderByText." ".$orderDir;
                //var_dump($dql);
                $row['data'] = $em->createQuery($dql)
                        ->setFirstResult($start)
                        ->setMaxResults($longitud)
                        ->getResult();
            } else {
                $dql = "SELECT CONCAT('<div id=\"',quo.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, "
                        . "CONCAT('<div style=\"text-align: left;\">', quo.fechaRegistro, '</div>') as created, "
                        . "CONCAT('<div style=\"text-align: left;\">', per.nombre, ' ', per.apellido, '</div>') as assigned, "
                        . "CONCAT('<div style=\"text-align: left;\">', est.nombre, '</div>') as status, "
                        . "CONCAT('<div style=\"text-align: right;\">', ROUND(SUM((det.cantidad * det.valorUnitario) + (det.cantidad * det.valorUnitario * (det.tax/100))),2), '</div>') as total, "
                        . "CONCAT('<div style=\"text-align: left;\">', quo.fechaVencimiento, '</div>') as close "
                        . "FROM ERPCRMBundle:CrmCotizacion quo "
                        . "JOIN quo.usuario us "
                        . "JOIN us.persona per "
                        . "JOIN quo.estadoCotizacion est "
                        . "JOIN quo.tagCotizacion tquo "
                        . "JOIN tquo.etiqueta tag "
                        . "JOIN quo.detalleCotizacion det "
                        . "WHERE quo.estado = 1 AND tag.id = " . $tagId
                        . "GROUP BY quo.id "
                        . "ORDER BY ".$orderByText." ".$orderDir;

                $row['data'] = $em->createQuery($dql)
                        ->setFirstResult($start)
                        ->setMaxResults($longitud)
                        ->getResult();
                
                $sql = "SELECT et.id as id, e.nombre as nombre FROM ERPCRMBundle:CrmCotizacion quo"
                            ." JOIN op.tagCotizacion et "
                            ." JOIN et.etiqueta e "
                            ." WHERE e.id=:id";
                $rowsTotal = $em->createQuery($sql)
                                    ->setParameters(array('id'=>$tagId))
                                    ->getResult();
        
                //$row['draw']=$draw++;  
                $row['recordsTotal'] = count($rowsTotal);
                $row['recordsFiltered']= count($rowsTotal);
            }
        }
        //var_dump($row);
        //die();
        return new Response(json_encode($row));
    }
}
