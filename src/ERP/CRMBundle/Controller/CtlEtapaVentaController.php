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
 * @Route("/admin/salestages")
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
            'menuEtapaA' => true,
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
     * List sale stages
     *
     * @Route("/sales/data/list", name="admin_etapa_ventas_data")
     */
    public function datasalesAction(Request $request)
    {
            try {
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
                            $sql = "SELECT CONCAT('<div id=\"',obj.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',obj.nombre,'</div>') as name, CONCAT('<div style=\"text-align:right;\">',obj.probabilidad,' %</div>') as probability, 
                                        CASE
                                        WHEN obj.estado =1 THEN 'Active'
                                        ELSE 'Inactive'
                                        END AS state FROM ERPCRMBundle:CtlEtapaVenta obj "
                                        . "WHERE obj.estado=1 AND CONCAT(upper(obj.nombre),' ',upper(obj.probabilidad)) LIKE upper(:busqueda) "
                                        . "AND obj.estado=1 ORDER BY ".$orderByText." ".$orderDir;
                            $row['data'] = $em->createQuery($sql)
                                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                                    ->getResult();                    
                            $row['recordsFiltered']= count($row['data']);
                            $sql = "SELECT CONCAT('<div id=\"',obj.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',obj.nombre,'</div>') as name, CONCAT('<div style=\"text-align:right;\">',obj.probabilidad,' %</div>') as probability,
                                        CASE
                                        WHEN obj.estado =1 THEN 'Active'
                                        ELSE 'Inactive'
                                        END AS state FROM ERPCRMBundle:CtlEtapaVenta obj "
                                                . "WHERE obj.estado=1 AND CONCAT(upper(obj.nombre),' ',upper(obj.probabilidad)) LIKE upper(:busqueda) "
                                                . "AND obj.estado=1 ORDER BY ".$orderByText." ".$orderDir;
                            $row['data'] = $em->createQuery($sql)
                                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                                    ->setFirstResult($start)
                                    ->setMaxResults($longitud)
                                    ->getResult();
                }
                else{
                    $sql = "SELECT CONCAT('<div id=\"',obj.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',obj.nombre,'</div>') as name, CONCAT('<div style=\"text-align:right;\">',obj.probabilidad,' %</div>') as probability,
                                CASE
                                WHEN obj.estado =1 THEN 'Active'
                                ELSE 'Inactive'
                                END AS state FROM ERPCRMBundle:CtlEtapaVenta obj "
                                        . " WHERE obj.estado=1 ORDER BY ".$orderByText." ".$orderDir;
                    $row['data'] = $em->createQuery($sql)
                            ->setFirstResult($start)
                            ->setMaxResults($longitud)
                            ->getResult();
                            //var_dump($row);
                }
                return new Response(json_encode($row));
            } catch (\Exception $e) {    
                    if(method_exists($e,'getErrorCode')){                     
                    switch (intval($e->getErrorCode()))
                    {
                        case 2003: 
                            $serverOffline= $this->getParameter('app.serverOffline');
                            $row['data'][0]['name'] =$serverOffline.'. CODE: '.$e->getErrorCode();
                        break;
                        default :
                            $row['data'][0]['name'] = $e->getMessage();                           
                        break;
                    }               
                    $row['data'][0]['chk'] ='';
                    $row['data'][0]['probability'] ='';
                    $row['data'][0]['actions'] ='';
                    $row['data'][0]['state'] ='';                     
                    $row['recordsFiltered']= 0;
                    }                                    
                    else{
                            $data['error']=$e->getMessage();
                    }
                return new Response(json_encode($row));        
        }
    
        
                
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
                $sql = "SELECT upper(pac.nombre) FROM ERPCRMBundle:CtlEtapaVenta pac "
                                            . "WHERE pac.estado=1 AND upper(pac.nombre) LIKE upper(:busqueda) "
                                            . "AND pac.estado=1";
                $objectDuplicate = $em->createQuery($sql)
                                        ->setParameters(array('busqueda'=>"".strtoupper($name).""))
                                        ->getResult();   

                    
                    
                    if (count($objectDuplicate) && $id=='') {
                        $serverDuplicateName = $this->getParameter('app.serverDuplicateName');
                        $data['error'] = $serverDuplicateName;
                    } else {
                        if ($id=='') {
                                $object = new CtlEtapaVenta();
                                $object->setNombre($name);
                                $object->setProbabilidad($probability);
                                $object->setEstado(true);
                                $em->persist($object);
                                $em->flush();    
                                $serverSave = $this->getParameter('app.serverMsgSave');
                                $data['msg']=$serverSave;
                                $data['id']=$object->getId();
                        } else {
                                $object = $em->getRepository('ERPCRMBundle:CtlEtapaVenta')->find($id);
                                $object->setNombre($name);
                                $object->setProbabilidad($probability);
                                $em->merge($object);
                                $em->flush();    
                                $serverUpdate = $this->getParameter('app.serverMsgUpdate');
                                $data['msg']=$serverUpdate; 
                                $data['id']=$object->getId();
                        }
                        
                        
                    }
                $response->setData($data); 
            } catch (\Exception $e) {
                    //var_dump($e);
                    if(method_exists($e,'getErrorCode')){
                        switch (intval($e->getErrorCode())){
                            case 2003: 
                                $serverOffline = $this->getParameter('app.serverOffline');
                                $data['error'] = $serverOffline.'. CODE: '.$e->getErrorCode();
                            break;
                            case 1062: 
                                $serverDuplicate = $this->getParameter('app.serverDuplicateName');
                                $data['error'] = $serverDuplicate."! CODE: ".$e->getErrorCode();
                            break;
                            default :
                                $data['error'] = "Error CODE: ".$e->getMessage();               
                            break;
                            }      
                    }
                    else{
                            $data['error']=$e->getMessage();
                    }
                    $response->setData($data);
            }
        
            
        } else {   
            $data['error']='Ajax request';
            $response->setData($data);
            
        }
        return $response;
        
    }





    /**
     * Retrieve sales stage
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
            if(count($object)!=0){
                
                //$object->setProbabilidad($);
                //$em->merge($object);
                //$em->flush();    
                $data['name']=$object->getNombre();
                $data['probability']=$object->getProbabilidad();
                //$data['name']=$object->getNombre();
                $data['id']=$object->getId();
            }
            else{
                $data['error']="Error";
            }
                        
            $response->setData($data); 
            
        } catch (\Exception $e) {
            if(method_exists($e,'getErrorCode')){
                switch (intval($e->getErrorCode()))
                        {
                            case 2003: 
                                $serverOffline = $this->getParameter('app.serverOffline');
                                $data['error'] = $serverOffline.'. CODE: '.$e->getErrorCode();
                            break;
                            default :
                                $data['error'] = "Error CODE: ".$e->getMessage();                     
                            break;
                        }      
            }
            else{
                    $data['error']=$e->getMessage();
            }
            $response->setData($data);
        }
        
        return $response;
        
    }




    /**
     * Delete sales stage
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
                    $serverDelete = $this->getParameter('app.serverMsgDelete');
                    $data['msg']=$serverDelete;
                }
                else{
                    $data['error']="Error";
                }
            }
            $response->setData($data); 
        } catch (\Exception $e) {
            if(method_exists($e,'getErrorCode')){
                switch (intval($e->getErrorCode()))
                    {
                        case 2003: 
                            $serverOffline = $this->getParameter('app.serverOffline');
                            $data['error'] = $serverOffline.'. CODE: '.$e->getErrorCode();
                        break;
                        default :
                            $data['error'] = $e->getMessage();                     
                        break;
                    }      
             }
            else{
                    $data['error']=$e->getMessage();
            }
            $response->setData($data);
        }
        return $response;
        
    }

    




}
