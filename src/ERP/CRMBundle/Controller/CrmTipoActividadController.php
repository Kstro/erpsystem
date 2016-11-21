<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CrmTipoActividad;
use ERP\CRMBundle\Form\CrmTipoActividadType;

/**
 * CrmTipoActividad controller.
 *
 * @Route("/admin/activities-types")
 */
class CrmTipoActividadController extends Controller
{
    /**
     * Lists all CrmTipoActividad entities.
     *
     * @Route("/", name="admin_activities-types_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $campanias = $em->getRepository('ERPCRMBundle:CrmTipoCampania')->findBy(array('estado'=>1));
        $personas = $em->getRepository('ERPCRMBundle:CtlUsuario')->findBy(array('estado'=>1));
        // $personas = $em->getRepository('ERPCRMBundle:CtlUsuari')->findAll();
        // $crmTipoCampanias = $em->getRepository('ERPCRMBundle:CrmTipoCampania')->findAll();

        return $this->render('crmtipoactividad/index.html.twig', array(
            'campanias' => $campanias,
            'personas' => $personas,
            // 'crmTipoCampanias' => $crmTipoCampanias,
            'menuCampaniaA' => true,
        ));
    }

    /**
     * Creates a new CrmTipoActividad entity.
     *
     * @Route("/new", name="admin_activities-types_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $crmTipoActividad = new CrmTipoActividad();
        $form = $this->createForm('ERP\CRMBundle\Form\CrmTipoActividadType', $crmTipoActividad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crmTipoActividad);
            $em->flush();

            return $this->redirectToRoute('admin_activities-types_show', array('id' => $crmTipoActividad->getId()));
        }

        return $this->render('crmtipoactividad/new.html.twig', array(
            'crmTipoActividad' => $crmTipoActividad,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CrmTipoActividad entity.
     *
     * @Route("/{id}", name="admin_activities-types_show")
     * @Method("GET")
     */
    public function showAction(CrmTipoActividad $crmTipoActividad)
    {
        $deleteForm = $this->createDeleteForm($crmTipoActividad);

        return $this->render('crmtipoactividad/show.html.twig', array(
            'crmTipoActividad' => $crmTipoActividad,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CrmTipoActividad entity.
     *
     * @Route("/{id}/edit", name="admin_activities-types_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CrmTipoActividad $crmTipoActividad)
    {
        $deleteForm = $this->createDeleteForm($crmTipoActividad);
        $editForm = $this->createForm('ERP\CRMBundle\Form\CrmTipoActividadType', $crmTipoActividad);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crmTipoActividad);
            $em->flush();

            return $this->redirectToRoute('admin_activities-types_edit', array('id' => $crmTipoActividad->getId()));
        }

        return $this->render('crmtipoactividad/edit.html.twig', array(
            'crmTipoActividad' => $crmTipoActividad,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CrmTipoActividad entity.
     *
     * @Route("/{id}", name="admin_activities-types_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CrmTipoActividad $crmTipoActividad)
    {
        $form = $this->createDeleteForm($crmTipoActividad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($crmTipoActividad);
            $em->flush();
        }

        return $this->redirectToRoute('admin_activities-types_index');
    }

    /**
     * Creates a form to delete a CrmTipoActividad entity.
     *
     * @param CrmTipoActividad $crmTipoActividad The CrmTipoActividad entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CrmTipoActividad $crmTipoActividad)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_activities-types_delete', array('id' => $crmTipoActividad->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    
    
    
    
    
    
    
    /////Campaigns

    /**
     * List all campaigns
     *
     * @Route("/campaigns/data/list", name="admin_types_activities_data")
     */
    public function datacampaignsAction(Request $request)
    {
            try {
                $start = $request->query->get('start');
                $draw = $request->query->get('draw');
                $longitud = $request->query->get('length');
                $busqueda = $request->query->get('search');
                $timeZone = $this->get('time_zone')->getTimeZone();
                date_default_timezone_set($timeZone->getNombre());
                $em = $this->getDoctrine()->getEntityManager();
                
                $sql = "SELECT act.id as id FROM ERPCRMBundle:CrmTipoActividad act "
                            /*."JOIN cam.tipoCampania tc"*/;
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
                        $orderByText = "icono";
                        break;
                }
                $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
                if($busqueda['value']!=''){
                    $sql = "SELECT CASE WHEN act.id>3 THEN CONCAT('<div id=\"',act.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') ELSE CONCAT('<div id=\"',act.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"hidden\" type=\"checkbox\"></div>') END as chk, CONCAT('<div style=\"text-align:left\">',act.nombre,'</div>') as name, act.id, CONCAT('<div style=\"text-align:left\">','<i class=\"fa ',act.icono_clase,'\"> </i> </div>') as icono
                                FROM crm_tipo_actividad act
                                HAVING CONCAT(name,' ',icono) LIKE upper('%".$busqueda['value']."%') ORDER BY ". $orderByText." ".$orderDir." LIMIT " . $start . "," . $longitud;
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    $row['data'] = $stmt->fetchAll();
                    $row['recordsFiltered']= count($row['data']);
                    $sql = "SELECT CASE WHEN act.id>3 THEN CONCAT('<div id=\"',act.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') ELSE CONCAT('<div id=\"',act.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"hidden\" type=\"checkbox\"></div>') END as chk, CONCAT('<div style=\"text-align:left\">',act.nombre,'</div>') as name, act.id, CONCAT('<div style=\"text-align:left\">','<i class=\"fa ',act.icono_clase,'\"> </i> </div>') as icono
                                FROM crm_tipo_actividad act
                                HAVING CONCAT(name,' ',icono) LIKE upper('%".$busqueda['value']."%') ORDER BY ". $orderByText." ".$orderDir." LIMIT " . $start . "," . $longitud;
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    $row['data'] = $stmt->fetchAll();
                }
                else{
                    $sql = "SELECT CASE WHEN act.id>3 THEN CONCAT('<div id=\"',act.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') ELSE CONCAT('<div id=\"',act.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"hidden\" type=\"checkbox\"></div>') END as chk, CONCAT('<div style=\"text-align:left\">',act.nombre,'</div>') as name, act.id, CONCAT('<div style=\"text-align:left\">','<i class=\"fa ',act.icono_clase,'\"> </i> </div>') as icono
                                        FROM crm_tipo_actividad act
                                        
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
                        $row['error']=$e->getMessage();
                }
                return new Response(json_encode($row));            
        }   
    }

    //////Fin de campania
    
    
    
    
    
    /////Save tipo actividad
    /**
     * Save 
     *
     * @Route("/activitiy/types/save", name="admin_activity_type_save_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function saveajaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){

        
            try {
                
                
                $id=$request->get("param1");
                $name=$request->get("param2");
                $icon=$request->get("param3");
                $response = new JsonResponse();
                // var_dump($name);
                // var_dump($probability);
                // die();

                $em = $this->getDoctrine()->getManager();
                $sql = "SELECT upper(obj.nombre) FROM ERPCRMBundle:CrmTipoActividad obj "
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
                                $object = new CrmTipoActividad();
                                $object->setNombre($name);
                                $object->setIcono($icon);
                                $object->setEstado(true);
                                $em->persist($object);
                                $em->flush();    
                                $serverSave = $this->getParameter('app.serverMsgSave');
                                $data['msg']=$serverSave;
                                $data['id']=$object->getId();
                        } else {
                                $object = $em->getRepository('ERPCRMBundle:CrmTipoActividad')->find($id);
                                $object->setNombre($name);
                                $object->setIcono($icon);
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
     * @Route("/activity/types/retrieve", name="admin_retrieve_activity_types_ajax",  options={"expose"=true}))
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
                $object = $em->getRepository('ERPCRMBundle:CrmTipoActividad')->find($id);
                if(count($object)!=0){
                
                    //$object->setProbabilidad($);
                    //$em->merge($object);
                    //$em->flush();    
                    $data['name']=$object->getNombre();
                    $data['icon']=$object->getIcono();
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
    
    
    
    
    
    
    
    
    
}
