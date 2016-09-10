<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CtlPrioridad;
use ERP\CRMBundle\Form\CtlPrioridadType;

/**
 * CtlPrioridad controller.
 *
 * @Route("/admin/priority")
 */
class CtlPrioridadController extends Controller
{
    /**
     * Lists all CtlPrioridad entities.
     *
     * @Route("/", name="admin_priority_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ctlPrioridads = $em->getRepository('ERPCRMBundle:CtlPrioridad')->findAll();

        return $this->render('ctlprioridad/index.html.twig', array(
            'ctlPrioridads' => $ctlPrioridads,
        ));
    }

    /**
     * Creates a new CtlPrioridad entity.
     *
     * @Route("/new", name="admin_priority_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ctlPrioridad = new CtlPrioridad();
        $form = $this->createForm('ERP\CRMBundle\Form\CtlPrioridadType', $ctlPrioridad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlPrioridad);
            $em->flush();

            return $this->redirectToRoute('admin_priority_show', array('id' => $ctlPrioridad->getId()));
        }

        return $this->render('ctlprioridad/new.html.twig', array(
            'ctlPrioridad' => $ctlPrioridad,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * Creates a new CtlPrioridad entity.
     *
     * @Route("/register", name="admin_register_priority", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function registerPriorityAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $em = $this->getDoctrine()->getManager();
                
                $parameters = $request->request->all();
                $priorityname = $parameters['name'];
                $id = $parameters['id'];
                
                $sql = "SELECT upper(pri.nombre) FROM ERPCRMBundle:CtlPrioridad pri "
                        . "WHERE upper(pri.nombre) LIKE upper(:busqueda) AND pri.estado = 1";
                
                $objectDuplicate = $em->createQuery($sql)
                                     ->setParameters(array('busqueda'=>"%".strtoupper($priorityname)."%"))
                                     ->getResult(); 
                
                if (count($objectDuplicate)) {
                    $data['error'] = $this->getParameter('app.serverDuplicateName');
                } else {
                    if ($id=='') {
                        $priority = new CtlPrioridad();
                        $priority->setNombre($priorityname);
                        $priority->setEstado(1);

                        $em->persist($priority);
                        $em->flush();
                        
                        $serverSave = $this->getParameter('app.serverMsgSave');
                        $data['msg']=$serverSave;
                        $data['id']=$priority->getId();
                    } else {
                        $priority = $em->getRepository('ERPCRMBundle:CtlPrioridad')->find($id);
                        $priority->setNombre($priorityname);
                        
                        $em->merge($priority);
                        $em->flush();
                        
                        $serverUpdate = $this->getParameter('app.serverMsgUpdate');
                        $data['msg']=$serverUpdate; 
                        $data['id']=$priority->getId();
                    }                                        
                }
                
                $response = new JsonResponse();
                $response->setData(array(
                                  'msg'   => $data
                               ));  
            
                return $response; 
            } catch (Exception $e) {
                if(method_exists($e,'getErrorCode')){
                    switch (intval($e->getErrorCode())){
                        case 2003: 
                            $data['error'] = $this->getParameter('app.serverOffline');
                        break;
                        case 1062: 
                            $data['error'] = $this->getParameter('app.serverDuplicateName');
                        break;
                        default :
                            $data['error'] = $e->getMessage();                     
                        break;
                        }      
                }
                else{
                    $data['error']=$e->getMessage();
                }
                    
                $response = new JsonResponse();
                $response->setData(array(
                                  'msg'   => $data
                               ));  
            }                
        } else {    
            return new Response('0');              
        }
    }

    /**
     * Finds and displays a CtlPrioridad entity.
     *
     * @Route("/{id}", name="admin_priority_show")
     * @Method("GET")
     */
    public function showAction(CtlPrioridad $ctlPrioridad)
    {
        $deleteForm = $this->createDeleteForm($ctlPrioridad);

        return $this->render('ctlprioridad/show.html.twig', array(
            'ctlPrioridad' => $ctlPrioridad,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CtlPrioridad entity.
     *
     * @Route("/{id}/edit", name="admin_priority_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CtlPrioridad $ctlPrioridad)
    {
        $deleteForm = $this->createDeleteForm($ctlPrioridad);
        $editForm = $this->createForm('ERP\CRMBundle\Form\CtlPrioridadType', $ctlPrioridad);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlPrioridad);
            $em->flush();

            return $this->redirectToRoute('admin_priority_edit', array('id' => $ctlPrioridad->getId()));
        }

        return $this->render('ctlprioridad/edit.html.twig', array(
            'ctlPrioridad' => $ctlPrioridad,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CtlPrioridad entity.
     *
     * @Route("/{id}", name="admin_priority_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CtlPrioridad $ctlPrioridad)
    {
        $form = $this->createDeleteForm($ctlPrioridad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ctlPrioridad);
            $em->flush();
        }

        return $this->redirectToRoute('admin_priority_index');
    }

    /**
     * Creates a form to delete a CtlPrioridad entity.
     *
     * @param CtlPrioridad $ctlPrioridad The CtlPrioridad entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CtlPrioridad $ctlPrioridad)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_priority_delete', array('id' => $ctlPrioridad->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * 
     *
     * @Route("/priority/data/as", name="admin_priorities_data", options={"expose"=true})
     */
    public function dataPriorityAction(Request $request)
    {        
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');
        
        $em = $this->getDoctrine()->getEntityManager();
        $rowsTotal = $em->getRepository('ERPCRMBundle:CtlPrioridad')->findAll();
        
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
                $orderByText = "name";
                break;
        }
        
        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        if($busqueda['value']!=''){                                
            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', pri.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOriginSource fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',pri.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when pri.estado = 1 then 'Active' "
                    . "else 'Inactive' "
                    . "as state "
                    . "FROM ERPCRMBundle:CtlPrioridad pri "
                    . "WHERE pri.estado = 1 AND CONCAT(upper(pri.nombre), ' ' , upper(pri.estado)) LIKE upper(:busqueda) "
                    . "ORDER BY ".$orderByText." ".$orderDir;

            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                    ->getResult();

            $row['recordsFiltered']= count($row['data']);

            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', pri.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOriginSource fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',pri.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when pri.estado = 1 then 'Active' "
                    . "else 'Inactive' "
                    . "as state "
                    . "FROM ERPCRMBundle:CtlPrioridad pri "
                    . "WHERE pri.estado = 1 AND CONCAT(upper(pri.nombre),' ', upper(pri.estado)) LIKE upper(:busqueda) "
                    . "ORDER BY ".$orderByText." ".$orderDir;

            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();              
        }
        else{
            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', pri.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOriginSource fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',pri.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when pri.estado = 1 then 'Active' "
                    . "else 'Inactive' "
                    . "as state "
                    . "FROM ERPCRMBundle:CtlPrioridad pri "
                    . "WHERE pri.estado = 1 ORDER BY ".$orderByText." ".$orderDir;
            
            $row['data'] = $em->createQuery($dql)
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();
        }
        
        return new Response(json_encode($row));
    }
    
    /**
     * Retrieve the priority
     *
     * @Route("/priority/retrieve", name="admin_retrieve_priority", options={"expose"=true}))
     * @Method("POST")
     */
    public function retrievePriorityAction(Request $request)
    {
        try {
            $id=$request->get("id");
            $response = new JsonResponse();
            
            $em = $this->getDoctrine()->getManager();
            $priority = $em->getRepository('ERPCRMBundle:CtlPrioridad')->find($id);
            if(count($priority)){
                
                $data['name']=$priority->getNombre();
                $data['id']=$priority->getId();
            }
            else{
                $data['error']="Error";
            }
                        
            $response->setData($data); 
            
        } catch (\Exception $e) {
            switch (intval($e->getErrorCode()))
                    {
                        case 2003: 
                            $data['error'] = $this->getParameter('app.serverOffline');
                        break;
                        default :
                            $data['error'] = $e->getMessage();                     
                        break;
                    }      
            $response->setData($data);
        }
        
        return $response;
        
    }
    
    /**
     * Delete the priority
     *
     * @Route("/priority/delete", name="admin_delete_priority",  options={"expose"=true}))
     * @Method("POST")
     */
    public function deletePriorityAction(Request $request)
    {
        try {
            $ids=$request->get("param1");
            $response = new JsonResponse();
            
            $em = $this->getDoctrine()->getManager();
            foreach ($ids as $key => $id) {
                $object = $em->getRepository('ERPCRMBundle:CtlPrioridad')->find($id);    
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
            $response = new JsonResponse();
            
            switch (intval($e->getErrorCode()))
                    {
                        case 2003: 
                            $data['error'] = $this->getParameter('app.serverOffline');
                        break;
                        default :
                            $data['error'] = $e->getMessage();                     
                        break;
                    }      
            $data['error']=$e->getMessage();
            $response->setData($data);
        }
        
        return $response;        
    }
}
