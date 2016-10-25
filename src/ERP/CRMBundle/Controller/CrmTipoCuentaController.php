<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CrmTipoCuenta;
use ERP\CRMBundle\Form\CrmTipoCuentaType;

/**
 * CrmTipoCuenta controller.
 *
 * @Route("/admin/account-types")
 */
class CrmTipoCuentaController extends Controller
{
    /**
     * Lists all CrmTipoCuenta entities.
     *
     * @Route("/", name="admin_tipocuenta_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('crmtipocuenta/index.html.twig');
    }

    /**
     * Creates a new CrmTipoCuenta entity.
     *
     * @Route("/new", name="admin_tipocuenta_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $crmTipoCuentum = new CrmTipoCuenta();
        $form = $this->createForm('ERP\CRMBundle\Form\CrmTipoCuentaType', $crmTipoCuentum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crmTipoCuentum);
            $em->flush();

            return $this->redirectToRoute('admin_tipocuenta_show', array('id' => $crmTipoCuentum->getId()));
        }

        return $this->render('crmtipocuenta/new.html.twig', array(
            'crmTipoCuentum' => $crmTipoCuentum,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new CrmTipoCuenta entity.
     *
     * @Route("/register", name="admin_register_accounttype", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function registerAccountTypeAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $em = $this->getDoctrine()->getManager();
                //$exito = '1';
                $parameters = $request->request->all();
                $accounttypename = $parameters['name'];
                $id = $parameters['id'];
                
                $sql = "SELECT upper(acc.nombre) FROM ERPCRMBundle:CrmTipoCuenta acc "
                        . "WHERE upper(acc.nombre) LIKE upper(:busqueda) AND acc.estado = 1";
                
                $objectDuplicate = $em->createQuery($sql)
                                    ->setParameters(array('busqueda'=>"%".strtoupper($accounttypename)."%"))
                                    ->getResult(); 
                
                if (count($objectDuplicate)) {
                    $data['error'] = $this->getParameter('app.serverDuplicateName');
                    //$exito = '0';
                } else {
                    if ($id=='') {
                        $accounttype = new CrmTipoCuenta();
                        $accounttype->setNombre($accounttypename);
                        $accounttype->setEstado(1);

                        $em->persist($accounttype);
                        $em->flush();
                        
                        $serverSave = $this->getParameter('app.serverMsgSave');
                        $data['msg']=$serverSave;
                        $data['id']=$accounttype->getId();
                    } else {
                        $accounttype = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->find($id);
                        $accounttype->setNombre($accounttypename);
                        
                        $em->merge($accounttype);
                        $em->flush();
                        
                        $serverUpdate = $this->getParameter('app.serverMsgUpdate');
                        $data['msg']=$serverUpdate; 
                        $data['id']=$accounttype->getId();
                    }                                        
                }
                
                $response = new JsonResponse();
                $response->setData(array(
                                  //'exito'   => $exito,
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
     * Finds and displays a CrmTipoCuenta entity.
     *
     * @Route("/{id}", name="admin_tipocuenta_show")
     * @Method("GET")
     */
    public function showAction(CrmTipoCuenta $crmTipoCuentum)
    {
        $deleteForm = $this->createDeleteForm($crmTipoCuentum);

        return $this->render('crmtipocuenta/show.html.twig', array(
            'crmTipoCuentum' => $crmTipoCuentum,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CrmTipoCuenta entity.
     *
     * @Route("/{id}/edit", name="admin_tipocuenta_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CrmTipoCuenta $crmTipoCuentum)
    {
        $deleteForm = $this->createDeleteForm($crmTipoCuentum);
        $editForm = $this->createForm('ERP\CRMBundle\Form\CrmTipoCuentaType', $crmTipoCuentum);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crmTipoCuentum);
            $em->flush();

            return $this->redirectToRoute('admin_tipocuenta_edit', array('id' => $crmTipoCuentum->getId()));
        }

        return $this->render('crmtipocuenta/edit.html.twig', array(
            'crmTipoCuentum' => $crmTipoCuentum,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CrmTipoCuenta entity.
     *
     * @Route("/{id}", name="admin_tipocuenta_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CrmTipoCuenta $crmTipoCuentum)
    {
        $form = $this->createDeleteForm($crmTipoCuentum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($crmTipoCuentum);
            $em->flush();
        }

        return $this->redirectToRoute('admin_tipocuenta_index');
    }

    /**
     * Creates a form to delete a CrmTipoCuenta entity.
     *
     * @param CrmTipoCuenta $crmTipoCuentum The CrmTipoCuenta entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CrmTipoCuenta $crmTipoCuentum)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_tipocuenta_delete', array('id' => $crmTipoCuentum->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * 
     *
     * @Route("/account-types/data/as", name="admin_account_types_data", options={"expose"=true})
     */
    public function dataAccountTypesAction(Request $request)
    {        
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');
        
        $em = $this->getDoctrine()->getEntityManager();
        $rowsTotal = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->findAll();
        
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
            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', acc.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoAccountType fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',acc.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when acc.estado = 1 then 'Active' "
                    . "else 'Inactive' "
                    . "as state "
                    . "FROM ERPCRMBundle:CrmTipoCuenta acc "
                    . "WHERE acc.estado = 1 AND CONCAT(upper(acc.nombre), ' ' , upper(acc.estado)) LIKE upper(:busqueda) "
                    . "ORDER BY ".$orderByText." ".$orderDir;

            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                    ->getResult();

            $row['recordsFiltered']= count($row['data']);

            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', acc.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoAccountType fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',acc.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when acc.estado = 1 then 'Active' "
                    . "else 'Inactive' "
                    . "as state "
                    . "FROM ERPCRMBundle:CrmTipoCuenta acc "
                    . "WHERE acc.estado = 1 AND CONCAT(upper(acc.nombre),' ', upper(acc.estado)) LIKE upper(:busqueda) "
                    . "ORDER BY ".$orderByText." ".$orderDir;

            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();
              
        }
        else{
            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', acc.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoAccountType fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',acc.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when acc.estado = 1 then 'Active' "
                    . "else 'Inactive' "
                    . "as state "
                    . "FROM ERPCRMBundle:CrmTipoCuenta acc "
                    . "WHERE acc.estado = 1 ORDER BY ".$orderByText." ".$orderDir;
            
            $row['data'] = $em->createQuery($dql)
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();
        }
        
        return new Response(json_encode($row));
    }
    
    /**
     * Retrieve the account type
     *
     * @Route("/account-types/retrieve", name="admin_retrieve_accounttype", options={"expose"=true}))
     * @Method("POST")
     */
    public function retrieveAccountTypeAction(Request $request)
    {
        try {
            $id=$request->get("id");
            $response = new JsonResponse();
            
            $em = $this->getDoctrine()->getManager();
            $accountType = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->find($id);
            if(count($accountType)){
                //$em->merge($accountType);
                //$em->flush();    
                $data['name']=$accountType->getNombre();
                $data['id']=$accountType->getId();
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
     * Delete the account type
     *
     * @Route("/account-types/delete", name="admin_delete_accounttype",  options={"expose"=true}))
     * @Method("POST")
     */
    public function deleteAccountTypeAction(Request $request)
    {
        try {
            $ids=$request->get("param1");
            $response = new JsonResponse();
            
            $em = $this->getDoctrine()->getManager();
            foreach ($ids as $key => $id) {
                $object = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->find($id);    
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
