<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/sales/data/list", name="admin_etapa_ventas_data")
     */
    public function datasalesAction(Request $request)
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
//var_dump($orderDir);
        $orderByText="";
        switch(intval($orderBy)){
            case 1:
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
                    $sql = "SELECT CONCAT('<div id=\"',pac.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',pac.nombre,'</div>') as name, CONCAT('<div style=\"text-align:right;\">',pac.probabilidad,' %</div>') as probability, '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>' as actions,
                                CASE
                                WHEN pac.estado =1 THEN 'Active'
                                ELSE 'Inactive'
                                END AS state FROM ERPCRMBundle:CtlEtapaVenta pac "
                                . "WHERE pac.estado=1 AND CONCAT(upper(pac.nombre),' ',upper(pac.probabilidad)) LIKE upper(:busqueda) "
                                . "AND pac.estado=1 ORDER BY ".$orderByText." ".$orderDir;
                    $row['data'] = $em->createQuery($sql)
                            ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                            ->getResult();                    
                    $row['recordsFiltered']= count($row['data']);
                    $sql = "SELECT CONCAT('<div id=\"',pac.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',pac.nombre,'</div>') as name, CONCAT('<div style=\"text-align:right;\">',pac.probabilidad,' %</div>') as probability, '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>' as actions,
                                CASE
                                WHEN pac.estado =1 THEN 'Active'
                                ELSE 'Inactive'
                                END AS state FROM ERPCRMBundle:CtlEtapaVenta pac "
                                        . "WHERE pac.estado=1 AND CONCAT(upper(pac.nombre),' ',upper(pac.probabilidad)) LIKE upper(:busqueda) "
                                        . "AND pac.estado=1 ORDER BY ".$orderByText." ".$orderDir;
                    $row['data'] = $em->createQuery($sql)
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





    /**
     * Save sales stage
     *
     * @Route("/sales/stage/save", name="admin_ctletapaventa_save_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function saveajaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){

        
            try {
                $name=$request->get("param1");
                $probability=$request->get("param2");
                $id=$request->get("param3");
                $response = new JsonResponse();
                // var_dump($name);
                // var_dump($probability);
                // die();

                $em = $this->getDoctrine()->getManager();
                if ($id=='') {
                        $object = new CtlEtapaVenta();
                        $object->setNombre($name);
                        $object->setProbabilidad($probability);
                        $object->setEstado(true);
                        $em->persist($object);
                        $em->flush();    
                        $data['msg']='¡Saved!';
                        $data['id']=$object->getId();
                } else {
                        $object = $em->getRepository('ERPCRMBundle:CtlEtapaVenta')->find($id);
                        $object->setNombre($name);
                        $object->setProbabilidad($probability);
                        $em->merge($object);
                        $em->flush();    
                        $data['msg']='¡Updated!';
                        $data['id']=$object->getId();
                }
                
                // if ($editForm->isSubmitted() && $editForm->isValid()) {
                //     $em = $this->getDoctrine()->getManager();
                //     $em->persist($ctlEtapaVentum);
                //     $em->flush();

                //     return $this->redirectToRoute('admin_ctletapaventa_edit', array('id' => $ctlEtapaVentum->getId()));
                // }
                
                
                $response->setData($data); 
                
            } catch (Exception $e) {
                $data['error']=$e->getMessage();
                $response->setData($data);
            }
        
            
        } else {   
            $data['error']='Ajax request';
            $response->setData($data);
            
        }
        return $response;
        
    }





    /**
     * Save sales stage
     *
     * @Route("/sales/stage/retrieve", name="admin_ctletapaventa_retrieve_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function retrieveajaxAction(Request $request)
    {
        try {
            $id=$request->get("param1");
            $response = new JsonResponse();
            
            $em = $this->getDoctrine()->getManager();
            $object = $em->getRepository('ERPCRMBundle:CtlEtapaVenta')->find($id);
            if(count($object)){
                
                //$object->setProbabilidad($);
                $em->merge($object);
                $em->flush();    
                $data['name']=$object->getNombre();
                $data['probability']=$object->getProbabilidad();
                //$data['name']=$object->getNombre();
                $data['id']=$object->getId();
            }
            else{
                $data['error']="Error";
            }
                        
            $response->setData($data); 
            
        } catch (Exception $e) {
            $data['error']=$e->getMessage();
            $response->setData($data);
        }
        
        return $response;
        
    }




    /**
     * Save sales stage
     *
     * @Route("/sales/stage/delete", name="admin_ctletapaventa_delete_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function deleteajaxAction(Request $request)
    {
        try {
            $ids=$request->get("param1");
            $response = new JsonResponse();
            // var_dump($ids);
            // die();
            $em = $this->getDoctrine()->getManager();
            foreach ($ids as $key => $id) {
                $object = $em->getRepository('ERPCRMBundle:CtlEtapaVenta')->find($id);    
                if(count($object)){
                    $object->setEstado(0);
                    $em->merge($object);
                    $em->flush();    
                    $data['msg']='¡Data Updated!';
                }
                else{
                    $data['error']="Error";
                }
            }
            $response->setData($data); 
        } catch (Exception $e) {
            $data['error']=$e->getMessage();
            $response->setData($data);
        }
        return $response;
        
    }

    /**
     * Save sales stage
     *
     * @Route("/sales/stage/active", name="admin_ctletapaventa_active_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function activeajaxAction(Request $request)
    {
        try {
            $ids=$request->get("param1");
            $response = new JsonResponse();
            // var_dump($ids);
            // die();
            $em = $this->getDoctrine()->getManager();
            foreach ($ids as $key => $id) {
                $object = $em->getRepository('ERPCRMBundle:CtlEtapaVenta')->find($id);    
                if(count($object)){
                    $object->setEstado(1);
                    $em->merge($object);
                    $em->flush();    
                    $data['msg']='¡Data Updated!';
                }
                else{
                    $data['error']="Error";
                }
            }
            $response->setData($data); 
        } catch (Exception $e) {
            $data['error']=$e->getMessage();
            $response->setData($data);
        }
        return $response;
        
    }




}
