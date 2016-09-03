<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * List campaign
     *
     * @Route("/campaigns/data/list", name="admin_campaigns_data")
     */
    public function datacampaignsAction(Request $request)
    {
            try {
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
                                END AS state FROM ERPCRMBundle:CrmTipoCampania obj "
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
                                END AS state FROM ERPCRMBundle:CrmTipoCampania obj "
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
                                END AS state FROM ERPCRMBundle:CrmTipoCampania obj "
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
                            $row['data'][0]['name'] ='Server offline. CODE: '.$e->getErrorCode();
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
     * Save campaign types
     *
     * @Route("/campaign/types/save", name="admin_campaigntypes_save_ajax",  options={"expose"=true}))
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
                $sql = "SELECT upper(obj.nombre) FROM ERPCRMBundle:CrmTipoCampania obj "
                                            . "WHERE obj.estado=1 AND upper(obj.nombre) LIKE upper(:busqueda) "
                                            . "AND obj.estado=1";
                $objectDuplicate = $em->createQuery($sql)
                                        ->setParameters(array('busqueda'=>"%".strtoupper($name)."%"))
                                        ->getResult();   

                    
                   
                    if (count($objectDuplicate) && $id=='') {
                        $data['error'] = "Duplicate name";
                    } else {
                        if ($id=='') {
                                $object = new CrmTipoCampania();
                                $object->setNombre($name);
                                $object->setEstado(true);
                                $em->persist($object);
                                $em->flush();    
                                $data['msg']='¡Saved!';
                                $data['id']=$object->getId();
                        } else {
                                $object = $em->getRepository('ERPCRMBundle:CrmTipoCampania')->find($id);
                                $object->setNombre($name);
                                $em->merge($object);
                                $em->flush();    
                                $data['msg']='¡Updated!';
                                $data['id']=$object->getId();
                        }
                        
                        
                    }
                $response->setData($data); 
            } catch (\Exception $e) {
                    //var_dump($e);
                    if(method_exists($e,'getErrorCode')){
                        switch (intval($e->getErrorCode())){
                            case 2003: 
                                $data['error'] = 'Server offline. CODE: '.$e->getErrorCode();
                            break;
                            case 1062: 
                                $data['error'] = "Duplicate name! CODE: ".$e->getErrorCode();
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
     * @Route("/campaign/types/retrieve", name="admin_campaigntypes_retrieve_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function retrieveajaxAction(Request $request)
    {
        try {
            $id=$request->get("param1");
            $response = new JsonResponse();
            
            $em = $this->getDoctrine()->getManager();
            $object = $em->getRepository('ERPCRMBundle:CrmTipoCampania')->find($id);
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
                                $data['error'] = "Server offline";
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
     * @Route("/campaign/types/delete", name="admin_campaigntypes_delete_ajax",  options={"expose"=true}))
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
                $object = $em->getRepository('ERPCRMBundle:CrmTipoCampania')->find($id);    
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
        } catch (\Exception $e) {
            if(method_exists($e,'getErrorCode')){
                switch (intval($e->getErrorCode()))
                    {
                        case 2003: 
                            $data['error'] = "Server offline";
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
