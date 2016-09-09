<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CtlTiempoNotificacion;
use ERP\CRMBundle\Form\CtlTiempoNotificacionType;

/**
 * CtlTiempoNotificacion controller.
 *
 * @Route("/admin/notifications")
 */
class CtlTiempoNotificacionController extends Controller
{
    /**
     * Lists all CtlTiempoNotificacion entities.
     *
     * @Route("/", name="admin_notifications_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        // $em = $this->getDoctrine()->getManager();

        // $ctlTiempoNotificacions = $em->getRepository('ERPCRMBundle:CtlTiempoNotificacion')->findAll();

        return $this->render('ctltiemponotificacion/index.html.twig', array(
            // 'ctlTiempoNotificacions' => $ctlTiempoNotificacions,
            'menuTiempoNotificacionA' => true,
        ));
    }

    /**
     * Creates a new CtlTiempoNotificacion entity.
     *
     * @Route("/new", name="admin_notifications_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ctlTiempoNotificacion = new CtlTiempoNotificacion();
        $form = $this->createForm('ERP\CRMBundle\Form\CtlTiempoNotificacionType', $ctlTiempoNotificacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlTiempoNotificacion);
            $em->flush();

            return $this->redirectToRoute('admin_notifications_show', array('id' => $ctlTiempoNotificacion->getId()));
        }

        return $this->render('ctltiemponotificacion/new.html.twig', array(
            'ctlTiempoNotificacion' => $ctlTiempoNotificacion,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CtlTiempoNotificacion entity.
     *
     * @Route("/{id}", name="admin_notifications_show")
     * @Method("GET")
     */
    public function showAction(CtlTiempoNotificacion $ctlTiempoNotificacion)
    {
        $deleteForm = $this->createDeleteForm($ctlTiempoNotificacion);

        return $this->render('ctltiemponotificacion/show.html.twig', array(
            'ctlTiempoNotificacion' => $ctlTiempoNotificacion,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CtlTiempoNotificacion entity.
     *
     * @Route("/{id}/edit", name="admin_notifications_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CtlTiempoNotificacion $ctlTiempoNotificacion)
    {
        $deleteForm = $this->createDeleteForm($ctlTiempoNotificacion);
        $editForm = $this->createForm('ERP\CRMBundle\Form\CtlTiempoNotificacionType', $ctlTiempoNotificacion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlTiempoNotificacion);
            $em->flush();

            return $this->redirectToRoute('admin_notifications_edit', array('id' => $ctlTiempoNotificacion->getId()));
        }

        return $this->render('ctltiemponotificacion/edit.html.twig', array(
            'ctlTiempoNotificacion' => $ctlTiempoNotificacion,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CtlTiempoNotificacion entity.
     *
     * @Route("/{id}", name="admin_notifications_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CtlTiempoNotificacion $ctlTiempoNotificacion)
    {
        $form = $this->createDeleteForm($ctlTiempoNotificacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ctlTiempoNotificacion);
            $em->flush();
        }

        return $this->redirectToRoute('admin_notifications_index');
    }

    /**
     * Creates a form to delete a CtlTiempoNotificacion entity.
     *
     * @param CtlTiempoNotificacion $ctlTiempoNotificacion The CtlTiempoNotificacion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CtlTiempoNotificacion $ctlTiempoNotificacion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_notifications_delete', array('id' => $ctlTiempoNotificacion->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    /**
     * List time notifications
     *
     * @Route("/time/notifications/list", name="admin_tiempo_notificaciones_data")
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
                        $orderByText = "duration";
                        break;
                    case 3:
                        $orderByText = "unit";
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
                    $sql = "SELECT CONCAT('<div id=\"',obj.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',obj.nombre,'</div>') as name, obj.duracion as duration, obj.unidadTiempo as unit
                                 FROM ERPCRMBundle:CtlTiempoNotificacion obj "
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
                    $row['data'][0]['duration'] ='';
                    $row['data'][0]['unit'] ='';                     
                    $row['recordsFiltered']= 0;
                    }                                    
                    else{
                            $data['error']=$e->getMessage();
                    }
                return new Response(json_encode($row));        
        }
    
        
                
    }




    /**
     * Save time notification
     *
     * @Route("/time/notification/save", name="admin_time_notification_save_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function saveajaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){

        
            try {
                $name=$request->get("param1");
                $duration=$request->get("param2");
                $unit=$request->get("param3");
                
                $id=$request->get("param4");
                $response = new JsonResponse();
                // var_dump($name);
                // var_dump($probability);
                // die();

                $em = $this->getDoctrine()->getManager();
                $sql = "SELECT upper(obj.nombre) FROM ERPCRMBundle:CtlTiempoNotificacion obj "
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
                                // echo "if";
                                $object = new CtlTiempoNotificacion();
                                $object->setNombre($name);
                                $object->setDuracion($duration);
                                $object->setUnidadTiempo($unit);
                                $object->setEstado(true);
                                $em->persist($object);
                                $em->flush();    
                                $serverSave = $this->getParameter('app.serverMsgSave');
                                $data['msg']=$serverSave;
                                $data['id']=$object->getId();
                        } else {
                                // echo "else";
                                $object = $em->getRepository('ERPCRMBundle:CtlTiempoNotificacion')->find($id);
                                $object->setNombre($name);
                                $object->setDuracion($duration);
                                $object->setUnidadTiempo($unit);
                                //$object->setNombre($name);
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
     * Retrieve time notification
     *
     * @Route("/time/notification/retrieve", name="admin_time_notification_retrieve_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function retrieveajaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $id=$request->get("param1");
                $response = new JsonResponse();
                
                $em = $this->getDoctrine()->getManager();
                $object = $em->getRepository('ERPCRMBundle:CtlTiempoNotificacion')->find($id);
                if(count($object)!=0){
                
                    //$object->setProbabilidad($);
                    //$em->merge($object);
                    //$em->flush();    
                    $data['name']=$object->getNombre();
                    $data['duration']=$object->getDuracion();
                    $data['unit']=$object->getUnidadTiempo();
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
        } else {   
            $data['error']='Ajax request';
            $response->setData($data);
            
        }
        return $response;
        
    }



    /**
     * Delete time notification
     *
     * @Route("/time/notification/delete", name="admin_time_notification_delete_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function deleteajaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
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
        } else {   
            $data['error']='Ajax request';
            $response->setData($data);
            
        }
        return $response;
        
    }



}
