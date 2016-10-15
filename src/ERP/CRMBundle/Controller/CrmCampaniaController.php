<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CrmCampania;
use ERP\CRMBundle\Entity\CrmPersonalCampania;
use ERP\CRMBundle\Form\CrmCampaniaType;

/**
 * CrmCampania controller.
 *
 * @Route("/admin/campaigns")
 */
class CrmCampaniaController extends Controller
{
    /**
     * Lists all CrmCampania entities.
     *
     * @Route("/", name="admin_campaigns_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $campanias = $em->getRepository('ERPCRMBundle:CrmTipoCampania')->findBy(array('estado'=>1));
        $personas = $em->getRepository('ERPCRMBundle:CtlUsuario')->findBy(array('estado'=>1));
        // $personas = $em->getRepository('ERPCRMBundle:CtlUsuari')->findAll();
        // $crmTipoCampanias = $em->getRepository('ERPCRMBundle:CrmTipoCampania')->findAll();

        return $this->render('crmcampania/index.html.twig', array(
            'campanias' => $campanias,
            'personas' => $personas,
            // 'crmTipoCampanias' => $crmTipoCampanias,
            'menuCampaniaA' => true,
        ));
    }

    /**
     * Creates a new CrmCampania entity.
     *
     * @Route("/new", name="admin_campaigns_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $crmCampanium = new CrmCampania();
        $form = $this->createForm('ERP\CRMBundle\Form\CrmCampaniaType', $crmCampanium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crmCampanium);
            $em->flush();

            return $this->redirectToRoute('admin_campaigns_show', array('id' => $crmCampanium->getId()));
        }

        return $this->render('crmcampania/new.html.twig', array(
            'crmCampanium' => $crmCampanium,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CrmCampania entity.
     *
     * @Route("/{id}", name="admin_campaigns_show")
     * @Method("GET")
     */
    public function showAction(CrmCampania $crmCampanium)
    {
        $deleteForm = $this->createDeleteForm($crmCampanium);

        return $this->render('crmcampania/show.html.twig', array(
            'crmCampanium' => $crmCampanium,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CrmCampania entity.
     *
     * @Route("/{id}/edit", name="admin_campaigns_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CrmCampania $crmCampanium)
    {
        $deleteForm = $this->createDeleteForm($crmCampanium);
        $editForm = $this->createForm('ERP\CRMBundle\Form\CrmCampaniaType', $crmCampanium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crmCampanium);
            $em->flush();

            return $this->redirectToRoute('admin_campaigns_edit', array('id' => $crmCampanium->getId()));
        }

        return $this->render('crmcampania/edit.html.twig', array(
            'crmCampanium' => $crmCampanium,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CrmCampania entity.
     *
     * @Route("/{id}", name="admin_campaigns_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CrmCampania $crmCampanium)
    {
        $form = $this->createDeleteForm($crmCampanium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($crmCampanium);
            $em->flush();
        }

        return $this->redirectToRoute('admin_campaigns_index');
    }

    /**
     * Creates a form to delete a CrmCampania entity.
     *
     * @param CrmCampania $crmCampanium The CrmCampania entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CrmCampania $crmCampanium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_campaigns_delete', array('id' => $crmCampanium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }











    /////Campaigns

    /**
     * List all campaigns
     *
     * @Route("/campaigns/data/list", name="admin_campaign_data")
     */
    public function datacampaignsAction(Request $request)
    {
            try {
                $start = $request->query->get('start');
                $draw = $request->query->get('draw');
                $longitud = $request->query->get('length');
                $busqueda = $request->query->get('search');
                
                $em = $this->getDoctrine()->getEntityManager();
                
                $sql = "SELECT cam.id as id FROM ERPCRMBundle:CrmCampania cam "
                            ."JOIN cam.tipoCampania tc";
                            //. " WHERE tc.id=1";
                $rowsTotal = $em->createQuery($sql)
                            ->getResult();
               
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
                        $orderByText = "type";
                        break;
                    case 3:
                        $orderByText = "status";
                        break;
                    case 4:
                        $orderByText = "dateReg";
                        break;
                }
                $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
                if($busqueda['value']!=''){
                            $sql = "SELECT CONCAT('<div id=\"',cam.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',cam.nombre,'</div>') as name, cam.id, CONCAT('<div style=\"text-align:left\">',cam.estado_campania,'</div>') as status, CONCAT('<div style=\"text-align:left\">',cam.fecha_registro,'</div>') as dateReg, CONCAT('<div style=\"text-align:left\">',tc.nombre,'</div>') as type
                                        FROM crm_campania cam
                                        INNER JOIN crm_tipo_campania tc on(cam.tipo_campania=tc.id)
                                        GROUP BY 1
                                        HAVING CONCAT(name,' ',type,' ',status,' ',dateReg) LIKE upper('%".$busqueda['value']."%') ORDER BY ". $orderByText." ".$orderDir." LIMIT " . $start . "," . $longitud;
                            $stmt = $em->getConnection()->prepare($sql);
                            $stmt->execute();
                            $row['data'] = $stmt->fetchAll();
                            $row['recordsFiltered']= count($row['data']);
                            $sql = "SELECT CONCAT('<div id=\"',cam.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',cam.nombre,'</div>') as name, cam.id, CONCAT('<div style=\"text-align:left\">',cam.estado_campania,'</div>') as status, CONCAT('<div style=\"text-align:left\">',cam.fecha_registro,'</div>') as dateReg, CONCAT('<div style=\"text-align:left\">',tc.nombre,'</div>') as type
                                        FROM crm_campania cam
                                        INNER JOIN crm_tipo_campania tc on(cam.tipo_campania=tc.id)
                                        GROUP BY 1
                                        HAVING CONCAT(name,' ',type,' ',status,' ',dateReg) LIKE upper('%".$busqueda['value']."%') ORDER BY ". $orderByText." ".$orderDir." LIMIT " . $start . "," . $longitud;
                            $stmt = $em->getConnection()->prepare($sql);
                            $stmt->execute();
                            $row['data'] = $stmt->fetchAll();
                }
                else{
                    $sql = "SELECT CONCAT('<div id=\"',cam.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',cam.nombre,'</div>') as name, cam.id, CONCAT('<div style=\"text-align:left\">',cam.estado_campania,'</div>') as status, CONCAT('<div style=\"text-align:left\">',cam.fecha_registro,'</div>') as dateReg, CONCAT('<div style=\"text-align:left\">',tc.nombre,'</div>') as type
                                        FROM crm_campania cam
                                        INNER JOIN crm_tipo_campania tc on(cam.tipo_campania=tc.id)
                                        GROUP BY 1
                                        ORDER BY ". $orderByText." ".$orderDir;
                            $stmt = $em->getConnection()->prepare($sql);
                            $stmt->execute();
                            $row['data'] = $stmt->fetchAll();
                }
                return new Response(json_encode($row));
            } catch (\Exception $e) {  
                // var_dump($e);
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

    //////Fin de campania










    /**
     * Save level of campaigns satisfaction
     *
     * @Route("/campaigns/satisfaction/save", name="admin_campaigns_save_ajax",  options={"expose"=true}))
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
                $id=$_POST['txtId'];

                $nombreCampaign=$_POST['txtName'];
                $tipoCampania=$_POST['tipoCampania'];
                $responsableCampania=$_POST['responsableCampania'];
                $masResponsableCampania=$_POST['responsable'];
                $estadoCampania=$_POST['estadoCampania'];
                $inicioCampania=$_POST['inicioCampania'];
                $finCampania=$_POST['finCampania'];
                $descripcion=$_POST['descripcionCampania'];
                // var_dump($_POST );
                //var_dump($idST );
                // var_dump($responsableCampania);
                // var_dump($masResponsableCampania);
                // die();
                // var_dump($id);
                // var_dump($nombreCampaign);
                $em = $this->getDoctrine()->getEntityManager();
                $tipoCampaniaObj = $em->getRepository('ERPCRMBundle:CrmTipoCampania')->find($tipoCampania);
                // var_dump($tipoCampaniaObj);
                // die();

                $em = $this->getDoctrine()->getManager();
                $sql = "SELECT upper(obj.nombre) FROM ERPCRMBundle:CrmCampania obj "
                                            . "WHERE obj.estado=1 AND upper(obj.nombre) LIKE upper(:busqueda) "
                                            . "AND obj.estado=1";
                $objectDuplicate = $em->createQuery($sql)
                                        ->setParameters(array('busqueda'=>"".strtoupper($nombreCampaign).""))
                                        ->getResult();

                    if (count($objectDuplicate) && $id=='') {
                        $serverDuplicateName = $this->getParameter('app.serverDuplicateName');
                        $data['error'] = $serverDuplicateName;
                    } else {
                        if ($id=='') {
                                // var_dump('sadcsdacdc');
                                $object = new CrmCampania();
                                
                                $object->setNombre($nombreCampaign);
                                $object->setEstado(1);
                                $object->setEstadoCampania($estadoCampania);
                                $object->setFechaRegistro(new \Datetime('now'));
                                $object->setFechaInicio(new \Datetime($inicioCampania));
                                $object->setFechaFin(new \Datetime($finCampania));
                                $object->setDescripcion($descripcion);
                                $object->setTipoCampania($tipoCampaniaObj);

                                $em->persist($object);
                                $em->flush();    

                                $responsableCampaniaObj = $em->getRepository('ERPCRMBundle:CtlPersona')->find($id);
                                if (count($responsableCampaniaObj)!=0) {
                                    $personaAsignada = new CrmPersonalCampania();
                                    $personaAsignada->setEstado(1);
                                    $personaAsignada->setPersonaAsignada($responsableCampaniaObj);
                                    $personaAsignada->setCampania($object);
                                    $em->persist($personaAsignada);
                                    $em->flush();
                                }



                                
                                //Tabla crmAsignacionCampania
                                foreach ($masResponsableCampania as $key => $per) {
                                    //Tabla crmAsignacionActividad
                                    $responsableUsuarioObj = $em->getRepository('ERPCRMBundle:CtlUsuario')->find($per);
                                    $personaAsignada = new CrmPersonalCampania();
                                    $personaAsignada->setEstado(1);
                                    $personaAsignada->setPersonaAsignada($responsableCampaniaObj->getPersona());
                                    $personaAsignada->setCampania($object);
                                    $em->persist($personaAsignada);
                                    $em->flush();
                                }



                                $serverSave = $this->getParameter('app.serverMsgSave');
                                $data['msg']=$serverSave;
                                $data['id']=$object->getId();
                        } else {
                                $object = $em->getRepository('ERPCRMBundle:CrmCampania')->find($id);
                                // var_dump($id);
                                //$object->setFechaRegistroE(new \Datetime('now'));
                                $object->setNombre($nombreCampaign);
                                //$object->setEstado($estadoCampania);
                                $object->setEstadoCampania($estadoCampania);
                                $object->setFechaInicio(new \Datetime($inicioCampania));
                                $object->setFechaFin(new \Datetime($finCampania));
                                $object->setDescripcion($descripcion);
                                $object->setTipoCampania($tipoCampaniaObj);

                                $personaAsignada = $em->getRepository('ERPCRMBundle:CrmPersonalCampania')->findBy(array('campania'=>$id));
                                foreach ($personaAsignada as $key => $value) {
                                    $em->remove($value);
                                    $em->flush();
                                }

                                // die();

                                $responsableCampaniaObj = $em->getRepository('ERPCRMBundle:CtlPersona')->find($id);
                                if (count($responsableCampaniaObj)!=0) {
                                    $personaAsignada2 = new CrmPersonalCampania();
                                    $personaAsignada2->setEstado(1);
                                    $personaAsignada2->setPersonaAsignada($responsableCampaniaObj);
                                    $personaAsignada2->setCampania($object);
                                    $em->persist($personaAsignada2);
                                    $em->flush();
                                }


                                //Tabla crmAsignacionCampania
                                foreach ($masResponsableCampania as $key => $row) {
                                    //Tabla crmAsignacionActividad
                                    $responsableUsuarioObj = $em->getRepository('ERPCRMBundle:CtlUsuario')->find($row);
                                    $personaAsignada = new CrmPersonalCampania();
                                    $personaAsignada->setEstado(1);
                                    $personaAsignada->setPersonaAsignada($responsableUsuarioObj->getPersona());
                                    $personaAsignada->setCampania($object);
                                    $em->persist($personaAsignada);
                                    $em->flush();
                                }


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
     * Retrieve the account type
     *
     * @Route("/campaign/retrieve", name="admin_retrieve_campaign_ajax", options={"expose"=true}))
     * @Method("POST")
     */
    public function retrievecampaignAction(Request $request)
    {
        try {
            $id=$request->get("param1");
            // var_dump($id);
            $response = new JsonResponse();
            
            $em = $this->getDoctrine()->getManager();
            $campaign = $em->getRepository('ERPCRMBundle:CrmCampania')->find($id);
            if(count($campaign)!=0){
                                                
                $data['id']=$campaign->getId();
                $data['name']=$campaign->getNombre();
                $data['tipoCampania']=$campaign->getTipoCampania()->getId();               
                $data['estadoCampania']=$campaign->getEstadoCampania();
                $data['fechaInicio']=$campaign->getFechaInicio()->format('Y-m-d H:i');
                $data['fechaFin']=$campaign->getFechaFin()->format('Y-m-d H:i');
                $data['descripcion']=$campaign->getDescripcion();
                
                /////Persona
                $personaAsignada = $em->getRepository('ERPCRMBundle:CrmPersonalCampania')->findBy(array('campania'=>$campaign->getId()));

                if (count($personaAsignada)!=0) {
                    $data['personaId']=$personaAsignada[0]->getPersonaAsignada()->getId();
                    $data['personaNombre']=$personaAsignada[0]->getPersonaAsignada()->getNombre()." ".$personaAsignada[0]->getPersonaAsignada()->getApellido();

                    $idsArray=array();
                    $nombresArray=array();

                    foreach ($personaAsignada as $key => $row) {
                        if ($key!=0) {
                            array_push($idsArray, $row->getPersonaAsignada()->getId());   
                            array_push($nombresArray, $row->getPersonaAsignada()->getNombre()." ".$row->getPersonaAsignada()->getApellido());   
                        }
                    }
                    
                    $data['otrosId']=$idsArray;
                    $data['otrosNombre']=$nombresArray;


                } else {
                    $data['personaId']=0;
                    $data['personaNombre']='';
                }
                

                
                // var_dump($data);
                // die();

                

            }
            else{
                $data['error']="Error";
                // var_dump($campaign);
            }
                        
            $response->setData($data); 
            
        } catch (\Exception $e) {
            var_dump($e);
            if(method_exists($e,'getErrorCode')){ 
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
        }
        
        return $response;
        
    }










}
