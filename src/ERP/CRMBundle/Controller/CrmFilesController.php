<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CrmDocumentoAdjuntoActividad;
use ERP\CRMBundle\Entity\CrmDocumentoAdjuntoCampania;
use ERP\CRMBundle\Entity\CrmDocumentoAdjuntoContacto;
use ERP\CRMBundle\Entity\CrmDocumentoAdjuntoCuenta;
use ERP\CRMBundle\Entity\CrmDocumentoAdjuntoOportunidad;
use ERP\CRMBundle\Entity\CrmComentarioContacto;
use ERP\CRMBundle\Entity\CrmComentarioCuenta;
use ERP\CRMBundle\Entity\CrmComentarioCampania;
use ERP\CRMBundle\Entity\CrmComentarioOportunidad;
use ERP\CRMBundle\Form\CtlRolType;

/**
 * Files controller.
 *
 * @Route("/files/handler")
 */
class CrmFilesController extends Controller
{




    /**
     * Get files
     *
     * @Route("/files/get", name="admin_files_index_ajax",  options={"expose"=true}))
     * @Method("GET")
     */
    public function indexfilesAction(Request $request)
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
     * @Route("/file/add", name="admin_files_add_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function addfilesAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $response = new JsonResponse();
                $em = $this->getDoctrine()->getManager();
                $id=$request->get("param1");
                $tipoComment=$request->get("param2");
                
                
                switch($tipoComment){
                    case 1:///// CRM - Cuentas
                        $path = $this->getParameter('files.cuentas');
                        $cuentaObj = $em->getRepository('ERPCRMBundle:CrmCuenta')->find($id);
                        $sql = "SELECT max(doc.id) FROM ERPCRMBundle:CrmDocumentoAdjuntoCuenta doc";
                        //$sql="SELECT * FROM seguimiento where cuenta=".$id. " ORDER BY fecha_registro DESC LIMIT ".$inicio.",".$longitud;
                        break;
                    case 2:///// CRM - Actividades
                        $path = $this->getParameter('files.activities');
                        $cuentaObj = $em->getRepository('ERPCRMBundle:CrmActividad')->find($id);
                        $sql = "SELECT max(doc.id) FROM ERPCRMBundle:CrmDocumentoAdjuntoActividad doc";
                        //$sql="SELECT * FROM seguimientoact where actividad=".$id. " ORDER BY fecha_registro DESC LIMIT ".$inicio.",".$longitud;
                        break;
                    case 3:///// CRM - Campañas
                        $path = $this->getParameter('files.campaigns');
                        $cuentaObj = $em->getRepository('ERPCRMBundle:CrmCampania')->find($id);
                        $sql = "SELECT max(doc.id) FROM ERPCRMBundle:CrmDocumentoAdjuntoCampania doc";
                        break;
                    case 4:///// CRM - 
                        $path = $this->getParameter('files.contacts');
                        $cuentaObj = $em->getRepository('ERPCRMBundle:CrmContacto')->find($id);
                        $sql = "SELECT max(doc.id) FROM ERPCRMBundle:CrmDocumentoAdjuntoContacto doc";
                        break;
                    case 5:///// CRM - Oportunidades
                        $path = $this->getParameter('files.opportunities');
                        $cuentaObj = $em->getRepository('ERPCRMBundle:CrmOportunidad')->find($id);
                        $sql = "SELECT max(doc.id) FROM ERPCRMBundle:CrmDocumentoAdjuntoOportunidad doc";
                        break;
                }
                
                $idMax = $em->createQuery($sql)
                                    //->setParameters(array('idCuenta'=>$idCuenta))
                                    ->getResult();
                $nombreId = intval($idMax[0][1]);
                $usuarioObj = $this->get('security.token_storage')->getToken()->getUser();
                
                //if(count($cuentaObj)!=0){/////
                    //Manejo de imagen
                    $nombreTmp = $_FILES['file']['name'];
                    if ($nombreTmp!='') {
                        
                        $fecha = date('Y-m-d-His');
                        $extensionTmp = $_FILES['file']['type'];
                        $extensionArray= explode('/', $extensionTmp);
                        $extension = $extensionArray[1];
                        //$nombreArchivo =$fecha.".".$extension;
                        $nombreArchivo =$nombreTmp.".".$extension;
                        
                        
                        
                        $nombreArchivo = $nombreId.'-'.substr($nombreTmp, 0, 16).'.'.$extension;
                        //var_dump($nombreArchivo);
                        if(move_uploaded_file($_FILES['file']['tmp_name'], $path.$nombreArchivo)){
                            $data['path']='/files/';
                            switch($tipoComment){
                                case 1:///// CRM - Cuentas
                                    $crmFile= new CrmDocumentoAdjuntoCuenta();
                                    $crmFile->setCuenta($cuentaObj);
                                    $data['path'].='accounts/';
                                    break;
                                case 2:///// CRM - Actividades
                                    $crmFile= new CrmDocumentoAdjuntoActividad();
                                    $crmFile->setActividad($cuentaObj);
                                    $data['path'].='activities/';
                                    break;
                                case 3:///// CRM - Campañas
                                    $crmFile= new CrmDocumentoAdjuntoCampania();
                                    $crmFile->setCampania($cuentaObj);
                                    $data['path'].='campaigns/';
                                    break;
                                case 4:///// CRM - 
                                    $crmFile= new CrmDocumentoAdjuntoContacto();
                                    $crmFile->setContacto($cuentaObj);//en este caso el objeto $cuentaObj es del tipo contacto
                                    $data['path'].='contacts/';
                                    break;
                                case 5:///// CRM - opportunities
                                    $crmFile= new CrmDocumentoAdjuntoOportunidad();
                                    $crmFile->setOportunidad($cuentaObj);
                                    $data['path'].='opportunities/';
                                    break;
                            }                            
                            $crmFile->setUsuario($usuarioObj);
                            $crmFile->setFechaRegistro(new \DateTime('now'));
                            $crmFile->setSrc($nombreArchivo);
                            $crmFile->setEstado(1);
                            $em->persist($crmFile);
                            $em->flush();
                            $data['usuario']=$usuarioObj->getPersona()->getNombre().' '.$usuarioObj->getPersona()->getApellido();
                            $data['nombreFile']=$crmFile->getSrc();
                            $data['fecha']=$crmFile->getFechaRegistro()->format('Y-m-d H:i');
                            $data['idFile']=$crmFile->getId();
                        }
                        else{//Error al subir foto
                            $data['error']='Error';
                        }
                    } else {//Foto vacia
                        //var_dump('No file');
                    }
                //}
                $response->setData($data); 
            } catch (\Exception $e) {
//                var_dump($e->getMessage());
//                var_dump($e->getLine());
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
     * Add tag activities
     *
     * @Route("/file/add/activities", name="admin_files_add_activities_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function addfilesActAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $response = new JsonResponse();
                $em = $this->getDoctrine()->getManager();
                $idAct=$request->get("param1");                
                $actObj = $em->getRepository('ERPCRMBundle:CrmActividad')->find($idAct);
                $usuarioObj = $this->get('security.token_storage')->getToken()->getUser();
                //var_dump($_FILES);
                
                if(count($actObj)!=0){/////Etiqueta no existe
                    //Manejo de imagen
                    $nombreTmp = $_FILES['file']['name'];
                    if ($nombreTmp!='') {
                        //Buscar en la base la ciudad, primera iteracion debe buscar ciudad        
                        $path = $this->getParameter('files.actividades');
//                        var_dump(basename($path.$_FILES['file']['name']).PHP_EOL);
//                        die();
                        //var_dump($path);
                        $fecha = date('Y-m-d-His');
                        $extensionTmp = $_FILES['file']['type'];
                        $extensionArray= explode('/', $extensionTmp);
                        $extension = $extensionArray[1];
                        //$nombreArchivo =$fecha.".".$extension;
                        $nombreArchivo =$nombreTmp.".".$extension;
                        
                        $sql = "SELECT max(doc.id) FROM ERPCRMBundle:CrmDocumentoAdjuntoActividad doc";
                        $id = $em->createQuery($sql)
                                    //->setParameters(array('idCuenta'=>$idCuenta))
                                    ->getResult();
                        $nombreId = intval($id[0][1]);
                        
                        $nombreArchivo = $nombreId.'-'.substr($nombreTmp, 0, 16).'.'.$extension;
//                        var_dump($nombreArchivo);
//                        die();
                        //var_dump($nombreArchivo);
                        if(move_uploaded_file($_FILES['file']['tmp_name'], $path.$nombreArchivo)){
                            $crmFile= new CrmDocumentoAdjuntoActividad();
                            $crmFile->setActividad($actObj);
                            $crmFile->setUsuario($usuarioObj);
                            $crmFile->setFechaRegistro(new \DateTime('now'));
                            $crmFile->setSrc($nombreArchivo);
                            $crmFile->setEstado(1);
                            $em->persist($crmFile);
                            $em->flush();
                            $data['usuario']=$usuarioObj->getPersona()->getNombre().' '.$usuarioObj->getPersona()->getApellido();
                            $data['nombreFile']=$crmFile->getSrc();
                            $data['fecha']=$crmFile->getFechaRegistro()->format('Y-m-d H:i');
                            $data['idFile']=$crmFile->getId();
                        }
                        else{//Error al subir foto
                            $data['error']='Error';
                        }
                    } else {//Foto vacia
                        //var_dump('No file');
                    }
                }
                $response->setData($data); 
            } catch (\Exception $e) {
//                var_dump($e->getMessage());
//                var_dump($e->getLine());
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
     * Delete file
     *
     * @Route("/file/delete", name="admin_files_delete_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function deletefilesAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $response = new JsonResponse();
                $usuarioObj = $this->get('security.token_storage')->getToken()->getUser();
                $em = $this->getDoctrine()->getManager();
                $id=$request->get("param1");
                $idCuenta=$request->get("param2");
                $tipoComment=$request->get("param3");
                $actObj=null;
                    //$crmComentarioCuenta = new CrmComentarioCuenta();
                $fechaRegistro = new \DateTime('now');
                $cuentaObj = $em->getRepository('ERPCRMBundle:CrmCuenta')->find($idCuenta);
                switch($tipoComment){
                    case 1:///// CRM - Cuenta
                        $docObj = $em->getRepository('ERPCRMBundle:CrmDocumentoAdjuntoCuenta')->find($id);
                        $crmComentario= new CrmComentarioActividad();
                        $actObj = $em->getRepository('ERPCRMBundle:CrmCuenta')->find($idCuenta);
                        $crmComentario->setCuenta($actObj);
                        $sql="SELECT COUNT(*) as total FROM seguimiento where cuenta=".$id;
                        break;
                    case 2:///// CRM - Actividad
                        $docObj = $em->getRepository('ERPCRMBundle:CrmDocumentoAdjuntoActividad')->find($id);
                        $crmComentario= new CrmComentarioActividad();
                        $actObj = $em->getRepository('ERPCRMBundle:CrmActividad')->find($idCuenta);
                        $crmComentario->setActividad($actObj);
                        $sql="SELECT COUNT(*) as total FROM seguimientoact where actividad=".$id;
                        break;
                    case 3:///// CRM - Campaña
                        $docObj = $em->getRepository('ERPCRMBundle:CrmDocumentoAdjuntoCampania')->find($id);

                        $crmComentario= new CrmComentarioCampania();
                        $actObj = $em->getRepository('ERPCRMBundle:CrmCampania')->find($idCuenta);
                        $crmComentario->setCampania($actObj);
                        $sql="SELECT COUNT(*) as total FROM seguimientocmp where campania=".$id;
                        break;
                    case 4:///// CRM - Contacto                       
                        $docObj = $em->getRepository('ERPCRMBundle:CrmDocumentoAdjuntoContacto')->find($id);

                        $crmComentario = new CrmComentarioContacto();
                        $actObj = $em->getRepository('ERPCRMBundle:CrmContacto')->find($idCuenta);
                        $crmComentario->setContacto($actObj);
                        $sql = "SELECT COUNT(*) as total FROM seguimientocont where contacto=" . $id;
                        break;
                    case 5:///// CRM - Campaña
                        $docObj = $em->getRepository('ERPCRMBundle:CrmDocumentoAdjuntoOportunidad')->find($id);

                        $crmComentario= new CrmComentarioOportunidad();
                        $actObj = $em->getRepository('ERPCRMBundle:CrmOportunidad')->find($idCuenta);
                        $crmComentario->setOportunidad($actObj);
                        $sql="SELECT COUNT(*) as total FROM seguimientopport where oportunidad=".$id;
                        break;
                }
                if(count($docObj)!=0){
                    $em->getConnection()->beginTransaction();
                    $docObj->setEstado(0);
                    $em->merge($docObj);
                    $em->flush();
                    $comment = $this->getParameter('app.serverFileAttachedDel').' '.$docObj->getSrc();
                    $crmComentario->setComentario($comment);
                    $crmComentario->setFechaRegistro($fechaRegistro);
                    $crmComentario->setUsuario($usuarioObj);
                    $crmComentario->setTipoComentario(2);/////Archivos
                    $em->persist($crmComentario);
                    $em->flush();
                    $data['usuario']=$usuarioObj->getPersona()->getNombre().' '.$usuarioObj->getPersona()->getApellido();
                    $data['comentario']=$comment;
                    $data['tipocomentario']=$crmComentario->getTipoComentario();
                    $data['fecha']=$fechaRegistro->format('Y-m-d H:i');
                    //$data['numeroItems']=$reg[0]['total'];
                    $em->getConnection()->commit();
                    $em->close();
                }
                $data['deleted']='';
                $response->setData($data); 
            } catch (\Exception $e) {
                //var_dump($e);
                $em->getConnection()->rollback();
                $em->close();
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
