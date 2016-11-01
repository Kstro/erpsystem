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
use ERP\CRMBundle\Entity\CrmClientePotencial;
use ERP\CRMBundle\Form\CrmCuentaType;

/**
 * CrmCuenta controller.
 *
 * @Route("/admin/potentials-clients")
 */
class CrmClientesPotencialesController extends Controller
{
    /**
     * Lists all CrmCuenta entities.
     *
     * @Route("/", name="admin_clientes_potencial_accounts_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        try{
            $timeZone = $this->get('time_zone')->getTimeZone();
            date_default_timezone_set($timeZone->getNombre());
            //var_dump(date_default_timezone_get());
            //die();
            $em = $this->getDoctrine()->getManager();
            
            // $crmCuentas = $em->getRepository('ERPCRMBundle:CrmCuenta')->findAll();
            $response = new JsonResponse();
            //Titulo protocolario
            $items = $em->getRepository('ERPCRMBundle:CtlTratamientoProtocolario')->findBy(array('estado'=>1));
            //Estado
            //$estados = $em->getRepository('ERPCRMBundle:CtlEstado')->findAll();
            //Ciudad
            //$ciudades = $em->getRepository('ERPCRMBundle:CtlCiudad')->findAll();
            //Tipo persona
            //$personas = $em->getRepository('ERPCRMBundle:CtlTipoEntidad')->findBy(array('estado'=>1));
            $personas = $em->getRepository('ERPCRMBundle:CtlTipoEntidad')->findBy(array('estado'=>1));
            //Tipo interes
            $interes = $em->getRepository('ERPCRMBundle:CtlNivelInteres')->findBy(array('estado'=>1));
            //Estado cliente potencial
            $estadosCli = $em->getRepository('ERPCRMBundle:CrmEstadoClientePotencial')->findBy(array('estado'=>1));
            //fuentes
            $fuentes = $em->getRepository('ERPCRMBundle:CtlFuente')->findBy(array('estado'=>1));
            //campanias
            $campanias = $em->getRepository('ERPCRMBundle:CrmCampania')->findBy(array('estado'=>1));
            //Tipos telefono
            $tiposTelefono = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->findAll();
            //Tipos de cuenta
            $tiposCuenta = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->findBy(array('estado'=>1));
            //Tipos de etiqueta
            $etiquetas = $em->getRepository('ERPCRMBundle:CrmEtiqueta')->findAll();
            return $this->render('crmcuenta/index_cliente_potencial.html.twig', array(
                // 'crmCuentas' => $crmCuentas,
                'items'=>$items,
                'estadosCli'=>$estadosCli,
                //'estados'=>$estados,
                'campanias'=>$campanias,
                //'ciudades'=>$ciudades,
                'personas'=>$personas,
                'interes'=>$interes,
                'fuentes'=>$fuentes,
                'tiposTelefono'=>$tiposTelefono,
                'tiposCuenta'=>$tiposCuenta,
                'etiquetas'=>$etiquetas,
                'menuClientePotencialA' => 2,
            ));
        
        } catch (\Exception $e) {  
            echo $e->getMessage();
            echo $e->getLine();
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








    /////Clientes potenciales

    /**
     * List level of provders
     *
     * @Route("/potential/clients/data/list", name="admin_clients_potencial_data")
     */
    public function dataclientsAction(Request $request)
    {
            try {
                $start = $request->query->get('start');
                $draw = $request->query->get('draw');
                $longitud = $request->query->get('length');
                $busqueda = $request->query->get('search');
                
                $em = $this->getDoctrine()->getEntityManager();
                
                $sql = "SELECT obj.id as id FROM ERPCRMBundle:CrmCuenta obj "
                            ."JOIN obj.tipoCuenta tc"
                            . " WHERE tc.id=2";
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
                        $orderByText = "origin";
                        break;
                    case 5:
                        $orderByText = "interes";
                        break;
                }
                $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
                if($busqueda['value']!=''){
                            $sql = "SELECT CONCAT('<div id=\"',c.id,'-',per.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',c.nombre,'</div>') as name, c.id, (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.cuenta=c.id LIMIT 0,1 ) as phone,(SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.cuenta=c.id LIMIT 1) as email, CONCAT('<div style=\"text-align:left\">',c.nombre,'</div>') as account,  CONCAT('<div style=\"text-align:left\">', fue.nombre,'</div>') as origin,  CONCAT('<div style=\"text-align:left\">', inte.nombre,'</div>') as interes 
                                        FROM crm_contacto_cuenta cc
                                        INNER JOIN crm_cuenta c on(cc.cuenta=c.id)
                                        INNER JOIN crm_contacto con on(cc.contacto=con.id)
                                        INNER JOIN ctl_persona per on(con.persona=per.id)
                                        INNER JOIN crm_cliente_potencial cli on(cli.persona=per.id)
                                        INNER JOIN ctl_nivel_interes inte on(cli.nivel_interes=inte.id)
                                        INNER JOIN ctl_fuente fue on(cli.fuente_principal=fue.id)
                                        WHERE c.tipo_cuenta=2 AND per.id<>1 AND c.estado=1 
                                        GROUP BY 1
                                        HAVING name LIKE upper('%".$busqueda['value']."%') OR email LIKE upper('%".$busqueda['value']."%') OR origin LIKE upper('%".$busqueda['value']."%') OR phone LIKE upper('%".$busqueda['value']."%') OR interes LIKE upper('%".$busqueda['value']."%') OR name LIKE upper('%".$busqueda['value']."%') 
                                        ORDER BY ". $orderByText." ".$orderDir;
                            $stmt = $em->getConnection()->prepare($sql);
                            $stmt->execute();
                            $row['data'] = $stmt->fetchAll();
                            $row['recordsFiltered']= count($row['data']);
                            $sql = "SELECT CONCAT('<div id=\"',c.id,'-',per.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',c.nombre,'</div>') as name, c.id, (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.cuenta=c.id LIMIT 0,1 ) as phone,(SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.cuenta=c.id LIMIT 1) as email, CONCAT('<div style=\"text-align:left\">',c.nombre,'</div>') as account,  CONCAT('<div style=\"text-align:left\">', fue.nombre,'</div>') as origin,  CONCAT('<div style=\"text-align:left\">', inte.nombre,'</div>') as interes 
                                        FROM crm_contacto_cuenta cc
                                        INNER JOIN crm_cuenta c on(cc.cuenta=c.id)
                                        INNER JOIN crm_contacto con on(cc.contacto=con.id)
                                        INNER JOIN ctl_persona per on(con.persona=per.id)
                                        INNER JOIN crm_cliente_potencial cli on(cli.persona=per.id)
                                        INNER JOIN ctl_nivel_interes inte on(cli.nivel_interes=inte.id)
                                        INNER JOIN ctl_fuente fue on(cli.fuente_principal=fue.id)
                                        WHERE c.tipo_cuenta=2 AND per.id<>1 AND c.estado=1 
                                        GROUP BY 1
                                        HAVING name LIKE upper('%".$busqueda['value']."%') OR email LIKE upper('%".$busqueda['value']."%') OR origin LIKE upper('%".$busqueda['value']."%') OR phone LIKE upper('%".$busqueda['value']."%') OR interes LIKE upper('%".$busqueda['value']."%') OR name LIKE upper('%".$busqueda['value']."%') 
                                        ORDER BY ". $orderByText." ".$orderDir." LIMIT " . $start . "," . $longitud;
                            $stmt = $em->getConnection()->prepare($sql);
                            $stmt->execute();
                            $row['data'] = $stmt->fetchAll();
                }
                else{
                    /*$sql = "SELECT CONCAT('<div id=\"',c.id,'-',per.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',c.nombre,'</div>') as name, c.id, (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.cuenta=c.id LIMIT 0,1 ) as phone,(SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.cuenta=c.id LIMIT 1) as email, CONCAT('<div style=\"text-align:left\">',c.nombre,'</div>') as account,  CONCAT('<div style=\"text-align:left\">', fue.nombre,'</div>') as origin,  CONCAT('<div style=\"text-align:left\">', inte.nombre,'</div>') as interes 
                                        FROM crm_contacto_cuenta cc
                                        INNER JOIN crm_cuenta c on(cc.cuenta=c.id)
                                        INNER JOIN crm_contacto con on(cc.contacto=con.id)
                                        INNER JOIN ctl_persona per on(con.persona=per.id)
                                        LEFT JOIN crm_cliente_potencial cli on(cli.persona=per.id)
                                        LEFT JOIN ctl_nivel_interes inte on(cli.nivel_interes=inte.id)
                                        INNER JOIN ctl_fuente fue on(cli.fuente_principal=fue.id)
                                        WHERE per.id<>1 AND c.estado=1 AND c.tipo_cuenta=2
                                        GROUP BY 1
                                        ORDER BY ". $orderByText." ".$orderDir;*/
                    $sql = "SELECT CONCAT('<div id=\"',c.id,'-',per.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',c.nombre,'</div>') as name, c.id, (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.cuenta=c.id LIMIT 0,1 ) as phone,(SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.cuenta=c.id LIMIT 1) as email, CONCAT('<div style=\"text-align:left\">',c.nombre,'</div>') as account,  CONCAT('<div style=\"text-align:left\">', fue.nombre,'</div>') as origin,  CONCAT('<div style=\"text-align:left\">', inte.nombre,'</div>') as interes 
                                        FROM crm_contacto_cuenta cc
                                        INNER JOIN crm_cuenta c on(cc.cuenta=c.id)
                                        INNER JOIN crm_contacto con on(cc.contacto=con.id)
                                        INNER JOIN ctl_persona per on(con.persona=per.id)
                                        INNER JOIN crm_cliente_potencial cli on(cli.persona=per.id)
                                        INNER JOIN ctl_nivel_interes inte on(cli.nivel_interes=inte.id)
                                        LEFT JOIN ctl_fuente fue on(cli.fuente_principal=fue.id)
                                        WHERE per.id<>1 AND c.estado=1 AND c.tipo_cuenta=2
                                        GROUP BY 1
                                        ORDER BY ". $orderByText." ".$orderDir;
                            $stmt = $em->getConnection()->prepare($sql);
                            $stmt->execute();
                            $row['data'] = $stmt->fetchAll();
                }
                return new Response(json_encode($row));
            } catch (\Exception $e) {  
                //var_dump($e);
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


    //////Fin de clientes



    /**
     * Save clientes
     *
     * @Route("/potential/clients/save", name="admin_cliente_potencial_save_ajax",  options={"expose"=true}))
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
                $tipoCuenta = 2; //Proveedor
                
                $idCuenta = $_POST['id1'];//id crmCuenta
                $idPersona = $_POST['id2'];//id ctlPersona
                $clientePotencial = null;//cliente potencial, nulo
                $nivelSatisfaccion = null;//nivel de satisfaccion, nulo
                // $tipoEntidadId = $_POST['persona'];//tipo entidad, Id
                $nombreCuenta = $_POST['compania'];//cliente potencial
                $descripcionCuenta = '';//descripcion
                $fechaRegistro = new \DateTime('now');//descripcion
                // $sitioWeb = $_POST['website'];//descripcion
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
                $addressArray = $_POST['address'];//direccion persona
                $addressCityArray = $_POST['addressCity'];//city
                $addressDepartamentoArray = $_POST['addressDepartamento'];//state
                $zipCodeArray = $_POST['zipcode'];//zipcode

                if (isset($_POST['interes'])) {
                    $interesId = $_POST['interes'];//interes
                    $interesObj = $em->getRepository('ERPCRMBundle:CtlNivelInteres')->find($interesId);
                } 
                else{
                    $interesObj = null;
                }
                if (isset($_POST['estadoCli'])) {
                    $estadoClientePotencial = $_POST['estadoCli'];
                    $estadoCliObj = $em->getRepository('ERPCRMBundle:CrmEstadoClientePotencial')->find($estadoClientePotencial);
                    // echo "sadcsdcsd". $estadoClientePotencial;
                } 
                else{
                    $estadoCliObj = null;
                }
                if (isset($_POST['fuente'])) {
                    $fuenteClientePotencial = $_POST['fuente'];    
                    $fuenteObj = $em->getRepository('ERPCRMBundle:CtlFuente')->find($fuenteClientePotencial);//echo "edcc".$_POST['fuente'];
                } 
                else{
                    $fuenteObj = null;
                }
                if (isset($_POST['campania'])) {
                    $campaniaClientePotencial = $_POST['campania'];   
                    $campaniaObj = $em->getRepository('ERPCRMBundle:CrmCampania')->find($campaniaClientePotencial); 
                } 
                else{
                    $campaniaObj = null;
                }
                
                //Busqueda objetos a partir de ids
                
                // $tipoEntidadObj = $em->getRepository('ERPCRMBundle:CtlTipoEntidad')->find($tipoEntidadId); NULL
                $tratamientoProtocolarioObj = $em->getRepository('ERPCRMBundle:CtlTratamientoProtocolario')->find($tratamientoProtocolarioId);
                $crmTipoCuentaObj = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->find(2); //Cliente potencial
                // var_dump($_POST);
                // die();
                $contactos = $_POST['contactos'];
                if($idCuenta=='' && $idPersona==''){

                    //Tabla crmCuenta, ids
                    $crmCuentaObj = new CrmCuenta();
                    
                    $crmCuentaObj->setTipoCuenta($crmTipoCuentaObj);
                    $crmCuentaObj->setIndustria(null);
                    $crmCuentaObj->setClientePotencial($clientePotencial);
                    $crmCuentaObj->setNivelSatisfaccion($nivelSatisfaccion);
                    $crmCuentaObj->setTipoEntidad(null);
                    $crmCuentaObj->setNombre($nombreCuenta);
                    $crmCuentaObj->setDescripcion($descripcionCuenta);
                    $crmCuentaObj->setFechaRegistro($fechaRegistro);
                    $crmCuentaObj->setSitioWeb(null);
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

                    //Tabla crmClientePotencial
                    $crmClientePotencialObj = new CrmClientePotencial();
                    $crmClientePotencialObj->setPersona($ctlPersonaObj);
                    $crmClientePotencialObj->setNivelInteres($interesObj);
                    $crmClientePotencialObj->setEstadoClientePotencial($estadoCliObj);
                    $crmClientePotencialObj->setFuentePrincipal($fuenteObj);
                    $crmClientePotencialObj->setCampania($campaniaObj);
                    
                    //Persist crmClientePotencialObj
                    $em->persist($crmClientePotencialObj);
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
                        $ctlTipoTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->find($phoneTypeArray[$key]);
                        $ctlTelefonoObj->setTipoTelefono($ctlTipoTelefonoObj);    
                        /*if ($key<$phoneLenght && $key!=0) {
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
                        }*/
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
                    foreach ($addressArray as $key => $val) {
                                          
                        $ctlDireccionObj = new CtlDireccion();
                        $ctlDireccionObj->setCuenta($crmCuentaObj);
                        $ctlDireccionObj->setDireccion($addressArray[$key]);
                        $ctlDireccionObj->setZipCode($zipCodeArray[$key]);
                        $ctlDireccionObj->setCity($addressCityArray[$key]);
                        $ctlDireccionObj->setState($addressDepartamentoArray[$key]);
//                        if ($key<$addressLenght && $key!=0) {
//                            if ($addressCityArray[$key]==$addressCityArray[$key-1]) {
//                                //No buscar en la base ciudad
//                                $ctlDireccionObj->setCiudad($ctlCiudadObj);
//                            } else {
//                                //Buscar en la base la ciudad
//                                $ctlCiudadObj = $em->getRepository('ERPCRMBundle:CtlCiudad')->find($addressCityArray[$key]);
//                                $ctlDireccionObj->setCiudad($ctlCiudadObj);
//                            }
//                         
//                        } else {
//                                //Buscar en la base la ciudad, primera iteracion debe buscar ciudad
//                                $ctlCiudadObj = $em->getRepository('ERPCRMBundle:CtlCiudad')->find($addressCityArray[$key]);
//                                $ctlDireccionObj->setCiudad($ctlCiudadObj);
//                        }
                        
                        $ctlDireccionObj->setPersona(null);
                        $ctlDireccionObj->setEmpresa(null);
                        $ctlDireccionObj->setCiudad(null);
                        
                        $ctlDireccionObj->setLatitud(0);
                        $ctlDireccionObj->setLongitud(0);
                        $ctlDireccionObj->setEstado(1);
                        //Persist ctlDireccion
                        $em->persist($ctlDireccionObj);
                        $em->flush();
                        
                    }
                    
                    
                    //////Contactos
                    $contactsLenght=count($contactos)-1;//Cantidad de telefono ingresados, menos 1 para index de array
                    $crmContacto = $em->getRepository('ERPCRMBundle:CrmContacto')->find($contactos[0]);//Para definir la variable
                    foreach ($contactos as $key => $contact) {
                                          
                        $crmContactoCuenta = new CrmContactoCuenta();
                        $crmContactoCuenta->setCuenta($crmCuentaObj);
                        //var_dump($key);
//                        if ($key<$contactsLenght && $key!=0) {
//                            if ($contact[$key]==$contact[$key-1]) {
//                                //No buscar en la base contacto
//                                $crmContactoCuenta->setContacto($crmContacto);
//                            } else {
                                //Buscar en la base el tipo de telefono
                                $crmContacto = $em->getRepository('ERPCRMBundle:CrmContacto')->find($contactos[$key]);//Para definir la variable
                                $ctlTelefonoObj->setContacto($crmContacto);
                                //var_dump('buscar base tipo telefono');
//                            }
//                        } else {
                                //Buscar en la base el tipo de telefono, primera iteracion debe buscar el tipo de telefono
                                //$ctlTipoTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->find($phoneTypeArray[$key]);
                                //$crmContactoCuenta->setContacto($crmContacto);
                                //var_dump('no buscar base tipo telefono');
//                        }
                        $em->persist($crmContactoCuenta);
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
                        $crmCuentaObj->setIndustria(null);
                        $crmCuentaObj->setClientePotencial($clientePotencial);
                        $crmCuentaObj->setNivelSatisfaccion($nivelSatisfaccion);
                        $crmCuentaObj->setTipoEntidad(null);
                        $crmCuentaObj->setNombre($nombreCuenta);
                        $crmCuentaObj->setDescripcion($descripcionCuenta);
                        // $crmCuentaObj->setFechaRegistro($fechaRegistro);
                        $crmCuentaObj->setSitioWeb(null);
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
                        
                        //Eliminar contactos
                        $crmContactoCuentas = $em->getRepository('ERPCRMBundle:CrmContactoCuenta')->findBy(array('cuenta'=>$idCuenta));
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


                        //Tabla crmClientePotencial
                        $crmClientePotencialObj = $em->getRepository('ERPCRMBundle:CrmClientePotencial')->findBy(array('persona'=>$idPersona));
                        $crmClientePotencialObj[0]->setPersona($ctlPersonaObj);
                        $crmClientePotencialObj[0]->setNivelInteres($interesObj);
                        // $crmClientePotencialObj[0]->setEstadoClientePotencial($estadoCliObj);
                        $crmClientePotencialObj[0]->setEstadoClientePotencial($estadoCliObj);
                        $crmClientePotencialObj[0]->setFuentePrincipal($fuenteObj);
                        $crmClientePotencialObj[0]->setCampania($campaniaObj);

                        //Persist crmClientePotencialObj
                        $em->merge($crmClientePotencialObj[0]);
                        $em->flush();                    
                        // var_dump($crmClientePotencialObj);
                        // die();
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

                    foreach ($addressArray as $key => $val) {
                                          
                        $ctlDireccionObj = new CtlDireccion();
                        $ctlDireccionObj->setCuenta($crmCuentaObj);
                        $ctlDireccionObj->setDireccion($addressArray[$key]);
                        $ctlDireccionObj->setZipCode($zipCodeArray[$key]);
                        $ctlDireccionObj->setCity($addressCityArray[$key]);
                        $ctlDireccionObj->setState($addressDepartamentoArray[$key]);
//                        if ($key<$addressLenght && $key!=0) {
//                            if ($addressCityArray[$key]==$addressCityArray[$key-1]) {
//                                //No buscar en la base ciudad
//                                $ctlDireccionObj->setCiudad($ctlCiudadObj);
//                            } else {
//                                //Buscar en la base la ciudad
//                                $ctlCiudadObj = $em->getRepository('ERPCRMBundle:CtlCiudad')->find($addressCityArray[$key]);
//                                $ctlDireccionObj->setCiudad($ctlCiudadObj);
//                            }
//                         
//                        } else {
//                                //Buscar en la base la ciudad, primera iteracion debe buscar ciudad
//                                $ctlCiudadObj = $em->getRepository('ERPCRMBundle:CtlCiudad')->find($addressCityArray[$key]);
//                                $ctlDireccionObj->setCiudad($ctlCiudadObj);
//                        }
                        
                        $ctlDireccionObj->setPersona(null);
                        $ctlDireccionObj->setEmpresa(null);
                        $ctlDireccionObj->setCiudad(null);
                        
                        $ctlDireccionObj->setLatitud(0);
                        $ctlDireccionObj->setLongitud(0);
                        $ctlDireccionObj->setEstado(1);
                        //Persist ctlDireccion
                        $em->persist($ctlDireccionObj);
                        $em->flush();
                        
                    }      
                    
                    
                    //////Contactos
//                    $contactsLenght=count($contactos)-1;//Cantidad de contactos ingresados, menos 1 para index de array
//                    
                    foreach ($contactos as $key => $contact) {
                                          
                        $crmContactoCuenta = new CrmContactoCuenta();
                        $crmContactoCuenta->setCuenta($crmCuentaObj);
                        //var_dump($key);
//                        if ($key<$contactsLenght && $key!=0) {
//                            if ($contact[$key]==$contact[$key-1]) {
//                                //No buscar en la base contacto
//                                $crmContactoCuenta->setContacto($crmContacto);
//                            } else {
//                                //Buscar en la base el tipo de telefono
//                                $crmContacto = $em->getRepository('ERPCRMBundle:CrmContacto')->find($contactos[$key]);//Para definir la variable
//                                $ctlTelefonoObj->setContacto($crmContacto);
//                                //var_dump('buscar base tipo telefono');
//                            }
//                        } else {
                                //Buscar en la base el tipo de telefono, primera iteracion debe buscar el tipo de telefono
                                //$ctlTipoTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->find($phoneTypeArray[$key]);
//                        $sql = "SELECT c
//                                        FROM ERPCRMBundle:CrmContacto c
//                                        JOIN c.persona per
//                                        WHERE per.id = :idPersona";
//                        $crmContacto = $em->createQuery($sql)
//                                    ->setParameters(array('idPersona'=>$contact))
//                                    ->getResult();
                            
                            $crmContacto = $em->getRepository('ERPCRMBundle:CrmContacto')->find($contact);//Para definir la variable
                        if(count($crmContacto->getId()!=0)){
                            $crmContactoCuenta->setContacto($crmContacto);
                        }
                        else{
                            $crmContactoCuenta->setContacto(null);
                        }
                                //var_dump('no buscar base tipo telefono');
                        //}
//                            var_dump($crmContacto);
//                            var_dump($crmContactoCuentas);
//                            die();
                        $em->persist($crmContactoCuenta);
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
                echo $e->getMessage();
                echo $e->getLine();
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
     * Retrieve clients
     *
     * @Route("/potential/clients/retrieve", name="admin_clients_potencial_retrieve_ajax",  options={"expose"=true}))
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
            $crmClientePotencialObj = $em->getRepository('ERPCRMBundle:CrmClientePotencial')->findBy(array('persona'=>$idPersona));
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
                    $zipCodeArray=array();
                    foreach ($ctlDireccionObj as $key => $value) {
                        if($value->getDireccion()==null){
                            array_push($dirArray, '');
                        }
                        else{
                            array_push($dirArray, $value->getDireccion());
                        }
                        if($value->getCity()==null){
                            array_push($cityArray, '');
                        }
                        else{
                            array_push($cityArray, $value->getCity());
                        }
                        if($value->getState()==null){
                            array_push($stateArray, '');
                        }
                        else{
                            array_push($stateArray, $value->getState());
                        }
                        if($value->getZipCode()==null){
                            array_push($zipCodeArray, '');
                        }
                        else{
                            array_push($zipCodeArray, $value->getZipCode());
                        }
                    }
                    $data['addressArray']=$dirArray;
                    $data['cityArray']=$cityArray;
                    $data['stateArray']=$stateArray;
                    $data['zipCodeArray']=$zipCodeArray;
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
                    $data['typePhoneArray']='';
                    $data['phoneArray']='';
                    $data['extPhoneArray']='';
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
                $data['interes']=$crmClientePotencialObj[0]->getNivelInteres()->getId();
                if($crmClientePotencialObj[0]->getEstadoClientePotencial()!=null){
                    $data['estado']=$crmClientePotencialObj[0]->getEstadoClientePotencial()->getId();
                }
                else{
                    $data['estado']=0;
                }
                if($crmClientePotencialObj[0]->getFuentePrincipal()!=null){
                    $data['fuente']=$crmClientePotencialObj[0]->getFuentePrincipal()->getId();
                }
                else{
                    $data['fuente']=0;
                }
                
                
                // if ($crmClientePotencialObj[0]->getCampania()!=null) {
                //     $data['campania']=$crmClientePotencialObj[0]->getCampania()->getId();
                // } else {
                //     $data['campania']='';
                // }
                
                
                
                $data['id1']=$crmCuentaObj->getId();
                $data['id2']=$ctlPersonaObj->getId();
                
                $sql = "SELECT ec.id as id, e.nombre as nombre FROM ERPCRMBundle:CrmEtiquetaCuenta ec"
                            ." JOIN ec.etiqueta e "
                            ." JOIN ec.cuenta c "
                            ." WHERE c.id=:idCuenta";
                $tags = $em->createQuery($sql)
                                    ->setParameters(array('idCuenta'=>$idCuenta))
                                    ->getResult();
                
                $data['tags']=$tags;
                
                
                
                
                $crmContactoCuentaObj = $em->getRepository('ERPCRMBundle:CrmContactoCuenta')->findBy(array('cuenta'=>$idCuenta));
                
                $data['idContactos']=array();
                $data['nombreContactos']=array();
                $data['telefonoContactos']=array();
                $data['correoContactos']=array();
                foreach ($crmContactoCuentaObj as $key=>$contactoCuenta){
                    if($key!=0){
                        $dataTmp['correo']='';
                        $dataTmp['telefono']='';
                        $crmContactoObj = $contactoCuenta->getContacto();
                        $row=array();
                        
                        if($crmContactoObj!=null){
                            if($crmContactoObj->getPersona()!=null){
                                $idPersona= $crmContactoObj->getPersona()->getId();
                                $personaContacto= $crmContactoObj->getPersona()->getNombre().' '.$crmContactoObj->getPersona()->getApellido();
                                $ctlCorreo = $em->getRepository('ERPCRMBundle:CtlCorreo')->findBy(array('persona'=>$idPersona));
                                $ctlTelefono= $em->getRepository('ERPCRMBundle:CtlTelefono')->findBy(array('persona'=>$idPersona));
                                foreach ($ctlCorreo as $key=>$correo){
                                    $dataTmp['correo'].=', '.$correo->getCorreo();
                                }
                                foreach ($ctlTelefono as $key=>$telefono){
                                    $dataTmp['telefono'].=', '.$telefono->getTelefono();
                                }
                            }
                            $dataTmp['i'] = 0;
                            $idCont = $contactoCuenta->getContacto()->getId();
                        }
                        else{
                            $idCont = 0;
                            $dataTmp['i'] = 1;
                        }

                        if($dataTmp['correo']=='')
                            $dataTmp['correo']='-';
                        if($dataTmp['telefono']=='')
                            $dataTmp['telefono']='-';

                        array_push($data['idContactos'],$idCont);
                        array_push($data['nombreContactos'],$personaContacto);
                        array_push($data['telefonoContactos'],$dataTmp['telefono']);
                        array_push($data['correoContactos'],$dataTmp['correo']);
    //                    array_push($row, $idCont);
    //                    array_push($row, $dataTmp['telefono']);
    //                    array_push($row, $dataTmp['correo']);
    //                    var_dump($row);
    //                    die();
    //                    array_push($data['contactos'],$row);
                    }

                }
                
                
                
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
     * Delete potential clients
     *
     * @Route("/potential/clients/delete", name="admin_clientes_potencial_account_delete_ajax",  options={"expose"=true}))
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
     * Convert potential clients
     *
     * @Route("/potential/clients/convert/client", name="admin_potential_cliente_to_cliente_convert_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function convertclientajaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {
                $ids=$request->get("param1");
                $response = new JsonResponse();
                 // var_dump($ids);
                // die();
                $em = $this->getDoctrine()->getManager();
                $tipoCuentaObj = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->find(3);//////Clientes
                foreach ($ids as $key => $id) {
                    $object = $em->getRepository('ERPCRMBundle:CrmCuenta')->find($id);    
                    if(count($object)){
                        $object->setTipoCuenta($tipoCuentaObj);
                        $em->merge($object);
                        $em->flush();    
                        $serverConvert = $this->getParameter('app.serverMsgConverted');
                        $data['msg']=$serverConvert;
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
