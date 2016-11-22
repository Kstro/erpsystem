<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CrmOportunidad;
use ERP\CRMBundle\Entity\CrmAsignadoOportunidad;
use ERP\CRMBundle\Entity\CrmProductoOportunidad;
use ERP\CRMBundle\Entity\CrmCotizacion;
use ERP\CRMBundle\Entity\CrmDetalleCotizacion;

/**
 * CrmOportunidadController controller.
 *
 * @Route("/admin/opportunities")
 */
class CrmOportunidadController extends Controller
{
    /**
     * Lists all CrmOportunidad entities.
     *
     * @Route("/", name="admin_opportunities_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        try{
            $em = $this->getDoctrine()->getManager();

            //Fuentes
            $fuentes = $em->getRepository('ERPCRMBundle:CtlFuente')->findBy(array('estado'=>1));

            //Tipos de cuenta
            $dql = "SELECT tipo.id, tipo.nombre "
                    . "FROM ERPCRMBundle:CrmTipoCuenta tipo "
                    . "WHERE tipo.estado = 1 AND tipo.id NOT IN (1, 4) "
                    . "ORDER BY tipo.id";

            $tiposCuenta = $em->createQuery($dql)                        
                    ->getResult();
            
            /*$tiposCuenta = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->findBy(array('estado'=>1));*/

            //Etapas de venta
            $etapasVenta = $em->getRepository('ERPCRMBundle:CtlEtapaVenta')->findBy(array('estado'=>1));

            //Campañas
            $campanias = $em->getRepository('ERPCRMBundle:CrmCampania')->findBy(array('estado'=>1));
            
            //Productos
            $productos = $em->getRepository('ERPCRMBundle:CtlProducto')->findBy(array('estado'=>1));

            //Persona-usuarios
            $personas = $em->getRepository('ERPCRMBundle:CtlUsuario')->findBy(array('estado'=>1));
            
            // Estados de la cotización
            $statusCot = $em->getRepository('ERPCRMBundle:CrmEstadoCotizacion')->findAll();
            
            //Tipos de etiqueta
            $sql = "SELECT tag.id as id, tag.nombre as nombre "
                    . "FROM ERPCRMBundle:CrmEtiquetaOportunidad obj "
                    ."JOIN obj.etiqueta tag";
            
            $etiquetas = $em->createQuery($sql)
                        ->getResult();
            
