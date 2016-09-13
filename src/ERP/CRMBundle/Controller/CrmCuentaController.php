<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CrmCuenta;
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
 * CrmCuenta controller.
 *
 * @Route("/admin/providers")
 */
class CrmCuentaController extends Controller
{
    /**
     * Lists all CrmCuenta entities.
     *
     * @Route("/", name="admin_providers_accounts_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        try{
            $em = $this->getDoctrine()->getManager();

            // $crmCuentas = $em->getRepository('ERPCRMBundle:CrmCuenta')->findAll();
            $response = new JsonResponse();
            //Titulo protocolario
            $items = $em->getRepository('ERPCRMBundle:CtlTratamientoProtocolario')->findAll();
            //Estado
            $estados = $em->getRepository('ERPCRMBundle:CtlEstado')->findAll();
            //Ciudad
            $ciudades = $em->getRepository('ERPCRMBundle:CtlCiudad')->findAll();
            //Tipo persona
            //$personas = $em->getRepository('ERPCRMBundle:CtlTipoEntidad')->findBy(array('estado'=>1));
            $personas = $em->getRepository('ERPCRMBundle:CtlTipoEntidad')->findAll();
            //Tipo industria
            $industrias = $em->getRepository('ERPCRMBundle:CtlIndustria')->findAll(array('estado'=>1));
            //Tipos telefono
            $tiposTelefono = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->findAll();
            return $this->render('crmcuenta/index_provider.html.twig', array(
                // 'crmCuentas' => $crmCuentas,
                'items'=>$items,
                'estados'=>$estados,
                'ciudades'=>$ciudades,
                'personas'=>$personas,
                'industrias'=>$industrias,
                'tiposTelefono'=>$tiposTelefono,
                'menuProveedorA' => true,
            ));
        
        } catch (\Exception $e) {  
            $serverOffline = $this->getParameter('app.serverOffline');
            $data['error'] = $serverOffline.'. CODE: '.$e->getErrorCode();
            $response->setData($data);
            return $response;
           
        }
    }

    /**
     * Creates a new CrmCuenta entity.
     *
     * @Route("/new", name="admin_accounts_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $crmCuentum = new CrmCuenta();
        $form = $this->createForm('ERP\CRMBundle\Form\CrmCuentaType', $crmCuentum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crmCuentum);
            $em->flush();

            return $this->redirectToRoute('admin_accounts_show', array('id' => $crmCuentum->getId()));
        }

        return $this->render('crmcuenta/new.html.twig', array(
            'crmCuentum' => $crmCuentum,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CrmCuenta entity.
     *
     * @Route("/{id}", name="admin_accounts_show")
     * @Method("GET")
     */
    public function showAction(CrmCuenta $crmCuentum)
    {
        $deleteForm = $this->createDeleteForm($crmCuentum);

        return $this->render('crmcuenta/show.html.twig', array(
            'crmCuentum' => $crmCuentum,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CrmCuenta entity.
     *
     * @Route("/{id}/edit", name="admin_accounts_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CrmCuenta $crmCuentum)
    {
        $deleteForm = $this->createDeleteForm($crmCuentum);
        $editForm = $this->createForm('ERP\CRMBundle\Form\CrmCuentaType', $crmCuentum);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crmCuentum);
            $em->flush();

            return $this->redirectToRoute('admin_accounts_edit', array('id' => $crmCuentum->getId()));
        }

        return $this->render('crmcuenta/edit.html.twig', array(
            'crmCuentum' => $crmCuentum,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CrmCuenta entity.
     *
     * @Route("/{id}", name="admin_accounts_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CrmCuenta $crmCuentum)
    {
        $form = $this->createDeleteForm($crmCuentum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($crmCuentum);
            $em->flush();
        }

        return $this->redirectToRoute('admin_accounts_index');
    }

    /**
     * Creates a form to delete a CrmCuenta entity.
     *
     * @param CrmCuenta $crmCuentum The CrmCuenta entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CrmCuenta $crmCuentum)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_accounts_delete', array('id' => $crmCuentum->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }








    /////Proveedores

    /**
     * List level of provders
     *
     * @Route("/providers/data/list", name="admin_providers_data")
     */
    public function dataprovidersAction(Request $request)
    {
            try {
                $start = $request->query->get('start');
                $draw = $request->query->get('draw');
                $longitud = $request->query->get('length');
                $busqueda = $request->query->get('search');
                
                $em = $this->getDoctrine()->getEntityManager();
                
                $sql = "SELECT obj.id as id FROM ERPCRMBundle:CrmCuenta obj "
                            ."JOIN obj.tipoCuenta tc"
                            . " WHERE tc.id=1";
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
                        $orderByText = "email";
                        break;
                    case 3:
                        $orderByText = "phone";
                        break;
                    case 4:
                        $orderByText = "industry";
                        break;
                }
                $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
                if($busqueda['value']!=''){
                            $sql = "SELECT CONCAT('<div id=\"',c.id,'-',per.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',per.nombre,' ',per.apellido,'</div>') as name, c.id, (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.cuenta=c.id LIMIT 0,1 ) as phone,(SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.cuenta=c.id LIMIT 1) as email, ind.nombre as industry
                                        FROM crm_contacto_cuenta cc
                                        INNER JOIN crm_cuenta c on(cc.cuenta=c.id)
                                        INNER JOIN ctl_industria ind on(c.industria=ind.id)
                                        INNER JOIN crm_contacto con on(cc.contacto=con.id)
                                        INNER JOIN ctl_persona per on(con.persona=per.id)
                                        WHERE per.id<>1 AND c.estado=1 AND CONCAT(per.nombre,' ',per.apellido,' ',tel.num_telefonico,' ',corr.email) LIKE upper('%".$busqueda['value']."%')  
                                        GROUP BY 1
                                        ORDER BY ". $orderByText." ".$orderDir;
                            $stmt = $em->getConnection()->prepare($sql);
                            $stmt->execute();
                            $row['data'] = $stmt->fetchAll();
                            $row['recordsFiltered']= count($row['data']);
                            $sql = "SELECT CONCAT('<div id=\"',c.id,'-',per.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',per.nombre,' ',per.apellido,'</div>') as name, c.id, (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.cuenta=c.id LIMIT 0,1 ) as phone,(SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.cuenta=c.id LIMIT 1) as email, ind.nombre as industry
                                        FROM crm_contacto_cuenta cc
                                        INNER JOIN crm_cuenta c on(cc.cuenta=c.id)
                                        INNER JOIN ctl_industria ind on(c.industria=ind.id)
                                        INNER JOIN crm_contacto con on(cc.contacto=con.id)
                                        INNER JOIN ctl_persona per on(con.persona=per.id)
                                        WHERE per.id<>1 AND c.estado=1 AND CONCAT(per.nombre,' ',per.apellido,' ', tel.num_telefonico ,' ',corr.email) LIKE upper('%".$busqueda['value']."%')
                                        GROUP BY 1
                                        ORDER BY ". $orderByText." ".$orderDir." LIMIT " . $start . "," . $longitud;
                            $stmt = $em->getConnection()->prepare($sql);
                            $stmt->execute();
                            $row['data'] = $stmt->fetchAll();
                }
                else{
                    $sql = "SELECT CONCAT('<div id=\"',c.id,'-',per.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',per.nombre,' ',per.apellido,'</div>') as name, c.id, (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.cuenta=c.id LIMIT 0,1 ) as phone,(SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.cuenta=c.id LIMIT 1) as email, ind.nombre as industry
                                        FROM crm_contacto_cuenta cc
                                        INNER JOIN crm_cuenta c on(cc.cuenta=c.id)
                                        INNER JOIN ctl_industria ind on(c.industria=ind.id)
                                        INNER JOIN crm_contacto con on(cc.contacto=con.id)
                                        INNER JOIN ctl_persona per on(con.persona=per.id)
                                        WHERE per.id<>1 AND c.estado=1
                                        GROUP BY 1
                                        ORDER BY ". $orderByText." ".$orderDir;
                            $stmt = $em->getConnection()->prepare($sql);
                            $stmt->execute();
                            $row['data'] = $stmt->fetchAll();
                }
                return new Response(json_encode($row));
            } catch (\Exception $e) {  
                var_dump($e);
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


    //////Fin de proveedores



    /**
     * Save provider
     *
     * @Route("/providers/save", name="admin_provider_save_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function saveajaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $em = $this->getDoctrine()->getEntityManager();
                $em->getConnection()->beginTransaction();
                $response = new JsonResponse();
                $imgData = $_FILES;
                //Captura de parametros

                //cuenta
                $tipoCuenta = 1; //Proveedor
                $industriaId = $_POST['industria'];//industria
                $idCuenta = $_POST['id1'];//id crmCuenta
                $idPersona = $_POST['id2'];//id ctlPersona
                $clientePotencial = null;//cliente potencial, nulo
                $nivelSatisfaccion = null;//nivel de satisfaccion, nulo
                $tipoEntidadId = $_POST['persona'];//tipo entidad, Id
                $nombreCuenta = $_POST['compania'];//cliente potencial
                $descripcionCuenta = '';//descripcion
                $fechaRegistro = new \DateTime('now');//descripcion
                $sitioWeb = $_POST['website'];//descripcion
                $estado = 1;//Estado
                // var_dump($idCuenta);
                // var_dump($idPersona);
                //die();
                //persona
                $sucursal = null;//sucursal, nulo
                $tratamientoProtocolarioId = $_POST['titulo'];//tratamiento protocolario
                $nombrePersona = $_POST['nombre'];//nombre persona
                $apellidoPersona = $_POST['apellido'];//apellido persona
                $genero = null;//genero persona, null

                //Correos
                $emailArray = $_POST['email'];//apellido persona

                //Telefonos
                $phoneTypeArray = $_POST['phoneType'];//apellido persona
                $phoneArray = $_POST['phone'];//apellido persona
                $phoneExtArray = $_POST['phoneExt'];//apellido persona

                //Dirección
                $addressArray = $_POST['address'];//apellido persona
                $addressCityArray = $_POST['addressCity'];//apellido persona
                $addressDepartamentoArray = $_POST['addressDepartamento'];//apellido persona

                //Busqueda objetos a partir de ids
                $industriaObj = $em->getRepository('ERPCRMBundle:CtlIndustria')->find($industriaId);
                $tipoEntidadObj = $em->getRepository('ERPCRMBundle:CtlTipoEntidad')->find($tipoEntidadId);
                $tratamientoProtocolarioObj = $em->getRepository('ERPCRMBundle:CtlTratamientoProtocolario')->find($tratamientoProtocolarioId);
                $crmTipoCuentaObj = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->find(1); //Proveedor

                if($idCuenta=='' && $idPersona==''){

                    //Tabla crmCuenta, ids
                    $crmCuentaObj = new CrmCuenta();
                    
                    $crmCuentaObj->setTipoCuenta($crmTipoCuentaObj);
                    $crmCuentaObj->setIndustria($industriaObj);
                    $crmCuentaObj->setClientePotencial($clientePotencial);
                    $crmCuentaObj->setNivelSatisfaccion($nivelSatisfaccion);
                    $crmCuentaObj->setTipoEntidad($tipoEntidadObj);
                    $crmCuentaObj->setNombre($nombreCuenta);
                    $crmCuentaObj->setDescripcion($descripcionCuenta);
                    $crmCuentaObj->setFechaRegistro($fechaRegistro);
                    $crmCuentaObj->setSitioWeb($sitioWeb);
                    $crmCuentaObj->setEstado(1);
                                    
                    //Persist crmCuentaObj
                    $em->persist($crmCuentaObj);
                    $em->flush();


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
                        
                        $path = $this->getParameter('photo.proveedor');
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
                        
                        $path = $this->getParameter('photo.proveedor');
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
     * Retrieve providers
     *
     * @Route("/providers/retrieve", name="admin_provider_retrieve_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function retrieveajaxAction(Request $request)
    {
        try {
            $idCuenta=$request->get("param1");
            $idPersona=$request->get("param2");
            $response = new JsonResponse();
            
            $em = $this->getDoctrine()->getManager();
            $crmCuentaObj = $em->getRepository('ERPCRMBundle:CrmCuenta')->find($idCuenta);
            $ctlTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTelefono')->findBy(array('cuenta'=>$crmCuentaObj->getId()));
            $ctlCorreoObj = $em->getRepository('ERPCRMBundle:CtlCorreo')->findBy(array('cuenta'=>$crmCuentaObj->getId()));
            $ctlDireccionObj = $em->getRepository('ERPCRMBundle:CtlDireccion')->findBy(array('cuenta'=>$crmCuentaObj->getId()));
            $crmFotoObj = $em->getRepository('ERPCRMBundle:CrmFoto')->findBy(array('cuenta'=>$crmCuentaObj->getId()));
            $ctlPersonaObj = $em->getRepository('ERPCRMBundle:CtlPersona')->find($idPersona);
            // var_dump($ctlTelefonoObj);
            if(count($crmCuentaObj)!=0){
                
                //$object->setProbabilidad($);
                //$em->merge($object);
                //$em->flush();    
                //var_dump($crmCuentaObj);
                // die();
                $data['nombre']=$ctlPersonaObj->getNombre();
                $data['apellido']=$ctlPersonaObj->getApellido();
                $data['compania']=$crmCuentaObj->getNombre();

                                
                // var_dump(count($ctlDireccionObj));
                // var_dump(count($ctlTelefonoObj));
                // var_dump(count($ctlCorreoObj));
                // var_dump(count($crmFotoObj));
                if(count($ctlDireccionObj)!=0){
                    $dirArray=array();
                    $cityArray=array();
                    $stateArray=array();
                    foreach ($ctlDireccionObj as $key => $value) {
                        array_push($dirArray, $value->getDireccion());
                        array_push($cityArray, $value->getCiudad()->getId());
                        array_push($stateArray, $value->getCiudad()->getEstado()->getId());
                    }
                    // $data['addressArray']=$ctlDireccionObj[0];
                    $data['addressArray']=$dirArray;
                    $data['cityArray']=$cityArray;
                    $data['stateArray']=$stateArray;
                }
                else{
                    $data['addressArray']=[];
                }
                if(count($ctlTelefonoObj)!=0){

                    // $data['phoneArray']=$ctlTelefonoObj[0];
                    $telTipoArray=array();
                    $telArray=array();
                    $telExtArray=array();
                    foreach ($ctlTelefonoObj as $key => $value) {
                        array_push($telTipoArray, $value->getTipoTelefono()->getId());
                        array_push($telArray, $value->getNumTelefonico());
                        array_push($telExtArray, $value->getExtension());
                    }
                    // $data['addressArray']=$ctlDireccionObj[0];
                    $data['typePhoneArray']=$telTipoArray;
                    $data['phoneArray']=$telArray;
                    $data['extPhoneArray']=$telExtArray;
                }
                else{
                    $data['phoneArray']='';
                    $data['phoneArray']='';
                    $data['phoneArray']='';
                }
                if(count($ctlCorreoObj)!=0){
                    // $data['emailArray']=$ctlCorreoObj[0];
                    // $data['phoneArray']=$ctlTelefonoObj[0];
                    $dirArray=array();
                    foreach ($ctlCorreoObj as $key => $value) {
                        array_push($dirArray, $value->getEmail());
                    }
                    // $data['addressArray']=$ctlDireccionObj[0];
                    $data['emailArray']=$dirArray;
                }
                else{
                    $data['emailArray']='';
                }
                if(count($crmFotoObj)!=0){
                    // $data['src']=$crmFotoObj[0]->getSrc();
                    $dirArray=array();
                    foreach ($crmFotoObj as $key => $value) {
                        array_push($dirArray, $value->getSrc());
                    }
                    // $data['addressArray']=$ctlDireccionObj[0];
                    $data['src']=$dirArray;

                }
                else{
                    $data['src']='';
                }

                // $data['addressArray']=$ctlDireccionObj[0];
                // $data['phoneArray']=$ctlTelefonoObj[0];
                // $data['emailArray']=$ctlCorreoObj[0];
                // $data['src']=$crmFotoObj[0]->getSrc();
                $data['titulo']=$ctlPersonaObj->getTratamientoProtocolario()->getId();
                $data['pesona']=$ctlPersonaObj->getId();
                $data['entidad']=$crmCuentaObj->getTipoEntidad()->getId();
                $data['industria']=$crmCuentaObj->getIndustria()->getId();
                $data['website']=$crmCuentaObj->getSitioWeb();
                
                $data['id1']=$crmCuentaObj->getId();
                $data['id2']=$crmCuentaObj->getId();
            }
            else{
                $data['error']="Error";
            }
                        
            $response->setData($data); 
            
        } catch (\Exception $e) {
            // var_dump($e);
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
     * Delete prividers
     *
     * @Route("/providers/delete", name="admin_providers_account_delete_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function deleteajaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $ids=$request->get("param1");
                $response = new JsonResponse();
                 // var_dump($ids);
                // die();
                $em = $this->getDoctrine()->getManager();
                foreach ($ids as $key => $id) {
                    $object = $em->getRepository('ERPCRMBundle:CrmCuenta')->find($id);    
                    if(count($object)){
                        $object->setEstado(0);
                        $em->merge($object);
                        $em->flush();    
                        $serverDelete = $this->getParameter('app.serverMsgDelete');
                        $data['msg']=$serverDelete;
                    }
                    else{
                        echo "sdcasdc";
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
     * search cities
     *
     * @Route("/providers/state/search/cities", name="admin_provider_search_cities_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function searchajaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $id=$request->get("param1");
                $response = new JsonResponse();
                 // var_dump($ids);
                // die();
                $em = $this->getDoctrine()->getManager();
                $object = $em->getRepository('ERPCRMBundle:CtlCiudad')->findBy(array('estado'=>$id));

                if(count($object)!=0){
                    $array=array();
                    
                    foreach ($object as $key => $value) {
                        $arrayAux=array();    
                        array_push($arrayAux, $value->getId());
                        array_push($arrayAux, $value->getNombre());
                        array_push($array, $arrayAux);
                    }
                    // $data['addressArray']=$object[0];
                    $data['ciudades']=$array;
                }
                else{
                    $data['addressArray']=[];
                }
                // var_dump($data);
                // die();
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