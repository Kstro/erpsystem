<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CrmOportunidad;

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
            $personas = $em->getRepository('ERPCRMBundle:CtlUsuario')->findAll();

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
                    . "CONCAT('<div id=\"',opo.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
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
                    . "CONCAT('<div id=\"',opo.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
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
                    . "CONCAT('<div id=\"',opo.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as check, "
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
                $idOportunidad = $_POST['txtId'];       // Id de Oportunidad
                $nombreOportunidad = $_POST['txtName']; // Nombre de la oportunidad de venta
                $tipoCuenta = $_POST['tipoCuenta'];     // Tipo de cuenta seleccionada
                $cuenta = $_POST['cuenta'];             // Cuenta vinculada a la oportunidad.
                $etapaVenta = $_POST['etapaVenta'];     // Etapa que se encuentra la oportunidad de venta.
                $probabilidad = $_POST['txtProbability'];//Probabilidad de que la venta se realice con exito.
                $fechaRegistro = new \DateTime('now');  // Fecha de registro de la oportunidad de venta.
                $txtFechaCierre = $_POST['txtFechaCierre'];// Fecha de cierre de la oportunidad de venta.
                $descripcion = $_POST['descripcion'];   // Descripcion de la oportunidad de venta.
                $fuente = $_POST['fuente'];             // Fuente de origen de la oportunidad de venta.
                $campania = $_POST['campania'];         // Campaña de donde se obtuvo la oportunidad de venta.
                $estado = 1;                            // Estado de la oporunidad de venta
                
                // Personas
                $personaArray = $_POST['responsable'];  // Array de personas asignadas a la oportunidad de venta.

                //Cantidad y productos asociados a la oportunidad
                $cantidadArray = $_POST['cantidad'];    // Array de cantidad de cada producto.
                $productosArray = $_POST['sProducto'];  // Array de productos asociados a la oportunidad de venta.

                //Busqueda objetos a partir de ids
                $crmTipoCuentaObj = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->find($tipoCuenta); // Objeto del tipo de cuenta seleccionado
                $crmCuentaObj = $em->getRepository('ERPCRMBundle:CrmCuenta')->find($cuenta);             // Objeto de la cuenta seleccionada
                $crmEtapaVentaObj = $em->getRepository('ERPCRMBundle:CtlEtapaVenta')->find($etapaVenta); // Objeto de la etapa de venta seleccionada
                
                if($fuente == 1){
                    $crmCampaniaObj = $em->getRepository('ERPCRMBundle:CrmCampania')->find($campania);   // Objeto de la campaña seleccionada
                }
                
                $fcierre = explode(' ', $txtFechaCierre);
                $fecha = explode('/', $fcierre[0]);
                $fechaCierre = $fecha[2] . '-' . $fecha[1] . '-' . $fecha[0] . ' ' . $fcierre[1];
                
                

                if($idOportunidad == ''){

                    //Seteo en Entidad crmOportunidad
                    $crmOportunidadObj = new CrmOportunidad();
                    
                    $crmOportunidadObj->setNombre($nombreOportunidad);
                    $crmOportunidadObj->setFechaRegistro($fechaRegistro);
                    $crmOportunidadObj->setFechaCierre($fechaCierre);
                    $crmOportunidadObj->setDescripcion($descripcion);
                    $crmOportunidadObj->setEtapaVenta($crmEtapaVentaObj);
                                    
                    //Persist crmOportunidadObj
                    $em->persist($crmOportunidadObj);
                    $em->flush();
                    
                    var_dump($_POST);
                    die();
                
                    //Tabla ctlPersona
                    $ctlPersonaObj = new CtlPersona();
                    $ctlPersonaObj->setNombre($nombrePersona);
                    $ctlPersonaObj->setApellido($apellidoPersona);
                    $ctlPersonaObj->setGenero($genero);
                    $ctlPersonaObj->setFechaRegistro($fechaRegistro);
                    $ctlPersonaObj->setSucursal($sucursal);
                    $ctlPersonaObj->setTratamientoProtocolario($tratamientoProtocolarioObj);

                    //Persist ctlPersonaObj
                    $em->persist($ctlPersonaObj);
                    $em->flush();


                    //Tabla crmContacto
                    $crmContactoObj = new CrmContacto();
                    $crmContactoObj->setEstado(1);
                    $crmContactoObj->setPersona($ctlPersonaObj);//Set llave foranea de ctlPersonaObj
                    
                    //Persist crmContactoObj
                    $em->persist($crmContactoObj);
                    $em->flush();

                    //Tabla crmContactoCuenta
                    $crmContactoCuentaObj = new CrmContactoCuenta();
                    $crmContactoCuentaObj->setCuenta($crmCuentaObj);
                    $crmContactoCuentaObj->setContacto($crmContactoObj);
                    
                    //Persist crmContactoCuenta
                    $em->persist($crmContactoCuentaObj);
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


                    //Tabla ctlTelefono
                    $phoneLenght=count($phoneArray)-1;//Cantidad de telefono ingresados, menos 1 para index de array
                    //var_dump($phoneTypeArray[0]);
                    $ctlTipoTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->find($phoneTypeArray[0]);//Para definir la variable $ctlTipoTelefonoObj
                    foreach ($phoneArray as $key => $phone) {
                                          
                        $ctlTelefonoObj = new CtlTelefono();
                        $ctlTelefonoObj->setCuenta($crmCuentaObj);
                        //var_dump($key);
                        if ($key<$phoneLenght && $key!=0) {
                            if ($phoneTypeArray[$key]==$phoneTypeArray[$key-1]) {
                                //No buscar en la base el tipo de telefono
                                $ctlTelefonoObj->setTipoTelefono($ctlTipoTelefonoObj);
                                //var_dump('no buscar base tipo telefono');
                            } else {
                                //Buscar en la base el tipo de telefono
                                $ctlTipoTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->find($phoneTypeArray[$key]);
                                $ctlTelefonoObj->setTipoTelefono($ctlTipoTelefonoObj);
                                //var_dump('buscar base tipo telefono');
                            }
                         
                        } else {
                                //Buscar en la base el tipo de telefono, primera iteracion debe buscar el tipo de telefono
                                //$ctlTipoTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->find($phoneTypeArray[$key]);
                                $ctlTelefonoObj->setTipoTelefono($ctlTipoTelefonoObj);
                                //var_dump('no buscar base tipo telefono');
                        }
                        $ctlTelefonoObj->setNumTelefonico($phoneArray[$key]);
                        $ctlTelefonoObj->setExtension($phoneExtArray[$key]);
                        $ctlTelefonoObj->setPersona(null);
                        $ctlTelefonoObj->setEmpresa(null);
                        $ctlTelefonoObj->setSucursal(null);
                        //Persist ctlTelefono
                        $em->persist($ctlTelefonoObj);
                        $em->flush();
                    }

                    //Tabla ctlDireccion
                    $addressLenght=count($addressArray)-1;//Cantidad de direccion ingresados, menos 1 para index de array
                    // var_dump(count($addressArray));
                    // var_dump($addressLenght);
                    foreach ($addressArray as $key => $val) {
                                          
                        $ctlDireccionObj = new CtlDireccion();
                        $ctlDireccionObj->setCuenta($crmCuentaObj);
                        if ($key<$addressLenght && $key!=0) {
                            if ($addressCityArray[$key]==$addressCityArray[$key-1]) {
                                //No buscar en la base ciudad
                                $ctlDireccionObj->setCiudad($ctlCiudadObj);
                            } else {
                                //Buscar en la base la ciudad
                                $ctlCiudadObj = $em->getRepository('ERPCRMBundle:CtlCiudad')->find($addressCityArray[$key]);
                                $ctlDireccionObj->setCiudad($ctlCiudadObj);
                            }
                         
                        } else {
                                //Buscar en la base la ciudad, primera iteracion debe buscar ciudad
                                $ctlCiudadObj = $em->getRepository('ERPCRMBundle:CtlCiudad')->find($addressCityArray[$key]);
                                $ctlDireccionObj->setCiudad($ctlCiudadObj);
                        }
                        $ctlDireccionObj->setDireccion($addressArray[$key]);
                        $ctlDireccionObj->setPersona(null);
                        $ctlDireccionObj->setEmpresa(null);
                        
                        $ctlDireccionObj->setLatitud(0);
                        $ctlDireccionObj->setLongitud(0);
                        $ctlDireccionObj->setEstado(1);
                        //Persist ctlDireccion
                        $em->persist($ctlDireccionObj);
                        $em->flush();
                        
                    }                

                    //Manejo de imagen
                    $nombreTmp = $_FILES['file']['name'];
                    if ($nombreTmp!='') {
                        //Buscar en la base la ciudad, primera iteracion debe buscar ciudad
                        
                        $path = $this->getParameter('photo.cuentas');
                        //var_dump($path);
                        $fecha = date('Y-m-d-H-i-s');
                        $extensionTmp = $_FILES['file']['type'];
                        $extensionArray= explode('/', $extensionTmp);
                        $extension = $extensionArray[1];
                        $nombreArchivo =$fecha.".".$extension;
                        //var_dump($nombreArchivo);
                        if(move_uploaded_file($_FILES['file']['tmp_name'], $path.$nombreArchivo)){
                            $crmFoto = $em->getRepository('ERPCRMBundle:CrmFoto')->find($crmCuentaObj->getId());
                            if (count($crmFoto)!=0) {
                                //unlink($path.$crmFoto->getSrc());
                                //crmFoto
                                $crmFoto->setSrc($nombreArchivo);
                                $em->merge($crmFoto);
                                $em->flush();
                            }
                            else{
                                //crmFoto
                                $crmFoto = new CrmFoto();
                                $crmFoto->setCuenta($crmCuentaObj);
                                $crmFoto->setPersona(null);
                                $crmFoto->setEstado(1);
                                $crmFoto->setSrc($nombreArchivo);
                                $em->persist($crmFoto);
                                $em->flush();
                            }
                        }
                        else{//Error al subir foto

                        }
                    } else {//Foto vacia
                        //var_dump('No file');
                    }
                    $serverSave = $this->getParameter('app.serverMsgSave');
                    $data['id1']=$crmCuentaObj->getId();
                    $data['id2']=$ctlPersonaObj->getId();
                    $data['msg']=$serverSave;
                }//Fin de if id, inserción
                //else para la modificación del objeto crmCuenta(proveedores) y sus tablas dependientes
                else{
                    // var_dump($phoneTypeArray);
                    // die();
                        $crmCuentaObj = $em->getRepository('ERPCRMBundle:CrmCuenta')->find($idCuenta);
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
                        // var_dump($idCuenta);
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


                    //Tabla ctlTelefono
                    $phoneLenght=count($phoneArray);//Cantidad de telefono ingresados, menos 1 para index de array
                    //var_dump($phoneTypeArray[0]);
                    $ctlTipoTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->find($phoneTypeArray[0]);//Para definir la variable $ctlTipoTelefonoObj

                    foreach ($phoneArray as $key => $phone) {
                            // var_dump('for');
                        $ctlTelefonoObj = new CtlTelefono();
                        $ctlTelefonoObj->setCuenta($crmCuentaObj);
                        // var_dump("key".$key);
                        // var_dump("\nphone length".$phoneLenght);
                        // var_dump("\n expresion".($key<$phoneLenght && $key!=0));
                        if ($key<$phoneLenght && $key!=0) {
                            // var_dump('if');
                            if ($phoneTypeArray[$key]==$phoneTypeArray[$key-1]) {
                                //No buscar en la base el tipo de telefono
                                $ctlTelefonoObj->setTipoTelefono($ctlTipoTelefonoObj);
                                //var_dump('no buscar base tipo telefono');
                            } else {
                                //Buscar en la base el tipo de telefono
                                $ctlTipoTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->find($phoneTypeArray[$key]);
                                $ctlTelefonoObj->setTipoTelefono($ctlTipoTelefonoObj);
                                //var_dump('buscar base tipo telefono');
                            }
                         
                        } else {
                                // var_dump('else');
                                //Buscar en la base el tipo de telefono, primera iteracion debe buscar el tipo de telefono
                                //$ctlTipoTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->find($phoneTypeArray[$key]);
                                $ctlTelefonoObj->setTipoTelefono($ctlTipoTelefonoObj);
                                //var_dump('no buscar base tipo telefono');
                        }
                        $ctlTelefonoObj->setNumTelefonico($phoneArray[$key]);
                        $ctlTelefonoObj->setExtension($phoneExtArray[$key]);
                        $ctlTelefonoObj->setPersona(null);
                        $ctlTelefonoObj->setEmpresa(null);
                        $ctlTelefonoObj->setSucursal(null);
                        //Persist ctlTelefono
                        $em->persist($ctlTelefonoObj);
                        $em->flush();
                    }

                    //Tabla ctlDireccion
                    $addressLenght=count($addressArray);//Cantidad de direccion ingresados, menos 1 para index de array
                    foreach ($addressArray as $key => $phone) {
                                          
                        $ctlDireccionObj = new CtlDireccion();
                        $ctlDireccionObj->setCuenta($crmCuentaObj);
                        if ($key<$addressLenght && $key!=0) {
                            if ($addressCityArray[$key]==$addressCityArray[$key-1]) {
                                //No buscar en la base ciudad
                                $ctlDireccionObj->setCiudad($ctlCiudadObj);
                            } else {
                                //Buscar en la base la ciudad
                                $ctlCiudadObj = $em->getRepository('ERPCRMBundle:CtlCiudad')->find($addressCityArray[$key]);
                                $ctlDireccionObj->setCiudad($ctlCiudadObj);
                            }
                         
                        } else {
                                //Buscar en la base la ciudad, primera iteracion debe buscar ciudad
                                $ctlCiudadObj = $em->getRepository('ERPCRMBundle:CtlCiudad')->find($addressCityArray[$key]);
                                $ctlDireccionObj->setCiudad($ctlCiudadObj);
                        }
                        $ctlDireccionObj->setDireccion($addressArray[$key]);
                        $ctlDireccionObj->setPersona(null);
                        $ctlDireccionObj->setEmpresa(null);
                        
                        $ctlDireccionObj->setLatitud(0);
                        $ctlDireccionObj->setLongitud(0);
                        $ctlDireccionObj->setEstado(1);
                        //Persist ctlDireccion
                        $em->persist($ctlDireccionObj);
                        $em->flush();
                        
                    }                

                    //Manejo de imagen
                    $nombreTmp = $_FILES['file']['name'];
                    if ($nombreTmp!='') {
                        //Buscar en la base la ciudad, primera iteracion debe buscar ciudad
                        
                        $path = $this->getParameter('photo.cuentas');
                        //var_dump($path);
                        $fecha = date('Y-m-d-H-i-s');
                        $extensionTmp = $_FILES['file']['type'];
                        $extensionArray= explode('/', $extensionTmp);
                        $extension = $extensionArray[1];
                        $nombreArchivo =$fecha.".".$extension;
                        //var_dump($nombreArchivo);
                        if(move_uploaded_file($_FILES['file']['tmp_name'], $path.$nombreArchivo)){
                            $crmFoto = $em->getRepository('ERPCRMBundle:CrmFoto')->findBy(array('cuenta'=>$idCuenta));
                            if (count($crmFoto)!=0) {
                                unlink($path.$crmFoto[0]->getSrc());
                                //crmFoto
                                $crmFoto[0]->setSrc($nombreArchivo);
                                $em->merge($crmFoto[0]);
                                $em->flush();
                            }
                            else{
                                //crmFoto
                                $crmFoto = new CrmFoto();
                                $crmFoto->setCuenta($crmCuentaObj);
                                $crmFoto->setPersona(null);
                                $crmFoto->setEstado(1);
                                $crmFoto->setSrc($nombreArchivo);
                                $em->persist($crmFoto);
                                $em->flush();
                            }
                        }
                        else{//Error al subir foto

                        }
                    } else {//Foto vacia
                        //var_dump('No file');
                    }
                        $serverSave = $this->getParameter('app.serverMsgUpdate');
                        $data['msg']=$serverSave;
                        $data['id1']=$idCuenta;
                        $data['id2']=$idPersona;
                }
                $em->getConnection()->commit();
                $em->close();
                $response->setData($data); 
            } catch (\Exception $e) {
                    $em->getConnection()->rollback();
                    $em->close();
                     // var_dump($e);
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
}
