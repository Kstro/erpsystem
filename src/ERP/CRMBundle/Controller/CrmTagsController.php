<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CrmEtiqueta;
use ERP\CRMBundle\Entity\CrmEtiquetaCuenta;
use ERP\CRMBundle\Form\CtlRolType;

/**
 * Tags controller.
 *
 * @Route("/tags/handler")
 */
class CrmTagsController extends Controller
{




    /**
     * Get tags
     *
     * @Route("/tags/get", name="admin_tags_index_ajax",  options={"expose"=true}))
     * @Method("GET")
     */
    public function indextagsAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $response = new JsonResponse();
                $em = $this->getDoctrine()->getManager();
                $crmEtiqueta = new CrmEtiqueta();
                $crmEtiquetaCuenta = new CrmEtiquetaCuenta();
                $idCuenta=$request->get("param1");
                
                $sql = "SELECT ec.id as id, e.nombre as nombre FROM ERPCRMBundle:CrmEtiquetaCuenta ec"
                            ." JOIN ec.etiqueta e "
                            ." JOIN ec.cuenta c "
                            ." WHERE c.id=:idCuenta";
                $tags = $em->createQuery($sql)
                                    ->setParameters(array('idCuenta'=>$idCuenta))
                                    ->getResult();
                
                if(count($tags)==0){
                    $data['error']='No data';
                }
                else{
                    $data['data']=$tags;
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





    /**
     * Add tag
     *
     * @Route("/tag/add", name="admin_tags_add_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function addtagsAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $response = new JsonResponse();
                $em = $this->getDoctrine()->getManager();
                $crmEtiqueta = new CrmEtiqueta();
                $crmEtiquetaCuenta = new CrmEtiquetaCuenta();
                $idCuenta=$request->get("param1");
                $nombreTag=$request->get("param2");
                $etiquetaCuentaObj=array();
                $cuentaObj = $em->getRepository('ERPCRMBundle:CrmCuenta')->find($idCuenta);
                $etiquetaObj = $em->getRepository('ERPCRMBundle:CrmEtiqueta')->findBy(array('nombre'=>$nombreTag));
                if(count($etiquetaObj)==0){/////Etiqueta no existe
                    $crmEtiqueta->setNombre($nombreTag);
                    $em->persist($crmEtiqueta);
                    $em->flush();  
                    $crmEtiquetaCuenta->setEtiqueta($crmEtiqueta);
                }
                else{/////Etiqueta existe
                    $etiquetaCuentaObj = $em->getRepository('ERPCRMBundle:CrmEtiquetaCuenta')->findBy(array('etiqueta'=>$etiquetaObj[0]->getId(),'cuenta'=>$idCuenta));
                    $crmEtiquetaCuenta->setEtiqueta($etiquetaObj[0]);
                    $crmEtiquetaCuenta->setCuenta($cuentaObj);
                    if (count($etiquetaCuentaObj)==0) {/////Etiqueta no esta asignada a esa cuenta
                        $em->persist($crmEtiquetaCuenta);
                        $em->flush();
                        $data['nombreTag']=$etiquetaObj[0]->getNombre();
                        $data['idTag']=$crmEtiquetaCuenta->getId();    
                    }
                    else{
                        $data['existe']=1;
                        // $data['nombreTag']=$etiquetaObj[0]->getNombre();
                        // $data['idTag']=$etiquetaCuentaObj[0]->getId();   
                        // $em->persist($crmEtiquetaCuenta);
                        // $em->flush(); 
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





    /**
     * Add tag
     *
     * @Route("/tag/delete", name="admin_tags_delete_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function deletetagsAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $response = new JsonResponse();
                $em = $this->getDoctrine()->getManager();
                $id=$request->get("param1");
                $etiquetaObj = $em->getRepository('ERPCRMBundle:CrmEtiquetaCuenta')->find($id);
                //$tagObj=
                /*var_dump($etiqueta);
                die();*/
                if(count($etiquetaObj)!=0){
                    $etiqueta = $etiquetaObj->getEtiqueta()->getId();
                    $em->remove($etiquetaObj);
                    $em->flush();
                    $etiquetaArrayObj = $em->getRepository('ERPCRMBundle:CrmEtiquetaCuenta')->findBy(array('etiqueta'=>$etiqueta));
                    if (count($etiquetaArrayObj)==0) {
                        $crmEtiquetaObj = $em->getRepository('ERPCRMBundle:CrmEtiqueta')->find($etiqueta);
                        $em->remove($crmEtiquetaObj);
                        $em->flush();
                        $data['deletedetiqueta']=$etiqueta;
                    }
                }
                $data['deleted']='';
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
