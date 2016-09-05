<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CtlNivelSatisfaccion;
use ERP\CRMBundle\Form\CtlNivelSatisfaccionType;

/**
 * CtlNivelSatisfaccion controller.
 *
 * @Route("/admin/levelcustomersatisfaction")
 */
class CtlNivelSatisfaccionController extends Controller
{
    /**
     * Lists all CtlNivelSatisfaccion entities.
     *
     * @Route("/", name="levelcustomersatisfaction_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // $ctlNivelSatisfaccions = $em->getRepository('ERPCRMBundle:CtlNivelSatisfaccion')->findAll();

        return $this->render('ctlnivelsatisfaccion/index.html.twig', array(
            // 'ctlNivelSatisfaccions' => $ctlNivelSatisfaccions,
            'menuSatisfaccionA' => true,
        ));
    }

    /**
     * Creates a new CtlNivelSatisfaccion entity.
     *
     * @Route("/new", name="levelcustomersatisfaction_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ctlNivelSatisfaccion = new CtlNivelSatisfaccion();
        $form = $this->createForm('ERP\CRMBundle\Form\CtlNivelSatisfaccionType', $ctlNivelSatisfaccion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlNivelSatisfaccion);
            $em->flush();

            return $this->redirectToRoute('levelcustomersatisfaction_show', array('id' => $ctlNivelSatisfaccion->getId()));
        }

        return $this->render('ctlnivelsatisfaccion/new.html.twig', array(
            'ctlNivelSatisfaccion' => $ctlNivelSatisfaccion,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CtlNivelSatisfaccion entity.
     *
     * @Route("/{id}", name="levelcustomersatisfaction_show")
     * @Method("GET")
     */
    public function showAction(CtlNivelSatisfaccion $ctlNivelSatisfaccion)
    {
        $deleteForm = $this->createDeleteForm($ctlNivelSatisfaccion);

        return $this->render('ctlnivelsatisfaccion/show.html.twig', array(
            'ctlNivelSatisfaccion' => $ctlNivelSatisfaccion,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CtlNivelSatisfaccion entity.
     *
     * @Route("/{id}/edit", name="levelcustomersatisfaction_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CtlNivelSatisfaccion $ctlNivelSatisfaccion)
    {
        $deleteForm = $this->createDeleteForm($ctlNivelSatisfaccion);
        $editForm = $this->createForm('ERP\CRMBundle\Form\CtlNivelSatisfaccionType', $ctlNivelSatisfaccion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlNivelSatisfaccion);
            $em->flush();

            return $this->redirectToRoute('levelcustomersatisfaction_edit', array('id' => $ctlNivelSatisfaccion->getId()));
        }

        return $this->render('ctlnivelsatisfaccion/edit.html.twig', array(
            'ctlNivelSatisfaccion' => $ctlNivelSatisfaccion,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CtlNivelSatisfaccion entity.
     *
     * @Route("/{id}", name="levelcustomersatisfaction_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CtlNivelSatisfaccion $ctlNivelSatisfaccion)
    {
        $form = $this->createDeleteForm($ctlNivelSatisfaccion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ctlNivelSatisfaccion);
            $em->flush();
        }

        return $this->redirectToRoute('levelcustomersatisfaction_index');
    }

    /**
     * Creates a form to delete a CtlNivelSatisfaccion entity.
     *
     * @param CtlNivelSatisfaccion $ctlNivelSatisfaccion The CtlNivelSatisfaccion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CtlNivelSatisfaccion $ctlNivelSatisfaccion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('levelcustomersatisfaction_delete', array('id' => $ctlNivelSatisfaccion->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }







    /**
     * List level of customer satisfaction
     *
     * @Route("/customer/satisfaction/data/list", name="admin_customer_satisfaction_data")
     */
    public function datacampaignsAction(Request $request)
    {
            try {
                $start = $request->query->get('start');
                $draw = $request->query->get('draw');
                $longitud = $request->query->get('length');
                $busqueda = $request->query->get('search');
                
                $em = $this->getDoctrine()->getEntityManager();
                $rowsTotal = $em->getRepository('ERPCRMBundle:CtlNivelSatisfaccion')->findAll();
                
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
                        $orderByText = "state";
                        break;
                }
                $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
                if($busqueda['value']!=''){        
                            $sql = "SELECT CONCAT('<div id=\"',obj.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',obj.nombre,'</div>') as name,
                                CASE
                                WHEN obj.estado =1 THEN 'Active'
                                ELSE 'Inactive'
                                END AS state FROM ERPCRMBundle:CtlNivelSatisfaccion obj "
                                        . "WHERE obj.estado=1 AND upper(obj.nombre) LIKE upper(:busqueda) "
                                        . "AND obj.estado=1 ORDER BY ".$orderByText." ".$orderDir;
                            $row['data'] = $em->createQuery($sql)
                                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                                    ->getResult();                    
                            $row['recordsFiltered']= count($row['data']);
                            $sql = "SELECT CONCAT('<div id=\"',obj.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',obj.nombre,'</div>') as name,
                                CASE
                                WHEN obj.estado =1 THEN 'Active'
                                ELSE 'Inactive'
                                END AS state FROM ERPCRMBundle:CtlNivelSatisfaccion obj "
                                                . "WHERE obj.estado=1 AND upper(obj.nombre) LIKE upper(:busqueda) "
                                                . "AND pac.estado=1 ORDER BY ".$orderByText." ".$orderDir;
                            $row['data'] = $em->createQuery($sql)
                                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                                    ->setFirstResult($start)
                                    ->setMaxResults($longitud)
                                    ->getResult();
                }
                else{
                    $sql = "SELECT CONCAT('<div id=\"',obj.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',obj.nombre,'</div>') as name,
                                CASE
                                WHEN obj.estado =1 THEN 'Active'
                                ELSE 'Inactive'
                                END AS state FROM ERPCRMBundle:CtlNivelSatisfaccion obj "
                                        . " WHERE obj.estado=1 ORDER BY ".$orderByText." ".$orderDir;
                    $row['data'] = $em->createQuery($sql)
                            ->setFirstResult($start)
                            ->setMaxResults($longitud)
                            ->getResult();
                            
                }
                //var_dump($row);
                return new Response(json_encode($row));
            } catch (\Exception $e) {  
                //var_dump($e);
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
                    
                    $row['recordsFiltered']= 0;
                    }                                    
                    else{
                            $data['error']=$e->getMessage();
                    }
                return new Response(json_encode($row));            
        }
    
        
                
    }





    /**
     * Save level of customer satisfaction
     *
     * @Route("/customer/satisfaction/save", name="admin_customer_satisfaction_save_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function saveajaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){

        
            try {
                $name=$request->get("param1");
                
                $id=$request->get("param2");
                $response = new JsonResponse();
                // var_dump($name);
                // var_dump($probability);
                // die();

                $em = $this->getDoctrine()->getManager();
                $sql = "SELECT upper(obj.nombre) FROM ERPCRMBundle:CtlNivelSatisfaccion obj "
                                            . "WHERE obj.estado=1 AND upper(obj.nombre) LIKE upper(:busqueda) "
                                            . "AND obj.estado=1";
                $objectDuplicate = $em->createQuery($sql)
                                        ->setParameters(array('busqueda'=>"".strtoupper($name).""))
                                        ->getResult();   

                    
                   
                    if (count($objectDuplicate) && $id=='') {
                        $serverDuplicateName = $this->getParameter('app.serverDuplicateName');
                        $data['error'] = $serverDuplicateName;
                    } else {
                        if ($id=='') {
                                $object = new CtlNivelSatisfaccion();
                                $object->setNombre($name);
                                $object->setEstado(true);
                                $em->persist($object);
                                $em->flush();    
                                $serverSave = $this->getParameter('app.serverMsgSave');
                                $data['msg']=$serverSave;
                                $data['id']=$object->getId();
                        } else {
                                $object = $em->getRepository('ERPCRMBundle:CtlNivelSatisfaccion')->find($id);
                                $object->setNombre($name);
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
     * Retrieve level of customer satisfaction
     *
     * @Route("/customer/satisfaction/retrieve", name="admin_customer_satisfaction_retrieve_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function retrieveajaxAction(Request $request)
    {
        try {
            $id=$request->get("param1");
            $response = new JsonResponse();
            
            $em = $this->getDoctrine()->getManager();
            $object = $em->getRepository('ERPCRMBundle:CtlNivelSatisfaccion')->find($id);
            if(count($object)){
                
                //$object->setProbabilidad($);
                $em->merge($object);
                $em->flush();    
                $data['name']=$object->getNombre();
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
     * Delete level of customer satisfaction
     *
     * @Route("/customer/satisfaction/delete", name="admin_customer_satisfaction_delete_ajax",  options={"expose"=true}))
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
                $object = $em->getRepository('ERPCRMBundle:CtlNivelSatisfaccion')->find($id);    
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
