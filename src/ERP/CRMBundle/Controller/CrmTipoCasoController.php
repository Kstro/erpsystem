<?php

namespace ERP\CRMBundle\Controller;

use ERP\CRMBundle\Entity\CrmTipoCaso;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;



/**
 * Crmtipocaso controller.
 *
 * @Route("/admin/crmtipocaso")
 */
class CrmTipoCasoController extends Controller
{
    /**
     * Lists all crmTipoCaso entities.
     *
     * @Route("/", name="crmtipocaso_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $crmTipoCasos = $em->getRepository('ERPCRMBundle:CrmTipoCaso')->findAll();

        return $this->render('crmtipocaso/index.html.twig', array(
            'crmTipoCasos' => $crmTipoCasos,
            'menuTipoCasoA'=> 'a'
        ));
    }

    /**
     * Creates a new crmTipoCaso entity.
     *
     * @Route("/new", name="crmtipocaso_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $crmTipoCaso = new Crmtipocaso();
        $form = $this->createForm('ERP\CRMBundle\Form\CrmTipoCasoType', $crmTipoCaso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crmTipoCaso);
            $em->flush($crmTipoCaso);

            return $this->redirectToRoute('crmtipocaso_show', array('id' => $crmTipoCaso->getId()));
        }

        return $this->render('crmtipocaso/new.html.twig', array(
            'crmTipoCaso' => $crmTipoCaso,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a crmTipoCaso entity.
     *
     * @Route("/{id}", name="crmtipocaso_show")
     * @Method("GET")
     */
    public function showAction(CrmTipoCaso $crmTipoCaso)
    {
        $deleteForm = $this->createDeleteForm($crmTipoCaso);

        return $this->render('crmtipocaso/show.html.twig', array(
            'crmTipoCaso' => $crmTipoCaso,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing crmTipoCaso entity.
     *
     * @Route("/{id}/edit", name="crmtipocaso_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CrmTipoCaso $crmTipoCaso)
    {
        $deleteForm = $this->createDeleteForm($crmTipoCaso);
        $editForm = $this->createForm('ERP\CRMBundle\Form\CrmTipoCasoType', $crmTipoCaso);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('crmtipocaso_edit', array('id' => $crmTipoCaso->getId()));
        }

        return $this->render('crmtipocaso/edit.html.twig', array(
            'crmTipoCaso' => $crmTipoCaso,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a crmTipoCaso entity.
     *
     * @Route("/{id}", name="crmtipocaso_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CrmTipoCaso $crmTipoCaso)
    {
        $form = $this->createDeleteForm($crmTipoCaso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($crmTipoCaso);
            $em->flush($crmTipoCaso);
        }

        return $this->redirectToRoute('crmtipocaso_index');
    }

    /**
     * Creates a form to delete a crmTipoCaso entity.
     *
     * @param CrmTipoCaso $crmTipoCaso The crmTipoCaso entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CrmTipoCaso $crmTipoCaso)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('crmtipocaso_delete', array('id' => $crmTipoCaso->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
     /**
     * List all case
     *
     * @Route("/tipo/case/data/list", name="admin_tipo_case_data",  options={"expose"=true}))
     */
    public function tipoCaseAction(Request $request)
    {
              try {
                $start = $request->query->get('start');
                $draw = $request->query->get('draw');
                $longitud = $request->query->get('length');
                $busqueda = $request->query->get('search');
                
                $em = $this->getDoctrine()->getEntityManager();
               // $rowsTotal = $em->getRepository('ERPCRMBundle:CrmTipoCaso')->findAll();
                 $rowsTotal = $this->getDoctrine()->getRepository('ERPCRMBundle:CrmTipoCaso')->findBy(array('estado'=>1));
   
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
                            $sql = "SELECT CONCAT('<div id=\"',obj.id,'\" style=\"text-align:left;\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left;\">',obj.nombre,'</div>') as name
                                FROM ERPCRMBundle:CrmTipoCaso obj "
                                                . "WHERE obj.estado=1 AND upper(obj.nombre) LIKE upper(:busqueda) "
                                                . " ORDER BY ".$orderByText." ".$orderDir;
                            $row['data'] = $em->createQuery($sql)
                                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                                    ->setFirstResult($start)
                                    ->setMaxResults($longitud)
                                    ->getResult();
                }
                else{
                    $sql = "SELECT CONCAT('<div id=\"',obj.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',obj.nombre,'</div>') as name
                               FROM ERPCRMBundle:CrmTipoCaso obj "
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
                     return new Response(json_encode($row));    
                    }                                    
                    else{
                            $data['error']=$e->getMessage();
                              return new Response(json_encode($data)); 
                    }
                         
        }
    }
    
       /**
     * Save campaign types
     *
     * @Route("/case/types/save", name="admin_types_case_save_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function saveaTypesCasejaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){

        
            try {
                $name=$request->get("nombre");
                $id=$request->get("id");
                $response = new JsonResponse();
         
                $em = $this->getDoctrine()->getManager();
                $sql = "SELECT upper(obj.nombre) FROM ERPCRMBundle:CrmTipoCaso obj "
                                            . "WHERE obj.estado=1 AND upper(obj.nombre) LIKE upper(:busqueda) ";
                $objectDuplicate = $em->createQuery($sql)
                                        ->setParameters(array('busqueda'=>"".strtoupper($name).""))
                                        ->getResult();   

                    
                   
                    if (count($objectDuplicate) && $id=='') {
                        $serverDuplicateName = $this->getParameter('app.serverDuplicateName');
                        $data['error'] = $serverDuplicateName;
                    } else {
                        if ($id=='') {
                                $object = new CrmTipoCaso();
                                $object->setNombre($name);
                                $object->setEstado(true);
                                $em->persist($object);
                                $em->flush();    
                                $serverSave = $this->getParameter('app.serverMsgSave');
                                $data['msg']=$serverSave;
                                $data['id']=$object->getId();
                        } else {
                                $object = $em->getRepository('ERPCRMBundle:CrmTipoCaso')->find($id);
                                $object->setNombre($name);
                                 $object->setEstado(true);
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
     * Retrieve campaign types
     *
     * @Route("/tipo/case/types/retrieve", name="admin_tipo_case_retrieve_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function retrieveaTipoCasejaxAction(Request $request)
    {
        try {
            $id=$request->get("param1");
            $response = new JsonResponse();
   
            $em = $this->getDoctrine()->getManager();
            $object = $em->getRepository('ERPCRMBundle:CrmTipoCaso')->find($id);

            if(count($object)!=0){
 
                $data['name']=$object->getNombre();
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
     * Retrieve campaign types
     *
     * @Route("/tipo/case/types/deletea", name="admin_tipo_case_delete_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function deleteTipoCasejaxAction(Request $request)
    {
       try {
            $ids=$request->get("param1");
            $response = new JsonResponse();
            $em = $this->getDoctrine()->getManager();
            foreach ($ids as $key => $id) {
                $object = $em->getRepository('ERPCRMBundle:CrmTipoCaso')->find($id);    
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
