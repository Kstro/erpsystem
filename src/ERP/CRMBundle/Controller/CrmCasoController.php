<?php

namespace ERP\CRMBundle\Controller;

use ERP\CRMBundle\Entity\CrmCaso;
use ERP\CRMBundle\Entity\CrmCasoCuenta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Crmcaso controller.
 *
 * @Route("/admin/crmcaso")
 */
class CrmCasoController extends Controller
{
    /**
     * Lists all crmCaso entities.
     *
     * @Route("/", name="crmcaso_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $crmCasos = $em->getRepository('ERPCRMBundle:CrmCaso')->findAll();
        $prioridad = $em->getRepository('ERPCRMBundle:CtlPrioridad')->findBy(array('estado'=>1), array('id' => 'DESC'));
        $tipo= $this->getDoctrine()->getRepository('ERPCRMBundle:CrmTipoCaso')->findBy(array('estado'=>1));
        $cuenta= $this->getDoctrine()->getRepository('ERPCRMBundle:CrmCuenta')->findBy(array('estado'=>1));
 
        $dql_user= "SELECT us.id As id , CONCAT(p.nombre,' ',p.apellido) as usuario "
                            . " FROM ERPCRMBundle:CtlUsuario us "
                            . " JOIN us.rol r  "
                            . " JOIN ERPCRMBundle:CtlPersona p WHERE p.id=us.persona AND r.id=2 ";
        $user = $em->createQuery($dql_user)->getArrayResult();
        
        return $this->render('crmcaso/index.html.twig', array(
            'crmCasos' => $crmCasos,
            'menuCasoA'=> 'a',
            'priori' => $prioridad,
            'tipo'=> $tipo,
            'user'=> $user,
            'cuenta'=>$cuenta
        ));
    }

    /**
     * Creates a new crmCaso entity.
     *
     * @Route("/new", name="crmcaso_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $crmCaso = new Crmcaso();
        $form = $this->createForm('ERP\CRMBundle\Form\CrmCasoType', $crmCaso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crmCaso);
            $em->flush($crmCaso);

            return $this->redirectToRoute('crmcaso_show', array('id' => $crmCaso->getId()));
        }

        return $this->render('crmcaso/new.html.twig', array(
            'crmCaso' => $crmCaso,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a crmCaso entity.
     *
     * @Route("/{id}", name="crmcaso_show")
     * @Method("GET")
     */
    public function showAction(CrmCaso $crmCaso)
    {
        $deleteForm = $this->createDeleteForm($crmCaso);

        return $this->render('crmcaso/show.html.twig', array(
            'crmCaso' => $crmCaso,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing crmCaso entity.
     *
     * @Route("/{id}/edit", name="crmcaso_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CrmCaso $crmCaso)
    {
        $deleteForm = $this->createDeleteForm($crmCaso);
        $editForm = $this->createForm('ERP\CRMBundle\Form\CrmCasoType', $crmCaso);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('crmcaso_edit', array('id' => $crmCaso->getId()));
        }

        return $this->render('crmcaso/edit.html.twig', array(
            'crmCaso' => $crmCaso,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a crmCaso entity.
     *
     * @Route("/{id}", name="crmcaso_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CrmCaso $crmCaso)
    {
        $form = $this->createDeleteForm($crmCaso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($crmCaso);
            $em->flush($crmCaso);
        }

        return $this->redirectToRoute('crmcaso_index');
    }

    /**
     * Creates a form to delete a crmCaso entity.
     *
     * @param CrmCaso $crmCaso The crmCaso entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CrmCaso $crmCaso)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('crmcaso_delete', array('id' => $crmCaso->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * List all case
     *
     * @Route("/case/data/list", name="admin_case_data",  options={"expose"=true}))
     */
    public function CaseDataAction(Request $request)
    {
              try {
                $start = $request->query->get('start');
                $draw = $request->query->get('draw');
                $longitud = $request->query->get('length');
                $busqueda = $request->query->get('search');
                
                $em = $this->getDoctrine()->getEntityManager();
             
                  $dql_case = "SELECT ca.nombre As nombre, CONCAT(p.nombre,' ',p.apellido)as usuario, pr.nombre As prioridad, ca.descripcion As descripcion  "
                            . " FROM ERPCRMBundle:CrmCaso ca "
                            . " JOIN ERPCRMBundle:CrmTipoCaso tc WHERE tc.id=ca.tipoCaso "
                            . " JOIN ERPCRMBundle:CtlPrioridad pr WHERE pr.id=ca.prioridad "
                            . " JOIN ERPCRMBundle:CtlUsuario us WHERE us.id=ca.usuarioAsignado "
                            . " JOIN ERPCRMBundle:CtlPersona p WHERE p.id=us.persona ";
                  
                    $rowsTotal = $em->createQuery($dql_case)->getArrayResult();

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
                    case 2:
                        $orderByText = "state";
                        break;
                }
                $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
                if($busqueda['value']!=''){   
                        $criterio=$busqueda['value'];

                        $sql = "SELECT CONCAT('<div id=\"',ca.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, 
                             CONCAT('<div style=\"text-align:left\">',ca.nombre,'</div>') as name, 
                             CONCAT('<div style=\"text-align:left\">',pr.nombre,'</div>') as prioridad, 
                           CASE 
                            WHEN ca.usuario_asignado  <> 'NULL' THEN CONCAT('<div style=\"text-align:left\">',p.nombre,' ',p.apellido,'</div>')
	                    ELSE CONCAT('<div style=\"text-align:left\">','No user','</div>') 
                            END as usuario,
                             CASE 
                                WHEN cc.caso <> 'NULL' THEN CONCAT('<div style=\"text-align:left\">',acc.nombre,'</div>')
                                ELSE CONCAT('<div style=\"text-align:left\">','No accoun','</div>') 
                            END as account,
                            CASE WHEN ca.estado = 0 THEN CONCAT('<div style=\"text-align:left\">','New','</div>') 
                            WHEN ca.estado = 1 THEN CONCAT('<div style=\"text-align:left\">','Assigned','</div>')
                             WHEN ca.estado = 2 THEN CONCAT('<div style=\"text-align:left\">','Closed','</div>') 
                             END as estado 
                             FROM crm_caso ca 
                             JOIN ctl_prioridad pr ON pr.id=ca.prioridad 
                             LEFT JOIN ctl_usuario us ON us.id=ca.usuario_asignado 
                             LEFT JOIN ctl_persona p ON p.id=us.persona "
                            . " LEFT JOIN crm_caso_cuenta cc ON cc.caso=ca.id "
                            . " LEFT JOIN crm_cuenta acc ON acc.id=cc.cuenta "
                            . " GROUP BY 1 HAVING upper(CONCAT(coalesce(usuario,''),'',name,'',prioridad,coalesce(account,''), estado)) LIKE upper('%".$criterio."%')"
                            . " ORDER BY ".$orderByText." ".$orderDir. " LIMIT ".$start.",".$longitud;
              
                        
                    $stm = $em->getConnection()->prepare($sql);
                    $stm->execute();
               
                    $row['data'] = $stm->fetchAll();
                    $row['recordsFiltered']= count($stm->fetchAll());
                }
                else{
                    //CONCAT('<div style=\"text-align:left\">',tc.nombre,'</div>') as tipo, 
                    //JOIN crm_tipo_caso tc ON tc.id=ca.tipo_caso 
                    $sql = "SELECT CONCAT('<div id=\"',ca.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, 
                             CONCAT('<div style=\"text-align:left\">',ca.nombre,'</div>') as name, 
                             CONCAT('<div style=\"text-align:left\">',pr.nombre,'</div>') as prioridad, 
                             CASE 
                                WHEN ca.usuario_asignado  <> 'NULL' THEN CONCAT('<div style=\"text-align:left\">',p.nombre,' ',p.apellido,'</div>')
                                ELSE CONCAT('<div style=\"text-align:left\">','No user','</div>') 
                            END as usuario,
                            CASE 
                                WHEN cc.caso <> 'NULL' THEN CONCAT('<div style=\"text-align:left\">',acc.nombre,'</div>')
                                ELSE CONCAT('<div style=\"text-align:left\">','No account','</div>') 
                            END as account,
                            CASE WHEN ca.estado = 0 THEN CONCAT('<div style=\"text-align:left\">','New','</div>') 
                            WHEN ca.estado = 1 THEN CONCAT('<div style=\"text-align:left\">','Assigned','</div>')
                             WHEN ca.estado = 2 THEN CONCAT('<div style=\"text-align:left\">','Closed','</div>') 
                            END as estado 
                             FROM crm_caso ca 
                             JOIN ctl_prioridad pr ON pr.id=ca.prioridad 
                             LEFT JOIN ctl_usuario us ON us.id=ca.usuario_asignado 
                             LEFT JOIN ctl_persona p ON p.id=us.persona 
                             LEFT JOIN crm_caso_cuenta cc ON cc.caso=ca.id
                             LEFT JOIN crm_cuenta acc ON acc.id=cc.cuenta 
                             ORDER BY ".$orderByText." ".$orderDir. " LIMIT ".$start.",".$longitud;
                    $stm = $em->getConnection()->prepare($sql);
                    $stm->execute();
                    $row['data'] = $stm->fetchAll();
                    
                   /* $row['data'] = $em->createQuery($sql)
                            ->setFirstResult($start)
                            ->setMaxResults($longitud)
                            ->getResult(); */
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
     * @Route("/case/save", name="admin_case_save_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function saveaCasejaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
             $response = new JsonResponse();
            try {
                    $em = $this->getDoctrine()->getManager();
                   
                $name=$request->get("nombre");
                
                $id=$request->get("id");
                $descripcion=$request->get("txtDescripcion");
                $prioridad = $em->getRepository('ERPCRMBundle:CtlPrioridad')->find($request->get('dpPrioridad'));
                $tipo= $this->getDoctrine()->getRepository('ERPCRMBundle:CrmTipoCaso')->find($request->get('dpTipo'));
                $user= $this->getDoctrine()->getRepository('ERPCRMBundle:CtlUsuario')->find($request->get('dpUser'));
                $cuenta= $this->getDoctrine()->getRepository('ERPCRMBundle:CrmCuenta')->find($request->get('dpCuenta'));
            
                $sql = "SELECT upper(obj.nombre) FROM ERPCRMBundle:CrmCaso obj "
                                            . "WHERE  upper(obj.nombre) LIKE upper(:busqueda) ";
                $objectDuplicate = $em->createQuery($sql)
                                        ->setParameters(array('busqueda'=>"".strtoupper($name).""))
                                        ->getResult();   

                    if (count($objectDuplicate) && $id=='') {
                        $serverDuplicateName = $this->getParameter('app.serverDuplicateName');
                        $data['error'] = $serverDuplicateName;
                    } else {
                        if ($id=='') 
                        {
                             $em->getConnection()->beginTransaction();
                                $object = new CrmCaso();
                                
                                $object->setNombre($name);
                                $object->setTipoCaso($tipo);
                                $object->setPrioridad($prioridad);
                                $object->setUsuarioAsignado($user);
                                $object->setDescripcion($descripcion);
                             if($user!=null)
                             {
                                 $object->setEstado(1);
                             }
                                else
                             {
                                 $object->setEstado(0);
                             }     
                                $em->persist($object);
                                $em->flush();    
                                
                                  if($cuenta!=null)
                             {
                                 $objectCasoCuenta=new CrmCasoCuenta();
                                 $objectCasoCuenta->setCuenta($cuenta);
                                 $objectCasoCuenta->setCaso($object);
                                $em->persist($objectCasoCuenta);
                                $em->flush();
                             }
                                      
                                $em->getConnection()->commit();
                                $em->close();
                                $serverSave = $this->getParameter('app.serverMsgSave');
                                $data['msg']=$serverSave;
                                $data['id']=$object->getId();
                        } else {
                             $em->getConnection()->beginTransaction();
                                $object = $em->getRepository('ERPCRMBundle:CrmCaso')->find($id);
                                $object->setNombre($name);
                                $object->setTipoCaso($tipo);
                                $object->setPrioridad($prioridad);
                                $object->setUsuarioAsignado($user);
                                $object->setDescripcion($descripcion);
                                 if($user!=null)
                             {
                                 $object->setEstado(1);
                             }
                             else
                             {
                                 $object->setEstado(0);
                             }
                                $em->merge($object);
                                $em->flush();   
                                
                                 if($cuenta!=null)
                             {
                                $CasoCuenta= $em->getRepository('ERPCRMBundle:CrmCasoCuenta')->findByCaso($id);
                                 $objectCasoCuenta= $em->getRepository('ERPCRMBundle:CrmCasoCuenta')->find($CasoCuenta[0]->getId());
                                 $objectCasoCuenta->setCuenta($cuenta);
                                 $objectCasoCuenta->setCaso($object);
                                $em->merge($objectCasoCuenta);
                                $em->flush();
                             }
                                 $em->getConnection()->commit();
                                $em->close();
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
                    
                $em->getConnection()->rollback();
                $em->close();
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
     * @Route("/case/retrieve", name="admin_case_retrieve_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function retrieveaCasejaxAction(Request $request)
    {
        try {
            $id=$request->get("param1");
            
            $response = new JsonResponse();
   
            $em = $this->getDoctrine()->getManager();
            $object = $em->getRepository('ERPCRMBundle:CrmCaso')->find($id);
          //  $CasoCuenta= $em->getRepository('ERPCRMBundle:CrmCasoCuenta')->findByCaso($id);
         //   echo $CasoCuenta;
            
          // $data['cuenta']=$objectCuenta->getNombre();
            if(count($object)!=0){
                $data['name']=$object->getNombre();
                $data['id']=$object->getId();
                $data['Tipo']=$object->getTipoCaso()->getId();
                $data['Prioridad']=$object->getPrioridad()->getId();
                
          
            // $data['Cuenta']=$objectCasoCuenta[0]->getCuenta()->getNombre();
    
                if($em->getRepository('ERPCRMBundle:CrmCasoCuenta')->findByCaso($id)!=null)
                {
              
                       $data['IdCuenta']=$em->getRepository('ERPCRMBundle:CrmCasoCuenta')->findByCaso($id)[0]->getCuenta()->getId();
                } 
                
                if($object->getUsuarioAsignado()!=null)
                {
                $data['User']=$object->getUsuarioAsignado()->getId();
                }
                $data['Descripcion']=$object->getDescripcion();
            }
            else{
                $data['error']="Error";
            }    
            $response->setData($data); 
            
        } catch (\Exception $e) {
            var_dump($e);
            exit();
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
     * @Route("/case/deletea/data/", name="case_delete_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function deleteCasejaxAction(Request $request)
    {
       try {
            $ids=$request->get("param1");
            
            $response = new JsonResponse();
            $em = $this->getDoctrine()->getManager();
            foreach ($ids as $key => $id) {
                $object = $em->getRepository('ERPCRMBundle:CrmCaso')->find($id);    
                if(count($object)){
                    $object->setEstado(2);
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