            return $this->render('crm_oportunidad/index.html.twig', array(
                'tiposCuenta'=>$tiposCuenta,
                'fuentes'=>$fuentes,
                'etapasVenta'=>$etapasVenta,
                'campanias'=>$campanias,
                'productos'=>$productos,
                'personas'=>$personas,
                'statusCot'=>$statusCot,
                'etiquetas'=>$etiquetas,
                'menuOportunidadesA' => true,
            ));
        } catch (\Exception $e) {  
            $response = new JsonResponse();
            
            if(method_exists($e,'getErrorCode')){ 
                $serverOffline = $this->getParameter('app.serverOffline');
                $data['error'] = $serverOffline.'. CODE: '.$e->getErrorCode();
            }
            else{
                $data['error'] = $e->getMessage();  
            }
            $response->setData($data);
            
            return $response;                              
        }
    }  
    
    /**
     *  Se obtienen las oportunidades de venta registradas en el sistema
     *
     * @Route("/get/data/all", name="admin_opportunities_data", options={"expose"=true})
     */
    public function dataOpportunitiesAction(Request $request)
    {        
        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');
        
        $em = $this->getDoctrine()->getManager();
        $rowsTotal = $em->getRepository('ERPCRMBundle:CrmOportunidad')->findBy(array('estado' => 1));
        
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
                $orderByText = "account";
                break;
            case 3:
                $orderByText = "stage";
                break;
            case 4:
                $orderByText = "close";
                break;
            case 5:
                $orderByText = "contact";
                break;
        }
        
        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        if($busqueda['value']!=''){                                
            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', opo.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOportunidad fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',opo.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, "
                    . "CONCAT('<div style=\"text-align: left;\">', opo.fechaRegistro, '</div>') as created, "
                    . "CONCAT('<div style=\"text-align: left;\">', opo.fechaCierre, '</div>') as close, "
                    . "CONCAT('<div style=\"text-align: left;\">', sta.nombre, '</div>') as stage, "
                    . "CONCAT('<div style=\"text-align: left;\">', cta.nombre, '</div>') as account, "
                    . "CONCAT('<div style=\"text-align: left;\">', per.nombre, ' ', per.apellido, '</div>') as contact "
                    . "FROM ERPCRMBundle:CrmOportunidad opo "
                    . "JOIN opo.etapaVenta sta "
                    . "JOIN opo.cuenta cta "
                    . "JOIN cta.contactoCuenta cc "
                    . "JOIN cc.contacto con "
                    . "JOIN con.persona per "
                    . "WHERE opo.estado = 1 AND cc.titular = 1 AND CONCAT(upper(opo.nombre), ' ' , upper(opo.fechaRegistro), ' ' , upper(CONCAT(per.nombre, ' ', per.apellido)), ' ' , upper(opo.fechaCierre), ' ' , upper(sta.nombre), ' ' , upper(cta.nombre)) LIKE upper(:busqueda) "
                    . "ORDER BY ".$orderByText." ".$orderDir;

            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                    ->getResult();

            $row['recordsFiltered']= count($row['data']);

            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', opo.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOportunidad fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',opo.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, "
                    . "CONCAT('<div style=\"text-align: left;\">', opo.fechaRegistro, '</div>') as created, "
                    . "CONCAT('<div style=\"text-align: left;\">', opo.fechaCierre, '</div>') as close, "
                    . "CONCAT('<div style=\"text-align: left;\">', sta.nombre, '</div>') as stage, "
                    . "CONCAT('<div style=\"text-align: left;\">', cta.nombre, '</div>') as account, "
                    . "CONCAT('<div style=\"text-align: left;\">', per.nombre, ' ', per.apellido, '</div>') as contact "
                    . "FROM ERPCRMBundle:CrmOportunidad opo "
                    . "JOIN opo.etapaVenta sta "
                    . "JOIN opo.cuenta cta "
                    . "JOIN cta.contactoCuenta cc "
                    . "JOIN cc.contacto con "
                    . "JOIN con.persona per "
                    . "WHERE opo.estado = 1 AND cc.titular = 1 AND CONCAT(upper(opo.nombre), ' ' , upper(opo.fechaRegistro), ' ' , upper(CONCAT(per.nombre, ' ', per.apellido)), ' ' , upper(opo.fechaCierre), ' ' , upper(sta.nombre), ' ' , upper(cta.nombre)) LIKE upper(:busqueda) "
                    . "ORDER BY ".$orderByText." ".$orderDir;

            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();              
        }
        else{
            $dql = "SELECT CONCAT('<div style=\"text-align: left;\">', opo.nombre, '</div>') as name, "
                    . "'<a ><i style=\"cursor:pointer;\"  class=\"infoOportunidad fa fa-info-circle\"></i></a>' as link, "
                    . "CONCAT('<div id=\"',opo.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, "
                    . "CONCAT('<div style=\"text-align: left;\">', opo.fechaRegistro, '</div>') as created, "
                    . "CONCAT('<div style=\"text-align: left;\">', opo.fechaCierre, '</div>') as close, "
                    . "CONCAT('<div style=\"text-align: left;\">', sta.nombre, '</div>') as stage, "
                    . "CONCAT('<div style=\"text-align: left;\">', cta.nombre, '</div>') as account, "
                    . "CONCAT('<div style=\"text-align: left;\">', per.nombre, ' ', per.apellido, '</div>') as contact "
                    . "FROM ERPCRMBundle:CrmOportunidad opo "
                    . "JOIN opo.etapaVenta sta "
                    . "JOIN opo.cuenta cta "
                    . "JOIN cta.contactoCuenta cc "
                    . "JOIN cc.contacto con "
                    . "JOIN con.persona per "
                    . "WHERE opo.estado = 1 AND cc.titular = 1 "
                    . "ORDER BY ".$orderByText." ".$orderDir;
            
            $row['data'] = $em->createQuery($dql)
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();
        }
        
        return new Response(json_encode($row));
    }
          
    /**
     * Save Opportunity
     *
     * @Route("/save/ajax", name="admin_opportunity_save_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function saveOpportunityAjaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                // Recuperando y seteando la zona horaria que se haya establecido
                $timeZone = $this->get('time_zone')->getTimeZone();
                date_default_timezone_set($timeZone->getNombre());
                
                $em = $this->getDoctrine()->getManager();
                $response = new JsonResponse();
                
                //Captura de parametros
                $idOportunidad = $_POST['txtId'];         // Id de Oportunidad
                $nombreOportunidad = $_POST['txtName'];   // Nombre de la oportunidad de venta
                $tipoCuenta = $_POST['tipoCuenta'];       // Tipo de cuenta seleccionada
                $cuenta = $_POST['cuenta'];               // Cuenta vinculada a la oportunidad.
                $etapaVenta = $_POST['etapaVenta'];       // Etapa que se encuentra la oportunidad de venta.
                $probabilidad = $_POST['txtProbability']; // Probabilidad de que la venta se realice con exito.
                $fechaRegistro = new \DateTime('now');    // Fecha de registro de la oportunidad de venta.
                $txtFechaCierre = $_POST['txtFechaCierre'];// Fecha de cierre de la oportunidad de venta.
                $descripcion = $_POST['descripcion'];     // Descripcion de la oportunidad de venta.
                $fuente = $_POST['fuente'];               // Fuente de origen de la oportunidad de venta.
                $campania = $_POST['campania'];           // Campaña de donde se obtuvo la oportunidad de venta.
                $estado = 1;                              // Estado de la oporunidad de venta
                
                // Personas
                $personaArray = $_POST['responsable'];   // Array de personas asignadas a la oportunidad de venta.

                //Cantidad y productos asociados a la oportunidad
                $cantidadArray = $_POST['cantidad'];     // Array de cantidad de cada producto.
                $productosArray = $_POST['sProducto'];   // Array de productos asociados a la oportunidad de venta.

                //Busqueda objetos a partir de Id's
                $crmTipoCuentaObj = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->find($tipoCuenta);   // Objeto del tipo de cuenta seleccionado
                $crmCuentaObj = $em->getRepository('ERPCRMBundle:CrmCuenta')->find($cuenta);               // Objeto de la cuenta seleccionada
                $crmEtapaVentaObj = $em->getRepository('ERPCRMBundle:CtlEtapaVenta')->find($etapaVenta);   // Objeto de la etapa de venta seleccionada
                $crmFuentePrincipalObj = $em->getRepository('ERPCRMBundle:CtlFuente')->find($fuente);      // Objeto de la etapa de venta seleccionada
                
                // Estableciendo el formato de fecha para seteo en la entidad (YYYY-mm-dd HH:ii)
                $fcierre = explode(' ', $txtFechaCierre);
                $fecha = explode('/', $fcierre[0]);
                $fechaCierre = $fecha[0] . '-' . $fecha[1] . '-' . $fecha[2] . ' ' . $fcierre[1];
                
                if($idOportunidad == ''){                    
                    //Seteo en Entidad CrmOportunidad
                    $crmOportunidadObj = new CrmOportunidad();
                    
                    $crmOportunidadObj->setNombre($nombreOportunidad);
                    $crmOportunidadObj->setFechaRegistro($fechaRegistro);
                    $crmOportunidadObj->setFechaCierre( new \DateTime($fechaCierre));
                    $crmOportunidadObj->setDescripcion($descripcion);
                    $crmOportunidadObj->setEtapaVenta($crmEtapaVentaObj);
                    $crmOportunidadObj->setProbabilidad($probabilidad);
                    $crmOportunidadObj->setEstadoOportunidad(NULL);
                    $crmOportunidadObj->setCuenta($crmCuentaObj);
                    $crmOportunidadObj->setFuentePrincipal($crmFuentePrincipalObj);
                    $crmOportunidadObj->setEstado($estado);
                    
                    // Si se ha seleccionado que la fuente de la oportunidad  
                    // de venta ha sido a traves de una camapaña
                    if($fuente == 1){
                        // Objeto de la campaña seleccionada
                        $crmCampaniaObj = $em->getRepository('ERPCRMBundle:CrmCampania')->find($campania);     
                        
                        // Seteo de la campaña seleccionada
                        $crmOportunidadObj->setCampania($crmCampaniaObj);
                    } else {
                        $crmOportunidadObj->setCampania(NULL);
                    }
                                    
                    //Persistencia en crmOportunidadObj
                    $em->persist($crmOportunidadObj);
                    $em->flush();
                    
                    // Personas asignadas a la oportunidad de venta
                    foreach ($personaArray as $value) {
                        // Objeto de la persona asignada a la oportunidad
                        $personaObj = $em->getRepository('ERPCRMBundle:CtlUsuario')->find($value);
                        
                        //Seteo en Entidad CrmAsignadoOportunidad
                        $crmAsignadoOportunidadObj = new CrmAsignadoOportunidad();
                        $crmAsignadoOportunidadObj->setOportunidad($crmOportunidadObj);
                        $crmAsignadoOportunidadObj->setUsuarioAsignado($personaObj);
                        $crmAsignadoOportunidadObj->setPrioridad(NULL);
                        $crmAsignadoOportunidadObj->setFechaLimite( new \DateTime($fecha[2] . '-' . $fecha[1] . '-' . $fecha[0]));

                        //Persistiendo $crmAsignadoOportunidadObj
                        $em->persist($crmAsignadoOportunidadObj);
                        $em->flush();
                    }
                    
                    // Productos relacionados a la oportunidad de venta
                    foreach ($productosArray as $key => $producto) {
                        // Objeto del producto relacionado a la oportunidad
                        $productoObj = $em->getRepository('ERPCRMBundle:CtlProducto')->find($producto);
                        
                        // Seteo en Entidad CrmProductoOportunidad
                        $productoOportunidadObj = new CrmProductoOportunidad();                        
                        $productoOportunidadObj->setOportunidad($crmOportunidadObj);
                        $productoOportunidadObj->setProducto($productoObj);
                        $productoOportunidadObj->setCantidad($cantidadArray[$key]);
                        
                        //Persistiendo productoOportunidadObj
                        $em->persist($productoOportunidadObj);
                        $em->flush();
                    }
                                
                    $serverSave = $this->getParameter('app.serverMsgSave');
                    
                    $data['id']=$crmOportunidadObj->getId();
                    $data['msg']=$serverSave;
                }//Fin de if id, inserción
                // Else para la modificación del objeto crmOportunidad y sus tablas dependientes
                else {                    
                    $crmOportunidadObj = $em->getRepository('ERPCRMBundle:CrmOportunidad')->find($idOportunidad);

                    //Seteo en Entidad CrmOportunidad
                    $crmOportunidadObj->setNombre($nombreOportunidad);
                    $crmOportunidadObj->setFechaCierre( new \DateTime($fechaCierre));
                    $crmOportunidadObj->setDescripcion($descripcion);
                    $crmOportunidadObj->setEtapaVenta($crmEtapaVentaObj);
                    $crmOportunidadObj->setProbabilidad($probabilidad);
                    $crmOportunidadObj->setCuenta($crmCuentaObj);
                    $crmOportunidadObj->setFuentePrincipal($crmFuentePrincipalObj);

                    // Si se ha seleccionado que la fuente de la oportunidad  
                    // de venta ha sido a traves de una camapaña
                    if($fuente == 1){
                        // Objeto de la campaña seleccionada
                        $crmCampaniaObj = $em->getRepository('ERPCRMBundle:CrmCampania')->find($campania);     

                        // Seteo de la campaña seleccionada
                        $crmOportunidadObj->setCampania($crmCampaniaObj);
                    } else {
                        $crmOportunidadObj->setCampania(NULL);
                    }

                    //Persistiendo $crmOportunidadObj
                    $em->merge($crmOportunidadObj);
                    $em->flush();

                    //Eliminar personas asignadas a la oportunidad
                    $asignacionArrayObj = $em->getRepository('ERPCRMBundle:CrmAsignadoOportunidad')->findBy(array('oportunidad'=>$crmOportunidadObj));
                    foreach ($asignacionArrayObj as $key => $value) {
                        $em->remove($value);
                        $em->flush();
                    }

                    //Eliminar productos vinculados a la oportunidad
                    $productosArrayObj = $em->getRepository('ERPCRMBundle:CrmProductoOportunidad')->findBy(array('oportunidad'=>$crmOportunidadObj));
                    foreach ($productosArrayObj as $key => $value) {
                        $em->remove($value);
                        $em->flush();
                    }
                        
                    // Personas asignadas a la oportunidad de venta
                    foreach ($personaArray as $value) {
                        // Objeto de la persona asignada a la oportunidad
                        $personaObj = $em->getRepository('ERPCRMBundle:CtlUsuario')->find($value);
                        
                        //Seteo en Entidad CrmAsignadoOportunidad
                        $crmAsignadoOportunidadObj = new CrmAsignadoOportunidad();
                        $crmAsignadoOportunidadObj->setOportunidad($crmOportunidadObj);
                        $crmAsignadoOportunidadObj->setUsuarioAsignado($personaObj);
                        $crmAsignadoOportunidadObj->setPrioridad(NULL);
                        $crmAsignadoOportunidadObj->setFechaLimite( new \DateTime($fecha[2] . '-' . $fecha[1] . '-' . $fecha[0]));

                        //Persistiendo $crmAsignadoOportunidadObj
                        $em->persist($crmAsignadoOportunidadObj);
                        $em->flush();
                    }
                    
                    // Productos relacionados a la oportunidad de venta
                    foreach ($productosArray as $key => $producto) {
                        // Objeto del producto relacionado a la oportunidad
                        $productoObj = $em->getRepository('ERPCRMBundle:CtlProducto')->find($producto);
                        
                        // Seteo en Entidad CrmProductoOportunidad
                        $productoOportunidadObj = new CrmProductoOportunidad();                        
                        $productoOportunidadObj->setOportunidad($crmOportunidadObj);
                        $productoOportunidadObj->setProducto($productoObj);
                        $productoOportunidadObj->setCantidad($cantidadArray[$key]);
                        
                        //Persistiendo productoOportunidadObj
                        $em->persist($productoOportunidadObj);
                        $em->flush();
                    }
                    
                    $serverUptate = $this->getParameter('app.servermsgupdate');
                    
                    $data['id']=$crmOportunidadObj->getId();
                    $data['msg']=$serverUptate;
                }
                
                $response->setData($data); 
            } catch (\Exception $e) {
                    
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
     * Search accounts
     *
     * @Route("/opportunities/accounttype/search/accounts", name="admin_opportunities_search_accounts_ajax",  options={"expose"=true}))
     */
    public function searchAccountsAjaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $id=$request->get("param1");
                $response = new JsonResponse();
                 
                $em = $this->getDoctrine()->getManager();
                //$object = $em->getRepository('ERPCRMBundle:CrmCuenta')->findBy(array('tipoCuenta' => $id, 'estado' => 1));
                
                $dql = "SELECT cta.id, cta.nombre as account, per.nombre, per.apellido "
                        . "FROM ERPCRMBundle:CrmCuenta cta "
                        . "JOIN cta.tipoCuenta tipo "
                        . "JOIN cta.contactoCuenta cc "
                        . "JOIN cc.contacto con "
                        . "JOIN con.persona per "
                        . "WHERE cta.estado = 1 AND cc.titular = 1 AND tipo.id = " . $id;

                $object = $em->createQuery($dql)                        
                        ->getResult();
                                                
                if(count($object)!=0){
                    $array=array();
                    
                    foreach ($object as $key => $value) {
                        $arrayAux = array();    
                        array_push($arrayAux, $value['id']);
                        
                        if($value['account'] == ' ' || $value['account'] == '') {
                            array_push($arrayAux, $value['nombre'] . ' ' . $value['apellido']);
                        } else {
                            array_push($arrayAux, $value['account']);
                        }    
                            
                        array_push($array, $arrayAux);
                    }
                    //var_dump($array);
                    //die();
                    $data['cuentas']=$array;
                }
                else{
                    $data['cuentas']=[];
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
     * Search probability of success selling opportunity
     *
     * @Route("/opportunities/salestage/search/probability", name="admin_opportunities_search_probability_ajax",  options={"expose"=true}))
     */
    public function searchProbabilitysAjaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $id=$request->get("param1");
                $response = new JsonResponse();
                 
                $em = $this->getDoctrine()->getManager();
                $object = $em->getRepository('ERPCRMBundle:CtlEtapaVenta')->find($id);
                
                
                if(count($object)!=0){
                    $data['probabilidad'] = $object->getProbabilidad();
                }
                else{
                    $data['probabilidad']='';
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
     * Retrieve opportunity
     *
     * @Route("/retrieve/ajax/opportunity", name="admin_opportunities_retrieve_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function retrieveAjaxOpportunityAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $data = array();
        
        try {
            $response = new JsonResponse();
            
            $id = $request->get("param1");            
            $crmOportunidadObj = $em->getRepository('ERPCRMBundle:CrmOportunidad')->find($id);
            
            $asignadosOportunidad = $em->getRepository('ERPCRMBundle:CrmAsignadoOportunidad')->findBy(array('oportunidad' => $crmOportunidadObj));
            $productosOportunidad = $em->getRepository('ERPCRMBundle:CrmProductoOportunidad')->findBy(array('oportunidad' => $crmOportunidadObj));
            
            if(count($crmOportunidadObj) != 0){
                $data['id'] = $id;
                $data['name'] = $crmOportunidadObj->getNombre();
                $data['description'] = $crmOportunidadObj->getDescripcion();
                $data['probability'] = $crmOportunidadObj->getProbabilidad();
                $data['fechaCierre'] = $crmOportunidadObj->getFechaCierre()->format('Y/m/d H:i:s');
                
                $data['compania'] = $crmOportunidadObj->getCuenta()->getId();                
                $data['tipoCuenta'] = $crmOportunidadObj->getCuenta()->getTipoCuenta()->getId();                
                $data['etapaVenta'] = $crmOportunidadObj->getEtapaVenta()->getId();                
                $fuente = $crmOportunidadObj->getFuentePrincipal();
                
                $data['fuente'] = $fuente->getId();
                
                if($crmOportunidadObj->getCampania()) {
                    $data['campania'] = $crmOportunidadObj->getCampania()->getId();    
                }
                
                $data['asignados'] = array();
                foreach ($asignadosOportunidad as $key => $value) {
                    $data['asignados'][$key] = $value->getUsuarioAsignado()->getId();
                }
                
                $data['productos'] = array();
                $data['cantProduct'] = array();
                foreach ($productosOportunidad as $key => $value) {
                    $data['productos'][$key] = $value->getProducto()->getId();
                    $data['cantProduct'][$key] = $value->getCantidad();
                }
                
                $dql = "SELECT cta.id, cta.nombre as account, per.nombre, per.apellido "
                        . "FROM ERPCRMBundle:CrmCuenta cta "
                        . "JOIN cta.tipoCuenta tipo "
                        . "JOIN cta.contactoCuenta cc "
                        . "JOIN cc.contacto con "
                        . "JOIN con.persona per "
                        . "WHERE cta.estado = 1 AND cc.titular = 1 AND tipo.id = " . $data['tipoCuenta'];

                $crmCuentasObj = $em->createQuery($dql)                        
                        ->getResult();
                
                if(count($crmCuentasObj) != 0){
                    $array=array();
                    
                    foreach ($crmCuentasObj as $key => $value) {
                        $arrayAux = array();    
                        array_push($arrayAux, $value['id']);
                        
                        if($value['account'] == ' ' || $value['account'] == '') {
                            array_push($arrayAux, $value['nombre'] . ' ' . $value['apellido']);
                        } else {
                            array_push($arrayAux, $value['account']);
                        }    
                            
                        array_push($array, $arrayAux);
                    }
                    
                    $data['cuentas']=$array;
                }
                else{
                    $data['cuentas']=[];
                }    
                
                $crmCotizacionesObj = $em->getRepository('ERPCRMBundle:CrmCotizacion')->findBy(array(
                                                                                            'oportunidad' => $crmOportunidadObj,
                                                                                            'estado'      => TRUE
                                                                                        ));
                
                if(count($crmCotizacionesObj) != 0){
                    $array=array();
                    
                    foreach ($crmCotizacionesObj as $key => $value) {
                        //var_dump($value);
                        //die();
                        
                        $arrayAux = array();    
                        array_push($arrayAux, $key + 1);
                        array_push($arrayAux, $value->getId());
                        array_push($arrayAux, $value->getUsuario()->getPersona()->getNombre() . ' ' . $value->getUsuario()->getPersona()->getApellido());
                        array_push($arrayAux, $value->getFechaRegistro()->format('Y-m-d H:i'));
                        array_push($arrayAux, $value->getEstadoCotizacion()->getNombre());
                        array_push($arrayAux, $value->getFechaVencimiento()->format('Y-m-d'));
                        //array_push($arrayAux, '<div id="'.$value->getId().'" style="text-align:left"><input style="z-index:5;" class="chkItemQ" type="checkbox"></div>');
                        
                        $itemsCotizacion = $em->getRepository('ERPCRMBundle:CrmDetalleCotizacion')->findBy(array('cotizacion' => $value));
                        $totalCotizacion = 0;
                        $totalTax = 0;
                        foreach ($itemsCotizacion as $key => $item) {
                            $tax = $item->getTax() / 100;
                            
                            $totalCotizacion+=($item->getCantidad() * $item->getValorUnitario());
                            $totalTax+=($item->getCantidad() * $item->getValorUnitario() * $tax);
                        }
                        
                        array_push($arrayAux, ($totalCotizacion + $totalTax));
                        
                        array_push($array, $arrayAux);
                    }
                    
                    $data['cotizaciones']=$array;
                }
                else{
                    $data['cotizaciones']=[];
                }  
                
                $sql = "SELECT ec.id as id, e.nombre as nombre FROM ERPCRMBundle:CrmEtiquetaOportunidad ec"
                            ." JOIN ec.etiqueta e "
                            ." JOIN ec.oportunidad c "
                            ." WHERE c.id=:idOportunidad";
                $tags = $em->createQuery($sql)
                                    ->setParameters(array('idOportunidad'=>$id))
                                    ->getResult();
                
                $data['tags']=$tags;
                
                
                $sql = "SELECT doc.id as id, doc.src as nombre, doc.estado FROM ERPCRMBundle:CrmDocumentoAdjuntoOportunidad doc"
                            ." JOIN doc.oportunidad c "
                            ." WHERE c.id=:idOportunidad ORDER BY doc.fechaRegistro DESC";
                $docs = $em->createQuery($sql)
                                    ->setParameters(array('idOportunidad'=>$id))
                                    ->getResult();
                
                $data['docs']=$docs;
                
            } else {
                $data['error'] = "Error";
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
        
        return $response;
    }
    
    /**
     * Delete opportunities
     *
     * @Route("/delete/opportunities", name="admin_oportunities_delete_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function deleteajaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $ids=$request->get("param1");
                $response = new JsonResponse();
                
                $em = $this->getDoctrine()->getManager();
                foreach ($ids as $key => $id) {
                    $object = $em->getRepository('ERPCRMBundle:CrmOportunidad')->find($id);    
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
     * Retrieve opportunity
     *
     * @Route("/retrieve/ajax/quotes-opportunity", name="admin_quotes_opportunities_retrieve_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function retrieveAjaxQuotesOpportunityAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $data = array();
        
        try {
            $response = new JsonResponse();
            
            $id = $request->get("param1");            
            $crmCotizacionObj = $em->getRepository('ERPCRMBundle:CrmCotizacion')->find($id);
            
            $itemsCotizacion = $em->getRepository('ERPCRMBundle:CrmDetalleCotizacion')->findBy(array('cotizacion' => $crmCotizacionObj));
            //$itemsCotizacion = $em->getRepository('ERPCRMBundle:CrmProductoOportunidad')->findBy(array('oportunidad' => $crmOportunidadObj));
            
            if(count($crmCotizacionObj) != 0){
                $data['id'] = $id;
                $data['status'] = $crmCotizacionObj->getEstadoCotizacion()->getId();                
                $data['assignedTo'] = $crmCotizacionObj->getUsuario()->getId();
                $data['gnalConditions'] = $crmCotizacionObj->getCondicionesGenerales();
                $data['validUntil'] = $crmCotizacionObj->getFechaVencimiento()->format('Y/m/d');
                                                
                $data['items'] = array();
                $data['cantItem'] = array();
                $data['priceItem'] = array();
                $data['totalItem'] = array();
                $data['tax'] = array();
                $data['taxTotal'] = 0;
                $data['priceSale'] = 0;
                $data['priceTotal'] = 0;
                
                foreach ($itemsCotizacion as $key => $value) {
                    $data['items'][$key] = $value->getProducto()->getId();
                    $data['cantItem'][$key] = $value->getCantidad();
                    $data['priceItem'][$key] = $value->getValorUnitario();
                    $data['tax'][$key] = $value->getTax();
                    $data['totalItem'][$key] = ($value->getCantidad() * $value->getValorUnitario());
                    $data['priceSale']+=$data['totalItem'][$key]; 
                    
                    $tax = $value->getTax() / 100;
                    $data['taxTotal']+=$data['totalItem'][$key] * $tax;                     
                }                
                
                $data['priceTotal'] = $data['priceSale'] + $data['taxTotal'];
            } else {
                $data['error'] = "Error";
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
        
        return $response;
    }
    
    /**
     * Save Opportunity
     *
     * @Route("/save/ajax/quote-opportunity", name="admin_opportunity_quotes_save_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function saveQuoteOpportunityAjaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                // Recuperando y seteando la zona horaria que se haya establecido
                $timeZone = $this->get('time_zone')->getTimeZone();
                date_default_timezone_set($timeZone->getNombre());
                
                $em = $this->getDoctrine()->getManager();
                $response = new JsonResponse();
                
                //Captura de parametros
                $idOportunidad = $_POST['idOportunidad'];                // Id de la Oportunidad 
                $idCotizacion = $_POST['txtIdQuote'];                    // Id de la cotización 
                $userQuote = $_POST['assignedUserQuote'];                // Usuario que ha realizado la cotización
                $status = $_POST['statusQuote'];                         // Estado/Etapa de la cotización
                $fechaRegistro = new \DateTime('now');                   // Fecha de registro de la cotización.
                $txtFechaVencimiento = $_POST['txtDateExpirationQuote']; // Fecha de vencimiento de la cotización.
                $conditionssQuote = $_POST['conditionssQuote'];          // Condiciones generales de la cotización.                
                $estado = 1;                                             // Estado de la cotización.
                
                
                // Cantidad, productos, precios y tax asociados a la cotización
                $cantidadArray = $_POST['txtCantItemQ'];          // Array de cantidad de cada producto.
                $productosArray = $_POST['sItemQ'];               // Array de productos asociados a la cotización realizada.
                $preciosArray = $_POST['txtPriceItemQ'];          // Array de precios de cada producto.
                $taxArray = $_POST['txtTaxItemQ'];                // Array de tax asociado a cada producto.

                //Busqueda objetos a partir de Id's
                $crmOportunidadObj = $em->getRepository('ERPCRMBundle:CrmOportunidad')->find($idOportunidad);       // Objeto de la oportunidad vinculada a la cotización
                $crmUsuarioObj = $em->getRepository('ERPCRMBundle:CtlUsuario')->find($userQuote);                   // Objeto del usuario seleccionado
                $crmEstadoCotObj = $em->getRepository('ERPCRMBundle:CrmEstadoCotizacion')->find($status);           // Objeto de la estado de la cotización seleccionada
                
                
                // Estableciendo el formato de fecha para seteo en la entidad (YYYY-mm-dd HH:ii)
                $fecha = explode('/', $txtFechaVencimiento);
                $fechaCierre = $fecha[0] . '-' . $fecha[1] . '-' . $fecha[2];
                    
                if($idCotizacion == ''){                    
                    //Seteo en Entidad CrmCotizacion
                    $crmCotizacionObj = new CrmCotizacion();
                    
                    $crmCotizacionObj->setOportunidad($crmOportunidadObj);
                    $crmCotizacionObj->setFechaRegistro($fechaRegistro);
                    $crmCotizacionObj->setFechaVencimiento( new \DateTime($fechaCierre));
                    $crmCotizacionObj->setEstadoCotizacion($crmEstadoCotObj);
                    $crmCotizacionObj->setUsuario($crmUsuarioObj);
                    $crmCotizacionObj->setCondicionesGenerales($conditionssQuote);
                                    
                    //Persistencia en $crmCotizacionObj
                    $em->persist($crmCotizacionObj);
                    $em->flush();
                    
                    
                    // Productos relacionados a la cotizacion
                    foreach ($productosArray as $key => $producto) {
                        // Objeto del producto relacionado a la cotizacion
                        $productoObj = $em->getRepository('ERPCRMBundle:CtlProducto')->find($producto);
                        
                        // Seteo en Entidad CrmDetalleCotizacion
                        $detalleCotizacionObj = new CrmDetalleCotizacion();                        
                        $detalleCotizacionObj->setCotizacion($crmCotizacionObj);
                        $detalleCotizacionObj->setProducto($productoObj);
                        $detalleCotizacionObj->setCantidad($cantidadArray[$key]);
                        $detalleCotizacionObj->setValorUnitario($preciosArray[$key]);
                        $detalleCotizacionObj->setTax($taxArray[$key]);
                        
                        //Persistiendo $detalleCotizacionObj
                        $em->persist($detalleCotizacionObj);
                        $em->flush();
                    }
                                
                    $serverSave = $this->getParameter('app.serverMsgSave');
                    
                    $data['id']=$crmCotizacionObj->getId();
                    $data['msg']=$serverSave;
                }//Fin de if id, inserción
                // Else para la modificación del objeto CrmCotizacion y sus tablas dependientes
                else {                    
                    $crmCotizacionObj = $em->getRepository('ERPCRMBundle:CrmCotizacion')->find($idCotizacion);

                    //Seteo en Entidad CrmOportunidad
                    $crmCotizacionObj->setEstadoCotizacion($crmEstadoCotObj);
                    $crmCotizacionObj->setFechaVencimiento( new \DateTime($fechaCierre));
                    $crmCotizacionObj->setUsuario($crmUsuarioObj);
                    $crmCotizacionObj->setCondicionesGenerales($conditionssQuote);
                    
                    
                    //Persistiendo $crmOportunidadObj
                    $em->merge($crmCotizacionObj);
                    $em->flush();

                    //Eliminar productos vinculados a la oportunidad
                    $productosArrayObj = $em->getRepository('ERPCRMBundle:CrmDetalleCotizacion')->findBy(array('cotizacion'=>$crmCotizacionObj));
                    foreach ($productosArrayObj as $key => $value) {
                        $em->remove($value);
                        $em->flush();
                    }
                        
                    
                    // Productos relacionados a la cotizacion
                    foreach ($productosArray as $key => $producto) {
                        // Objeto del producto relacionado a la cotizacion
                        $productoObj = $em->getRepository('ERPCRMBundle:CtlProducto')->find($producto);
                        
                        // Seteo en Entidad CrmDetalleCotizacion
                        $detalleCotizacionObj = new CrmDetalleCotizacion();                        
                        $detalleCotizacionObj->setCotizacion($crmCotizacionObj);
                        $detalleCotizacionObj->setProducto($productoObj);
                        $detalleCotizacionObj->setCantidad($cantidadArray[$key]);
                        $detalleCotizacionObj->setValorUnitario($preciosArray[$key]);
                        $detalleCotizacionObj->setTax($taxArray[$key]);
                        
                        //Persistiendo $detalleCotizacionObj
                        $em->persist($detalleCotizacionObj);
                        $em->flush();
                    }
                    
                    $serverUptate = $this->getParameter('app.servermsgupdate');
                    
                    $data['id']=$crmCotizacionObj->getId();
                    $data['msg']=$serverUptate;
                }
                
                $crmCotizacionesObj = $em->getRepository('ERPCRMBundle:CrmCotizacion')->findBy(array(
                                                                                            'oportunidad' => $crmOportunidadObj,
                                                                                            'estado'      => TRUE
                                                                                        ));
                
                if(count($crmCotizacionesObj) != 0){
                    $array=array();
                    
                    foreach ($crmCotizacionesObj as $key => $value) {
                        $arrayAux = array();    
                        array_push($arrayAux, $key + 1);
                        array_push($arrayAux, $value->getId());
                        array_push($arrayAux, $value->getUsuario()->getPersona()->getNombre() . ' ' . $value->getUsuario()->getPersona()->getApellido());
                        array_push($arrayAux, $value->getFechaRegistro()->format('Y-m-d H:i'));
                        array_push($arrayAux, $value->getEstadoCotizacion()->getNombre());
                        array_push($arrayAux, $value->getFechaVencimiento()->format('Y-m-d'));
                        
                        $itemsCotizacion = $em->getRepository('ERPCRMBundle:CrmDetalleCotizacion')->findBy(array('cotizacion' => $value));
                        $totalCotizacion = 0;
                        $totalTax = 0;
                        foreach ($itemsCotizacion as $key => $item) {
                            $tax = $item->getTax() / 100;
                            
                            $totalCotizacion+=($item->getCantidad() * $item->getValorUnitario());
                            $totalTax+=($item->getCantidad() * $item->getValorUnitario() * $tax);
                        }
                        
                        array_push($arrayAux, ($totalCotizacion + $totalTax));
                        
                        array_push($array, $arrayAux);
                    }
                    
                    $data['cotizaciones']=$array;
                }
                else{
                    $data['cotizaciones']=[];
                }
                
                $response->setData($data); 
            } catch (\Exception $e) {
                    
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
     * Delete Quotes
     *
     * @Route("/delete/ajax/quotes", name="admin_quotes_delete_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function deleteQuotesAjaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $ids=$request->get("param1");
                $idOportunidad = $request->get("param2");
                $response = new JsonResponse();
                
                $em = $this->getDoctrine()->getManager();
                $crmOportunidadObj = $em->getRepository('ERPCRMBundle:CrmCotizacion')->find($idOportunidad);    
                
                foreach ($ids as $key => $id) {
                    $object = $em->getRepository('ERPCRMBundle:CrmCotizacion')->find($id);    
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
                
                $crmCotizacionesObj = $em->getRepository('ERPCRMBundle:CrmCotizacion')->findBy(array(
                                                                                            'oportunidad' => $crmOportunidadObj,
                                                                                            'estado'      => TRUE
                                                                                        ));
                
                if(count($crmCotizacionesObj) != 0){
                    $array=array();
                    
                    foreach ($crmCotizacionesObj as $key => $value) {
                        //var_dump($value);
                        //die();
                        
                        $arrayAux = array();    
                        array_push($arrayAux, $key + 1);
                        array_push($arrayAux, $value->getId());
                        array_push($arrayAux, $value->getUsuario()->getPersona()->getNombre() . ' ' . $value->getUsuario()->getPersona()->getApellido());
                        array_push($arrayAux, $value->getFechaRegistro()->format('Y-m-d H:i'));
                        array_push($arrayAux, $value->getEstadoCotizacion()->getNombre());
                        array_push($arrayAux, $value->getFechaVencimiento()->format('Y-m-d'));
                        
                        $itemsCotizacion = $em->getRepository('ERPCRMBundle:CrmDetalleCotizacion')->findBy(array('cotizacion' => $value));
                        $totalCotizacion = 0;
                        $totalTax = 0;
                        foreach ($itemsCotizacion as $key => $item) {
                            $tax = $item->getTax() / 100;
                            
                            $totalCotizacion+=($item->getCantidad() * $item->getValorUnitario());
                            $totalTax+=($item->getCantidad() * $item->getValorUnitario() * $tax);
                        }
                        
                        array_push($arrayAux, ($totalCotizacion + $totalTax));
                        
                        array_push($array, $arrayAux);
                    }
                    
                    $data['cotizaciones']=$array;
                }
                else{
                    $data['cotizaciones']=[];
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
}
