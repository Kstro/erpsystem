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
use ERP\CRMBundle\Entity\CrmActividad;
use ERP\CRMBundle\Entity\CrmComentarioActividad;
use ERP\CRMBundle\Entity\CtlPersona;
use ERP\CRMBundle\Entity\CrmContacto;
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
 * @Route("/seguimiento/handler")
 */
class CrmSeguimientoController extends Controller {
    
    /**
    * Ajax utilizado para buscar informacion de los ultimos 6 seguimientos de una cuenta
    *  
    * @Route("/seguimiento/data/general", name="busqueda_general_seguimiento_info",  options={"expose"=true}))
    */
    public function busquedaSeguimientoGeneralAction(Request $request)
    {
        try {
            $timeZone = $this->get('time_zone')->getTimeZone();
            date_default_timezone_set($timeZone->getNombre());
            $response = new JsonResponse();
            $id=$request->get("param1"); /////Id de la cuenta que se esta viendo
            
            $longitud = $this->getParameter('app.serverSeguimientoLongitud'); /////Numero de items a recuperar por click y al inicio
            $files = $this->getParameter('app.serverFileAttached'); /////
            $numPedidos=$request->get("param2"); /////Numero de veces solicitado, para el paginado
            $tipoComment=$request->get("param3"); /////Parametro para identificar a que tabla va el comentario
            // sleep ( 5 );
            
            $data['path']='';
            
            $inicio=($longitud*$numPedidos)-$longitud;
            $em = $this->getDoctrine()->getEntityManager();
            
            switch($tipoComment){
                case 1:///// CRM - Cuentas
                    $data['path'].='accounts';
                    $sql="SELECT * FROM seguimiento where cuenta=".$id. " ORDER BY fecha_registro DESC LIMIT ".$inicio.",".$longitud;
                    break;
                case 2:///// CRM - Actividades
                    $data['path'].='activities';
                    $sql="SELECT * FROM seguimientoact where actividad=".$id. " ORDER BY fecha_registro DESC LIMIT ".$inicio.",".$longitud;
                    break;
                case 3:///// CRM - Campañas
                    $data['path'].='campaigns';
                    $sql="SELECT * FROM seguimientocmp where campania=".$id. " ORDER BY fecha_registro DESC LIMIT ".$inicio.",".$longitud;
                    break;
                case 4:///// CRM - 
                    $sql="SELECT * FROM seguimientocont where contacto=".$id. " ORDER BY fecha_registro DESC LIMIT ".$inicio.",".$longitud;
                    break;
                case 5:///// CRM - Oportunidades
                    $sql="SELECT * FROM seguimientopport where oportunidad=".$id. " ORDER BY fecha_registro DESC LIMIT ".$inicio.",".$longitud;
                    break;
            }
            
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $data['data']= $stmt->fetchAll();
            $data['files']= $files;
            // return new Response(json_encode($data));
        } catch (\Exception $e) {
            $data['error']=$e->getMessage();
        }
        $response->setData($data);
        return $response;
    }
}
