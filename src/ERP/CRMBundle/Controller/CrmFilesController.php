<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CrmDocumentoAdjuntoActividad;
use ERP\CRMBundle\Entity\CrmDocumentoAdjuntoCuenta;
use ERP\CRMBundle\Entity\CrmComentarioCuenta;
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
                        $cuentaObj = $em->getRepository('ERPCRMBundle:CrmCuenta')->find($id);
                        //$sql="SELECT * FROM seguimiento where cuenta=".$id. " ORDER BY fecha_registro DESC LIMIT ".$inicio.",".$longitud;
                        break;
                    case 2:///// CRM - Actividades
                        $cuentaObj = $em->getRepository('ERPCRMBundle:CrmActividad')->find($id);
                        //$sql="SELECT * FROM seguimientoact where actividad=".$id. " ORDER BY fecha_registro DESC LIMIT ".$inicio.",".$longitud;
                        break;
                    case 3:///// CRM - Campañas
                        $sql="SELECT * FROM seguimientoact where actividad=".$id. " ORDER BY fecha_registro DESC LIMIT ".$inicio.",".$longitud;
                        break;
                    case 4:///// CRM - 
                        $sql="SELECT * FROM seguimientoact where actividad=".$id. " ORDER BY fecha_registro DESC LIMIT ".$inicio.",".$longitud;
                        break;
                    case 5:///// CRM -
                        $sql="SELECT * FROM seguimientoact where actividad=".$id. " ORDER BY fecha_registro DESC LIMIT ".$inicio.",".$longitud;
                        break;
                }
                
                
                $usuarioObj = $this->get('security.token_storage')->getToken()->getUser();
                
                if(count($cuentaObj)!=0){/////
                    //Manejo de imagen
                    $nombreTmp = $_FILES['file']['name'];
                    if ($nombreTmp!='') {
                        //Buscar en la base la ciudad, primera iteracion debe buscar ciudad        
                        $path = $this->getParameter('files.cuentas');
//                        var_dump(basename($path.$_FILES['file']['name']).PHP_EOL);
//                        die();
                        //var_dump($path);
                        $fecha = date('Y-m-d-His');
                        $extensionTmp = $_FILES['file']['type'];
                        $extensionArray= explode('/', $extensionTmp);
                        $extension = $extensionArray[1];
                        //$nombreArchivo =$fecha.".".$extension;
                        $nombreArchivo =$nombreTmp.".".$extension;
                        
                        $sql = "SELECT max(doc.id) FROM ERPCRMBundle:CrmDocumentoAdjuntoCuenta doc";
                        $id = $em->createQuery($sql)
                                    //->setParameters(array('idCuenta'=>$idCuenta))
                                    ->getResult();
                        $nombreId = intval($id[0][1]);
                        
                        $nombreArchivo = $nombreId.'-'.substr($nombreTmp, 0, 16).'.'.$extension;
                        //var_dump($nombreArchivo);
                        if(move_uploaded_file($_FILES['file']['tmp_name'], $path.$nombreArchivo)){
                            
                            switch($tipoComment){
                                case 1:///// CRM - Cuentas
                                    $crmFile= new CrmDocumentoAdjuntoCuenta();
                                    $crmFile->setCuenta($cuentaObj);
                                    break;
                                case 2:///// CRM - Actividades
                                    $crmFile= new CrmDocumentoAdjuntoActividad();
                                    $crmFile->setActividad($cuentaObj);
                                    break;
                                case 3:///// CRM - Campañas
                                    //$sql="SELECT * FROM seguimientoact where actividad=".$id. " ORDER BY fecha_registro DESC LIMIT ".$inicio.",".$longitud;
                                    break;
                                case 4:///// CRM - 
                                    //$sql="SELECT * FROM seguimientoact where actividad=".$id. " ORDER BY fecha_registro DESC LIMIT ".$inicio.",".$longitud;
                                    break;
                                case 5:///// CRM -
                                    //$sql="SELECT * FROM seguimientoact where actividad=".$id. " ORDER BY fecha_registro DESC LIMIT ".$inicio.",".$longitud;
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
                $docObj = $em->getRepository('ERPCRMBundle:CrmDocumentoAdjuntoCuenta')->find($id);
                //$tagObj=
                /*var_dump($etiqueta);
                die();*/
                //$data['deletedetiqueta']='';
                if(count($docObj)!=0){
                    $em->getConnection()->beginTransaction();
                    $docObj->setEstado(0);
                    $em->merge($docObj);
                    $em->flush();
                    
                    $crmComentarioCuenta = new CrmComentarioCuenta();
                    $fechaRegistro = new \DateTime('now');
                    
                    $cuentaObj = $em->getRepository('ERPCRMBundle:CrmCuenta')->find($idCuenta);
                    
                    $comment = $this->getParameter('app.serverFileAttachedDel').' '.$docObj->getSrc();
                    $crmComentarioCuenta->setComentario($comment);
                    $crmComentarioCuenta->setFechaRegistro($fechaRegistro);
                    $crmComentarioCuenta->setCuenta($cuentaObj);
                    $crmComentarioCuenta->setUsuario($usuarioObj);
                    $crmComentarioCuenta->setTipoComentario(2);/////Archivos
    
                    $em->persist($crmComentarioCuenta);
                    $em->flush();
                    
                    
                    $data['usuario']=$usuarioObj->getPersona()->getNombre().' '.$usuarioObj->getPersona()->getApellido();
                    $data['comentario']=$comment;
                    $data['tipocomentario']=$crmComentarioCuenta->getTipoComentario();
                    $data['fecha']=$fechaRegistro->format('Y-m-d H:i');
                    //$data['numeroItems']=$reg[0]['total'];
                    
                    
                    $em->getConnection()->commit();
                    $em->close();
                }
                $data['deleted']='';
                $response->setData($data); 
            } catch (\Exception $e) {
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
