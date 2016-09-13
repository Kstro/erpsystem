<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CtlFuente;
use ERP\CRMBundle\Form\CtlFuenteType;

/**
 * CtlFuente controller.
 *
 * @Route("/admin/origin-source")
 */
class CtlFuenteController extends Controller
{
    /**
     * Lists all CtlFuente entities.
     *
     * @Route("/", name="admin_origin-source_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ctlFuentes = $em->getRepository('ERPCRMBundle:CtlFuente')->findAll();

        return $this->render('ctlfuente/index.html.twig', array(
            'ctlFuentes' => $ctlFuentes,
        ));
    }

    /**
     * Creates a new CtlFuente entity.
     *
     * @Route("/new", name="admin_origin-source_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ctlFuente = new CtlFuente();
        $form = $this->createForm('ERP\CRMBundle\Form\CtlFuenteType', $ctlFuente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlFuente);
            $em->flush();

            return $this->redirectToRoute('admin_origin-source_show', array('id' => $ctlFuente->getId()));
        }

        return $this->render('ctlfuente/new.html.twig', array(
            'ctlFuente' => $ctlFuente,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * Creates a new CtlFuente entity.
     *
     * @Route("/register", name="admin_register_origin_source", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function registerOriginSourceAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $em = $this->getDoctrine()->getManager();
                //$exito = '1';
                $parameters = $request->request->all();
                $originsourcename = $parameters['name'];
                $id = $parameters['id'];
                
                $sql = "SELECT upper(sou.nombre) FROM ERPCRMBundle:CtlFuente sou "
                        . "WHERE upper(sou.nombre) LIKE upper(:busqueda) AND sou.estado = 1";
                
                $objectDuplicate = $em->createQuery($sql)
                                     ->setParameters(array('busqueda'=>"%".strtoupper($originsourcename)."%"))
                                     ->getResult(); 
                
                if (count($objectDuplicate)) {
                    $data['error'] = $this->getParameter('app.serverDuplicateName');
                    //$exito = '0';
                } else {
                    if ($id=='') {
                        $originsource = new CtlFuente();
                        $originsource->setNombre($originsourcename);
                        $originsource->setEstado(1);

                        $em->persist($originsource);
                        $em->flush();
                        
                        $serverSave = $this->getParameter('app.serverMsgSave');
                        $data['msg']=$serverSave;
                        $data['id']=$originsource->getId();
                    } else {
                        $originsource = $em->getRepository('ERPCRMBundle:CtlFuente')->find($id);
                        $originsource->setNombre($originsourcename);
                        
                        $em->merge($originsource);
                        $em->flush();
                        
                        $serverUpdate = $this->getParameter('app.serverMsgUpdate');
                        $data['msg']=$serverUpdate; 
                        $data['id']=$originsource->getId();
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
     * Finds and displays a CtlFuente entity.
     *
     * @Route("/{id}", name="admin_origin-source_show")
     * @Method("GET")
     */
    public function showAction(CtlFuente $ctlFuente)
    {
        $deleteForm = $this->createDeleteForm($ctlFuente);

        return $this->render('ctlfuente/show.html.twig', array(
            'ctlFuente' => $ctlFuente,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CtlFuente entity.
     *
     * @Route("/{id}/edit", name="admin_origin-source_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CtlFuente $ctlFuente)
    {
        $deleteForm = $this->createDeleteForm($ctlFuente);
        $editForm = $this->createForm('ERP\CRMBundle\Form\CtlFuenteType', $ctlFuente);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlFuente);
            $em->flush();

            return $this->redirectToRoute('admin_origin-source_edit', array('id' => $ctlFuente->getId()));
        }

        return $this->render('ctlfuente/edit.html.twig', array(
            'ctlFuente' => $ctlFuente,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CtlFuente entity.
     *
     * @Route("/{id}", name="admin_origin-source_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CtlFuente $ctlFuente)
    {
        $form = $this->createDeleteForm($ctlFuente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ctlFuente);
            $em->flush();
        }

        return $this->redirectToRoute('admin_origin-source_index');
    }

    /**
     * Creates a form to delete a CtlFuente entity.
     *
     * @param CtlFuente $ctlFuente The CtlFuente entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CtlFuente $ctlFuente)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_origin-source_delete', array('id' => $ctlFuente->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * 
     *
     * @Route("/origin-sources/data/as", name="admin_origin_sources_data", options={"expose"=true})
     */
    public function dataOriginSourcesAction(Request $request)
    {        
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');
        
        $em = $this->getDoctrine()->getEntityManager();
        $rowsTotal = $em->getRepository('ERPCRMBundle:CtlFuente')->findAll();
        
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
            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', sou.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOriginSource fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',sou.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when sou.estado = 1 then 'Active' "
                    . "else 'Inactive' "
                    . "as state "
                    . "FROM ERPCRMBundle:CtlFuente sou "
                    . "WHERE sou.estado = 1 AND CONCAT(upper(sou.nombre), ' ' , upper(sou.estado)) LIKE upper(:busqueda) "
                    . "ORDER BY ".$orderByText." ".$orderDir;

            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                    ->getResult();

            $row['recordsFiltered']= count($row['data']);

            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', sou.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOriginSource fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',sou.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when sou.estado = 1 then 'Active' "
                    . "else 'Inactive' "
                    . "as state "
                    . "FROM ERPCRMBundle:CtlFuente sou "
                    . "WHERE sou.estado = 1 AND CONCAT(upper(sou.nombre),' ', upper(sou.estado)) LIKE upper(:busqueda) "
                    . "ORDER BY ".$orderByText." ".$orderDir;

            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();              
        }
        else{
            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', sou.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOriginSource fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',sou.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
                    . "case "
                    . "when sou.estado = 1 then 'Active' "
                    . "else 'Inactive' "
                    . "as state "
                    . "FROM ERPCRMBundle:CtlFuente sou "
                    . "WHERE sou.estado = 1 ORDER BY ".$orderByText." ".$orderDir;
            
            $row['data'] = $em->createQuery($dql)
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();
        }
        
        return new Response(json_encode($row));
    }
    
    /**
     * Retrieve the origin source
     *
     * @Route("/origin-sources/retrieve", name="admin_retrieve_originsource", options={"expose"=true}))
     * @Method("POST")
     */
    public function retrieveOriginSourcesAction(Request $request)
    {
        try {
            $id=$request->get("id");
            $response = new JsonResponse();
            
            $em = $this->getDoctrine()->getManager();
            $originsource = $em->getRepository('ERPCRMBundle:CtlFuente')->find($id);
            if(count($originsource)){
                
                $data['name']=$originsource->getNombre();
                $data['id']=$originsource->getId();
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
     * Delete the origin source
     *
     * @Route("/origin-source/delete", name="admin_delete_origin_source",  options={"expose"=true}))
     * @Method("POST")
     */
    public function deleteOriginSourcesAction(Request $request)
    {
        try {
            $ids=$request->get("param1");
            $response = new JsonResponse();
            
            $em = $this->getDoctrine()->getManager();
            foreach ($ids as $key => $id) {
                $object = $em->getRepository('ERPCRMBundle:CtlFuente')->find($id);    
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
