<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CrmCuenta;
use ERP\CRMBundle\Entity\CrmComentarioCuenta;
use ERP\CRMBundle\Entity\CrmComentarioCampania;
use ERP\CRMBundle\Entity\CrmComentarioOportunidad;
use ERP\CRMBundle\Entity\CrmActividad;
use ERP\CRMBundle\Entity\CrmComentarioActividad;
use ERP\CRMBundle\Entity\CtlPersona;
use ERP\CRMBundle\Entity\CrmContacto;
use ERP\CRMBundle\Entity\CrmComentarioContacto;
use ERP\CRMBundle\Entity\CrmContactoCuenta;
use ERP\CRMBundle\Entity\CtlTratamientoProtocolario;
use ERP\CRMBundle\Entity\CtlTelefono;
use ERP\CRMBundle\Entity\CtlCorreo;
use ERP\CRMBundle\Entity\CtlDireccion;
use ERP\CRMBundle\Entity\CrmFoto;
use ERP\CRMBundle\Form\CrmCuentaType;
/**
 * Files controller.
 *
 * @Route("/comments/handler")
 */
class CrmComentarioController extends Controller {

    /**
     * Add comment providers
     *
     * @Route("/general/comment/add", name="admin_general_comment_add_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function commentajaxactAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $timeZone = $this->get('time_zone')->getTimeZone();
                date_default_timezone_set($timeZone->getNombre());
                $id=$request->get("param1");                
                $comment=$request->get("param2");
                $tipoComment=$request->get("param3");                
                $fechaRegistro = new \DateTime('now');
//                var_dump($fechaRegistro->format('Y-m-d H:i'));
//                die();
                $response = new JsonResponse();
                $usuarioObj = $this->get('security.token_storage')->getToken()->getUser();

                $em = $this->getDoctrine()->getManager();

                
                // $usuarioObj = $em->getRepository('ERPCRMBundle:CtlUsuario')->find($idCuenta);

                $crmComentario = null;
                
               //echo $tipoComment;
                switch($tipoComment){
                    case 1:///// CRM - Cuenta
                        $crmComentario= new CrmComentarioCuenta();
                        $actObj = $em->getRepository('ERPCRMBundle:CrmCuenta')->find($id);
                        $crmComentario->setCuenta($actObj);
                        $sql="SELECT COUNT(*) as total FROM seguimiento where cuenta=".$id;
                        break;
                    case 2:///// CRM - Actividad
                        $crmComentario= new CrmComentarioActividad();
                        $actObj = $em->getRepository('ERPCRMBundle:CrmActividad')->find($id);
                        $crmComentario->setActividad($actObj);
                        $sql="SELECT COUNT(*) as total FROM seguimientoact where actividad=".$id;
                        break;
                    case 3:///// CRM - Campaña
                        $crmComentario= new CrmComentarioCampania();
                        $actObj = $em->getRepository('ERPCRMBundle:CrmCampania')->find($id);
                        $crmComentario->setCampania($actObj);
                        $sql="SELECT COUNT(*) as total FROM seguimientocmp where campania=".$id;
                        break;
                    
                    case 5:///// CRM - Oportunidad
                        $crmComentario= new CrmComentarioOportunidad();
                        $actObj = $em->getRepository('ERPCRMBundle:CrmOportunidad')->find($id);
                        $crmComentario->setOportunidad($actObj);
                        $sql="SELECT COUNT(*) as total FROM seguimientopport where oportunidad=".$id;
                        break;
                }
                
                $crmComentario->setComentario($comment);
                $crmComentario->setFechaRegistro($fechaRegistro);
                
                $crmComentario->setUsuario($usuarioObj);
                $crmComentario->setTipoComentario(1);//Comentario

                $em->persist($crmComentario);
                $em->flush();

                
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();
                $reg= $stmt->fetchAll();

                // var_dump($reg);
                // die();
                $data['usuario']=$usuarioObj->getPersona()->getNombre().' '.$usuarioObj->getPersona()->getApellido();
                $data['comentario']=$comment;
                $data['tipocomentario']=$crmComentario->getTipoComentario();
                $data['fecha']=$fechaRegistro->format('Y-m-d H:i');
                $data['numeroItems']=$reg[0]['total'];
                //$data['usuario']=$usuario->getPersona()->getNombre().' '.$usuario->getPersona()->getApellido();
                
                $response->setData($data); 
            } catch (\Exception $e) {
                echo "'".$e->getLine()."'";
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
