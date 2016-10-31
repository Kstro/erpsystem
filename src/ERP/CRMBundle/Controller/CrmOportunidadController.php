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
            $tiposCuenta = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->findBy(array('estado'=>1));

            //Etapas de venta
            $etapasVenta = $em->getRepository('ERPCRMBundle:CtlEtapaVenta')->findBy(array('estado'=>1));

            //Campañas
            $campanias = $em->getRepository('ERPCRMBundle:CrmCampania')->findBy(array('estado'=>1));
            
            //Productos
            $productos = $em->getRepository('ERPCRMBundle:CtlProducto')->findBy(array('estado'=>1));

            //Persona-usuarios
            $personas = $em->getRepository('ERPCRMBundle:CtlUsuario')->findBy(array('estado'=>1));

            return $this->render('crm_oportunidad/index.html.twig', array(
                'tiposCuenta'=>$tiposCuenta,
                'fuentes'=>$fuentes,
                'etapasVenta'=>$etapasVenta,
                'campanias'=>$campanias,
                'productos'=>$productos,
                'personas'=>$personas,
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
        $rowsTotal = $em->getRepository('ERPCRMBundle:CrmOportunidad')->findAll();
        
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
                $orderByText = "created";
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
                    . "CONCAT('<div style=\"text-align: left;\">', cta.nombre, '</div>') as account "
                    . "FROM ERPCRMBundle:CrmOportunidad opo "
                    . "JOIN opo.etapaVenta sta "
                    . "JOIN opo.cuenta cta "
                    . "WHERE CONCAT(upper(opo.nombre), ' ' , upper(opo.fechaRegistro), ' ' , upper(opo.fechaCierre), ' ' , upper(sta.nombre), ' ' , upper(cta.nombre)) LIKE upper(:busqueda) "
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
                    . "CONCAT('<div style=\"text-align: left;\">', cta.nombre, '</div>') as account "
                    . "FROM ERPCRMBundle:CrmOportunidad opo "
                    . "JOIN opo.etapaVenta sta "
                    . "JOIN opo.cuenta cta "
                    . "WHERE CONCAT(upper(opo.nombre), ' ' , upper(opo.fechaRegistro), ' ' , upper(opo.fechaCierre), ' ' , upper(sta.nombre), ' ' , upper(cta.nombre)) LIKE upper(:busqueda) "
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
                    . "CONCAT('<div style=\"text-align: left;\">', cta.nombre, '</div>') as account "
                    . "FROM ERPCRMBundle:CrmOportunidad opo "
                    . "JOIN opo.etapaVenta sta "
                    . "JOIN opo.cuenta cta "
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
                $fechaCierre = $fecha[0] . '-' . $fecha[1] . '-' . $fecha[2] . ' ' . $fcierre[1] . ':00';
                
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
                        $crmCuentaObj = $em->getRepository('ERPCRMBundle:CrmOportunidad')->find($idOportunidad);
                        
                        $crmCuentaObj->setTipoCuenta($crmTipoCuentaObj);
                        $crmCuentaObj->setIndustria($industriaObj);
                        $crmCuentaObj->setClientePotencial($clientePotencial);
                        $crmCuentaObj->setNivelSatisfaccion($nivelSatisfaccion);
                        $crmCuentaObj->setTipoEntidad($tipoEntidadObj);
                        $crmCuentaObj->setNombre($nombreCuenta);
                        $crmCuentaObj->setDescripcion($descripcionCuenta);
                        // $crmCuentaObj->setFechaRegistro($fechaRegistro);
                        $crmCuentaObj->setSitioWeb($sitioWeb);
                        $crmCuentaObj->setEstado(1);
                                        
                        //Persist crmCuentaObj
                        $em->merge($crmCuentaObj);
                        $em->flush();
                        
                        //Eliminar telefonos
                        $ctlTelefonoArrayObj = $em->getRepository('ERPCRMBundle:CtlTelefono')->findBy(array('cuenta'=>$idCuenta));
                        foreach ($ctlTelefonoArrayObj as $key => $value) {
                            $em->remove($value);
                            $em->flush();
                        }

                        //Eliminar correos
                        $ctlCorreoArrayObj = $em->getRepository('ERPCRMBundle:CtlCorreo')->findBy(array('cuenta'=>$idCuenta));
                        foreach ($ctlCorreoArrayObj as $key => $value) {
                            $em->remove($value);
                            $em->flush();
                        }

                        //Eliminar direccion
                        $ctlDireccionArrayObj = $em->getRepository('ERPCRMBundle:CtlDireccion')->findBy(array('cuenta'=>$idCuenta));
                        foreach ($ctlDireccionArrayObj as $key => $value) {
                            $em->remove($value);
                            $em->flush();
                        }

                        //Tabla ctlPersona
                        $ctlPersonaObj = $em->getRepository('ERPCRMBundle:CtlPersona')->find($idPersona);
                        $ctlPersonaObj->setNombre($nombrePersona);
                        $ctlPersonaObj->setApellido($apellidoPersona);
                        $ctlPersonaObj->setGenero($genero);
                        // $ctlPersonaObj->setFechaRegistro($fechaRegistro);
                        $ctlPersonaObj->setSucursal($sucursal);
                        $ctlPersonaObj->setTratamientoProtocolario($tratamientoProtocolarioObj);

                        //Persist ctlPersonaObj
                        $em->merge($ctlPersonaObj);
                        $em->flush();




                    //Tabla ctlCorreo
                    foreach ($emailArray as $key => $correo) {
                        $ctlCorreoObj = new CtlCorreo();
                        $ctlCorreoObj->setEmpresa(null);
                        $ctlCorreoObj->setPersona(null);
                        $ctlCorreoObj->setCuenta($crmCuentaObj);
                        $ctlCorreoObj->setEmail($correo);
                        $ctlCorreoObj->setEstado(1);
                        //Persist ctlCorreo
                        $em->persist($ctlCorreoObj);
                        $em->flush();

                    }
                    
                    $serverSave = $this->getParameter('app.serverMsgSave');
                    
                    $data['id']=$crmOportunidadObj->getId();
                    $data['msg']=$serverSave;
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
                $object = $em->getRepository('ERPCRMBundle:CrmCuenta')->findBy(array('tipoCuenta' => $id, 'estado' => 1));
                
                if(count($object)!=0){
                    $array=array();
                    
                    foreach ($object as $key => $value) {
                        $arrayAux = array();    
                        array_push($arrayAux, $value->getId());
                        array_push($arrayAux, $value->getNombre());
                        array_push($array, $arrayAux);
                    }
                    
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
        $response = new JsonResponse();
        
        try {
            $id = $request->get("param1");            
            $crmOportunidadObj = $em->getRepository('ERPCRMBundle:CrmOportunidad')->find($id);
            
            if(count($crmOportunidadObj) != 0){
                $data['name'] = $crmOportunidadObj->getNombre();
                $data['description'] = $crmOportunidadObj->getDescripcion();
                $data['probability'] = $crmOportunidadObj->getProbabilidad();
                
                $data['compania'] = $crmOportunidadObj->getCuenta()->getId();                
                $data['etapaVenta'] = $crmOportunidadObj->getEtapaVenta()->getId();                
                $fuente = $crmOportunidadObj->getFuentePrincipal();
                
                $data['fuente'] = $fuente->getId();
                
                if($data['fuente'] == 1) {
                    
                }
                
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
    }
}
