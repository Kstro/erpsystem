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
//use ERP\CRMBundle\Entity\CrmContactoCuenta;
use ERP\CRMBundle\Entity\CtlTratamientoProtocolario;
use ERP\CRMBundle\Entity\CtlTelefono;
use ERP\CRMBundle\Entity\CrmContactoCuenta;
use ERP\CRMBundle\Entity\CtlCorreo;
use ERP\CRMBundle\Entity\CtlDireccion;
use ERP\CRMBundle\Entity\CrmFoto;
//use ERP\CRMBundle\Entity\CrmClientePotencial;

//use ERP\CRMBundle\Entity\CrmContacto;
use ERP\CRMBundle\Form\CrmContactoType;

/**
 * CrmContacto controller.
 *
 * @Route("/admin/contact")
 */
class CrmContactoController extends Controller
{
    /**
     * Lists all CrmContacto entities.
     *
     * @Route("/", name="admin_contact_index")
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
            //Cuenta
            $crmCuentas = $em->getRepository('ERPCRMBundle:CrmCuenta')->findBy(array('estado'=>1));
            //Tipo persona
            //$personas = $em->getRepository('ERPCRMBundle:CtlTipoEntidad')->findBy(array('estado'=>1));
            $personas = $em->getRepository('ERPCRMBundle:CtlTipoEntidad')->findAll();
            //Tipo interes
            //$interes = $em->getRepository('ERPCRMBundle:CtlNivelInteres')->findAll(array('estado'=>1));
            //Estado cliente potencial
            //$estadosCli = $em->getRepository('ERPCRMBundle:CrmEstadoClientePotencial')->findAll(array('estado'=>1));
            //fuentes
            $fuentes = $em->getRepository('ERPCRMBundle:CtlFuente')->findBy(array('estado'=>1));
            //campanias
            $campanias = $em->getRepository('ERPCRMBundle:CrmCampania')->findBy(array('estado'=>1));
            //Tipos telefono
            $tiposTelefono = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->findAll();
            //Tipos de cuenta
            //$tiposCuenta = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->findBy(array('estado'=>1));
            return $this->render('crmcontacto/index_contacto.html.twig', array(
                'crmCuentas' => $crmCuentas,
                'items'=>$items,
                //'estadosCli'=>$estadosCli,
                'estados'=>$estados,
                'campanias'=>$campanias,
                'ciudades'=>$ciudades,
                'personas'=>$personas,
                //'interes'=>$interes,
                'fuentes'=>$fuentes,
                'tiposTelefono'=>$tiposTelefono,
                //'tiposCuenta'=>$tiposCuenta,
                'menuContactossA'=>true,
            ));
        
        } catch (\Exception $e) {  
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
     * Creates a new CrmContacto entity.
     *
     * @Route("/new", name="admin_contact_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $crmContacto = new CrmContacto();
        $form = $this->createForm('ERP\CRMBundle\Form\CrmContactoType', $crmContacto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crmContacto);
            $em->flush();

            return $this->redirectToRoute('admin_contact_show', array('id' => $crmContacto->getId()));
        }

        return $this->render('crmcontacto/new.html.twig', array(
            'crmContacto' => $crmContacto,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CrmContacto entity.
     *
     * @Route("/{id}", name="admin_contact_show")
     * @Method("GET")
     */
    public function showAction(CrmContacto $crmContacto)
    {
        $deleteForm = $this->createDeleteForm($crmContacto);

        return $this->render('crmcontacto/show.html.twig', array(
            'crmContacto' => $crmContacto,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CrmContacto entity.
     *
     * @Route("/{id}/edit", name="admin_contact_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CrmContacto $crmContacto)
    {
        $deleteForm = $this->createDeleteForm($crmContacto);
        $editForm = $this->createForm('ERP\CRMBundle\Form\CrmContactoType', $crmContacto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crmContacto);
            $em->flush();

            return $this->redirectToRoute('admin_contact_edit', array('id' => $crmContacto->getId()));
        }

        return $this->render('crmcontacto/edit.html.twig', array(
            'crmContacto' => $crmContacto,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CrmContacto entity.
     *
     * @Route("/{id}", name="admin_contact_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CrmContacto $crmContacto)
    {
        $form = $this->createDeleteForm($crmContacto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($crmContacto);
            $em->flush();
        }

        return $this->redirectToRoute('admin_contact_index');
    }

    /**
     * Creates a form to delete a CrmContacto entity.
     *
     * @param CrmContacto $crmContacto The CrmContacto entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CrmContacto $crmContacto)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_contact_delete', array('id' => $crmContacto->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /////Contacto

    /**
     * List level of contactos
     *
     * @Route("/contact/data/list", name="admin_contact_data")
     */
    public function datacontactsAction(Request $request) {
        try {
            $start = $request->query->get('start');
            $draw = $request->query->get('draw');
            $longitud = $request->query->get('length');
            $busqueda = $request->query->get('search');
            
            $em = $this->getDoctrine()->getEntityManager();

            $sql = "SELECT per.id as id FROM crm_contacto con 
                            INNER JOIN ctl_persona per on(con.persona=per.id)                                        
                            INNER JOIN ctl_correo corr on(corr.persona=per.id)                                        
                            INNER JOIN ctl_telefono tel on(tel.persona=per.id)                                        
                            WHERE con.estado = 1
                            GROUP BY 1";                            
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $rowsTotal = $stmt->fetchAll();      
//            $rowsTotal = $em->createQuery($sql)
//                    ->getResult();
                                    
            $row['draw'] = $draw++;
            $row['recordsTotal'] = count($rowsTotal);
            $row['recordsFiltered'] = count($rowsTotal);
            $row['data'] = array();
                        
            $arrayFiltro = explode(' ', $busqueda['value']);

            $orderParam = $request->query->get('order');
            $orderBy = $orderParam[0]['column'];
            $orderDir = $orderParam[0]['dir'];
            $orderByText = "";
            switch (intval($orderBy)) {
                case 1:
                    $orderByText = "name";
                    break;
                case 2:
                    $orderByText = "compania";
                    break;
                case 3:
                    $orderByText = "email";
                    break;
                case 4:
                    $orderByText = "phone";
                    break;
//                case 5:
//                    $orderByText = "city";
//                    break;
            }
            $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
            if ($busqueda['value'] != '') {
                $sql = "SELECT CONCAT('<div id=\"', con.id, '\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, 
                                        CONCAT('<div style=\"text-align:left\">',per.nombre,' ',per.apellido,'</div>') as name, 
                                        CONCAT('<div style=\"text-align:left\">',cu.nombre,'</div>') as compania,
                                        (SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.persona=per.id LIMIT 1) as email,
                                        (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.persona=per.id LIMIT 0,1 ) as phone                                        
                                        FROM crm_contacto con 
                                        INNER JOIN ctl_persona per on(con.persona=per.id)                                        
                                        INNER JOIN ctl_correo corr on(corr.persona=per.id)                                        
                                        INNER JOIN ctl_telefono tel on(tel.persona=per.id)
                                        INNER JOIN crm_contacto_cuenta c_c on(con.id=c_c.contacto)
                                        INNER JOIN crm_cuenta cu on(cu.id=c_c.cuenta)
                                        WHERE con.estado = 1
                                        GROUP BY 1
                                        HAVING name LIKE upper('%" . $busqueda['value'] . "%') OR name LIKE upper('%" . $busqueda['value'] . "%') OR email LIKE upper('%" . $busqueda['value'] . "%') OR phone LIKE upper('%" . $busqueda['value'] . "%')  
                                        ORDER BY " . $orderByText . " " . $orderDir;
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();
                $row['data'] = $stmt->fetchAll();
                $row['recordsFiltered'] = count($row['data']);
                $sql = "SELECT CONCAT('<div id=\"', con.id, '\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, 
                                        CONCAT('<div style=\"text-align:left\">',per.nombre,' ',per.apellido,'</div>') as name, 
                                        CONCAT('<div style=\"text-align:left\">',cu.nombre,'</div>') as compania,
                                        (SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.persona=per.id LIMIT 1) as email,
                                        (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.persona=per.id LIMIT 0,1 ) as phone                                        
                                        FROM crm_contacto con 
                                        INNER JOIN ctl_persona per on(con.persona=per.id)                                        
                                        INNER JOIN ctl_correo corr on(corr.persona=per.id)                                        
                                        INNER JOIN ctl_telefono tel on(tel.persona=per.id)
                                        INNER JOIN crm_contacto_cuenta c_c on(con.id=c_c.contacto)
                                        INNER JOIN crm_cuenta cu on(cu.id=c_c.cuenta)
                                        WHERE con.estado = 1
                                        GROUP BY 1
                                        HAVING name LIKE upper('%" . $busqueda['value'] . "%') OR name LIKE upper('%" . $busqueda['value'] . "%') OR email LIKE upper('%" . $busqueda['value'] . "%') OR phone LIKE upper('%" . $busqueda['value'] . "%')
                                        ORDER BY " . $orderByText . " " . $orderDir . " LIMIT " . $start . "," . $longitud;
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();
                $row['data'] = $stmt->fetchAll();
            } else {
                $sql = "SELECT CONCAT('<div id=\"', con.id, '\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, 
                                        CONCAT('<div style=\"text-align:left\">',per.nombre,' ',per.apellido,'</div>') as name, 
                                        CONCAT('<div style=\"text-align:left\">',cu.nombre,'</div>') as compania, 
                                        (SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.persona=per.id LIMIT 1) as email,
                                        (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.persona=per.id LIMIT 0,1 ) as phone
                                        FROM crm_contacto con 
                                        INNER JOIN ctl_persona per on(con.persona=per.id)                                        
                                        INNER JOIN ctl_correo corr on(corr.persona=per.id)                                        
                                        INNER JOIN ctl_telefono tel on(tel.persona=per.id)
                                        INNER JOIN crm_contacto_cuenta c_c on(con.id=c_c.contacto)
                                        INNER JOIN crm_cuenta cu on(cu.id=c_c.cuenta)
                                        WHERE con.estado = 1
                                        GROUP BY 1
                                        ORDER BY " . $orderByText . " " . $orderDir;               
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();
                $row['data'] = $stmt->fetchAll();  
                //var_dump($row['data']);                
            }
                        
            return new Response(json_encode($row));
        } catch (\Exception $e) {
            var_dump($e);
            if (method_exists($e, 'getErrorCode')) {
                switch (intval($e->getErrorCode())) {
                    case 2003:
                        $serverOffline = $this->getParameter('app.serverOffline');
                        $row['data'][0]['name'] = $serverOffline . '. CODE: ' . $e->getErrorCode();
                        break;
                    default :
                        $row['data'][0]['name'] = $e->getMessage();
                        break;
                }
                $row['data'][0]['chk'] = '';

                $row['recordsFiltered'] = 0;
            } else {
                $data['error'] = $e->getMessage();
            }
            return new Response(json_encode($row));
        }
    }
    //////Fin de contacto

    
    /**
     * Save contacto
     *
     * @Route("/save", name="admin_contacto_save_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function saveajaxAction(Request $request) {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if ($isAjax) {
            try {
                $em = $this->getDoctrine()->getEntityManager();
                $em->getConnection()->beginTransaction();
                $response = new JsonResponse();
                $imgData = $_FILES;
                               
                $idCuenta = $_POST['id1']; //id crmCuenta pero solo un momento mientras se hace el edit, el verdadero ahi que tiene es el de idContacto
                $idContacto = $idCuenta;
                //var_dump($idCuenta);
                //die();
                $idPersona = $_POST['id2']; //id ctlPersona 
                                
                $clientePotencial = null; //cliente potencial, nulo
               
                $idCompania = $_POST['compania']; //Este es el id de cuenta pero se refiere a la compañia
                
                //$descripcionCuenta = ''; //descripcion
                $fechaRegistro = new \DateTime('now'); //descripcion
                
                $estado = 1; //Estado
                
                //persona
                $sucursal = null; //sucursal, nulo
                $tratamientoProtocolarioId = $_POST['titulo']; //tratamiento protocolario
                
                $nombrePersona = $_POST['nombre']; //nombre persona
                
                $apellidoPersona = $_POST['apellido']; //apellido persona
                
                $genero = null; //genero persona, null
                //Correos
                $emailArray = $_POST['email']; //apellido persona
                
                //Telefonos
                $phoneTypeArray = $_POST['phoneType']; //apellido persona
               
                $phoneArray = $_POST['phone']; //apellido persona
                
                $phoneExtArray = $_POST['phoneExt']; //apellido persona
                
                //Dirección
                $addressArray = $_POST['address']; //apellido persona
           
                $addressCityArray = $_POST['addressCity']; //apellido persona
              
                $addressDepartamentoArray = $_POST['addressDepartamento']; //apellido persona
              

                //Busqueda objetos a partir de ids
                // $tipoEntidadObj = $em->getRepository('ERPCRMBundle:CtlTipoEntidad')->find($tipoEntidadId); NULL
                $tratamientoProtocolarioObj = $em->getRepository('ERPCRMBundle:CtlTratamientoProtocolario')->find($tratamientoProtocolarioId);
                //$crmTipoCuentaObj = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->find(2); //Cliente potencial
                // var_dump($_POST);
                // die();
                if ($idCuenta == '' && $idPersona == '') {
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
                    $crmContactoObj->setPersona($ctlPersonaObj);                                       
                    $crmContactoObj->setEstado(1);
                                                          
                    //Persist crmContacto
                    $em->persist($crmContactoObj);
                    $em->flush();
                    
                    //Persistiendo la tabla contacto_cuenta                  
                    $crmContactoCuentaObj = new CrmContactoCuenta();
                    $cuentaObj = $em->getRepository('ERPCRMBundle:CrmCuenta')->find($idCompania);
                    $crmContactoCuentaObj->setCuenta($cuentaObj);
                    $crmContactoCuentaObj->setContacto($crmContactoObj);
                    $em->persist($crmContactoCuentaObj);
                    $em->flush();
                    
                    //Tabla ctlCorreo
                    foreach ($emailArray as $key => $correo) {
                        $ctlCorreoObj = new CtlCorreo();
                        $ctlCorreoObj->setEmpresa(null);
                        $ctlCorreoObj->setPersona($ctlPersonaObj);
                        $ctlCorreoObj->setCuenta(null);
                        $ctlCorreoObj->setEmail($correo);
                        $ctlCorreoObj->setEstado(1);
                        //Persist ctlCorreo
                        $em->persist($ctlCorreoObj);
                        $em->flush();
                    }


                    //Tabla ctlTelefono
                    $phoneLenght = count($phoneArray) - 1; //Cantidad de telefono ingresados, menos 1 para index de array
                    //var_dump($phoneTypeArray[0]);
                    $ctlTipoTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->find($phoneTypeArray[0]); //Para definir la variable $ctlTipoTelefonoObj
                    foreach ($phoneArray as $key => $phone) {

                        $ctlTelefonoObj = new CtlTelefono();
                        $ctlTelefonoObj->setPersona($ctlPersonaObj);
                        //var_dump($key);
                        if ($key < $phoneLenght && $key != 0) {
                            if ($phoneTypeArray[$key] == $phoneTypeArray[$key - 1]) {
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
                        $ctlTelefonoObj->setPersona($ctlPersonaObj);
                        $ctlTelefonoObj->setEmpresa(null);
                        $ctlTelefonoObj->setSucursal(null);
                        //Persist ctlTelefono
                        $em->persist($ctlTelefonoObj);
                        $em->flush();
                    }

                    //Tabla ctlDireccion
                    $addressLenght = count($addressArray) - 1; //Cantidad de direccion ingresados, menos 1 para index de array
                    // var_dump(count($addressArray));
                    // var_dump($addressLenght);
                    foreach ($addressArray as $key => $val) {

                        $ctlDireccionObj = new CtlDireccion();
                        $ctlDireccionObj->setPersona($ctlPersonaObj);
                        if ($key < $addressLenght && $key != 0) {
                            if ($addressCityArray[$key] == $addressCityArray[$key - 1]) {
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
                        $ctlDireccionObj->setPersona($ctlPersonaObj);
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
                    if ($nombreTmp != '') {
                        //Buscar en la base la ciudad, primera iteracion debe buscar ciudad

                        $path = $this->getParameter('photo.contacto');
                        //var_dump($path);
                        $fecha = date('Y-m-d-H-i-s');
                        $extensionTmp = $_FILES['file']['type'];
                        $extensionArray = explode('/', $extensionTmp);
                        $extension = $extensionArray[1];
                        $nombreArchivo = $fecha . "." . $extension;
                        //var_dump($nombreArchivo);
                        if (move_uploaded_file($_FILES['file']['tmp_name'], $path . $nombreArchivo)) {
                            $crmFoto = $em->getRepository('ERPCRMBundle:CrmFoto')->find($crmContactoObj->getId());
                            if (count($crmFoto) != 0) {
                                //unlink($path.$crmFoto->getSrc());
                                //crmFoto
                                $crmFoto->setSrc($nombreArchivo);
                                $em->merge($crmFoto);
                                $em->flush();
                            } else {
                                //crmFoto
                                $crmFoto = new CrmFoto();
                                $crmFoto->setCuenta(null);
                                $crmFoto->setPersona($ctlPersonaObj);
                                $crmFoto->setEstado(1);
                                $crmFoto->setSrc($nombreArchivo);
                                $em->persist($crmFoto);
                                $em->flush();
                            }
                        } else {//Error al subir foto
                        }
                    } else {//Foto vacia
                        //var_dump('No file');
                    }
                    $serverSave = $this->getParameter('app.serverMsgSave');
                    $data['id1'] = $crmContactoObj->getId();
                    $data['id2'] = $ctlPersonaObj->getId();
                    $data['msg'] = $serverSave;
                }//Fin de if id, inserción
                //else para la modificación del objeto crmContacto y sus tablas dependientes
                else {
                    
                    //Persistiendo la tabla contacto_cuenta                  
                    
                    //$crmCuentaObj = new CrmCuenta();
                    //$crmContactoObj = new CrmContacto();
                    
                    $crmContactoObj = $em->getRepository('ERPCRMBundle:CrmContacto')->find($idContacto);
                    
                    $crmContactoCuentaObj = $em->getRepository('ERPCRMBundle:CrmContactoCuenta')->findBy(array('contacto' => $crmContactoObj));                             
                    
                    $crmCuentaObj = $em->getRepository('ERPCRMBundle:CrmCuenta')->find($idCompania);
                    //echo $idContacto;
                    //var_dump($idCompania);
                    $crmContactoCuentaObj[0]->setCuenta($crmCuentaObj);
                                        
                    $em->merge($crmContactoCuentaObj[0]);
                    $em->flush();
                    //die();
                    //HASTA AQUI VA LA PARTE DE LA PERSISREBCIA DE LA COMPAÑIA
                    // var_dump($idCuenta);
                    //Eliminar telefonos
                    $ctlTelefonoArrayObj = $em->getRepository('ERPCRMBundle:CtlTelefono')->findBy(array('cuenta' => $idCuenta));
                    foreach ($ctlTelefonoArrayObj as $key => $value) {
                        $em->remove($value);
                        $em->flush();
                    }  

                    //Eliminar correos
                    $ctlCorreoArrayObj = $em->getRepository('ERPCRMBundle:CtlCorreo')->findBy(array('cuenta' => $idCuenta));
                    foreach ($ctlCorreoArrayObj as $key => $value) {
                        $em->remove($value);
                        $em->flush();
                    }

                    //Eliminar direccion
                    $ctlDireccionArrayObj = $em->getRepository('ERPCRMBundle:CtlDireccion')->findBy(array('cuenta' => $idCuenta));
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
                    $phoneLenght = count($phoneArray); //Cantidad de telefono ingresados, menos 1 para index de array
                    //var_dump($phoneTypeArray[0]);
                    $ctlTipoTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->find($phoneTypeArray[0]); //Para definir la variable $ctlTipoTelefonoObj

                    foreach ($phoneArray as $key => $phone) {
                        // var_dump('for');
                        $ctlTelefonoObj = new CtlTelefono();
                        $ctlTelefonoObj->setCuenta($crmCuentaObj);
                        // var_dump("key".$key);
                        // var_dump("\nphone length".$phoneLenght);
                        // var_dump("\n expresion".($key<$phoneLenght && $key!=0));
                        if ($key < $phoneLenght && $key != 0) {
                            // var_dump('if');
                            if ($phoneTypeArray[$key] == $phoneTypeArray[$key - 1]) {
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
                    $addressLenght = count($addressArray); //Cantidad de direccion ingresados, menos 1 para index de array
                    foreach ($addressArray as $key => $phone) {

                        $ctlDireccionObj = new CtlDireccion();
                        $ctlDireccionObj->setCuenta($crmCuentaObj);
                        if ($key < $addressLenght && $key != 0) {
                            if ($addressCityArray[$key] == $addressCityArray[$key - 1]) {
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
                    var_dump($_FILES['file']);
                    if ($nombreTmp != '') {
                        //Buscar en la base la ciudad, primera iteracion debe buscar ciudad

                        $path = $this->getParameter('photo.contacto');
                        //var_dump($path);
                        $fecha = date('Y-m-d-H-i-s');
                        $extensionTmp = $_FILES['file']['type'];
                        $extensionArray = explode('/', $extensionTmp);
                        $extension = $extensionArray[1];
                        $nombreArchivo = $fecha . "." . $extension;
                        //var_dump($nombreArchivo);
                        if (move_uploaded_file($_FILES['file']['tmp_name'], $path . $nombreArchivo)) {
                            $crmFoto = $em->getRepository('ERPCRMBundle:CrmFoto')->find($crmContactoObj->getId());
                            if (count($crmFoto) != 0) {
                                unlink($path . $crmFoto[0]->getSrc());
                                //crmFoto
                                $crmFoto[0]->setSrc($nombreArchivo);
                                $em->merge($crmFoto[0]);
                                $em->flush();
                            } else {
                                //crmFoto
                                $crmFoto = new CrmFoto();
                                $crmFoto->setCuenta(null);
                                $crmFoto->setPersona($ctlPersonaObj);
                                $crmFoto->setEstado(1);
                                $crmFoto->setSrc($nombreArchivo);
                                $em->merge($crmFoto);
                                $em->flush();
                            }
                        } else {//Error al subir foto
                        }
                    } else {//Foto vacia
                        //var_dump('No file');
                    }
                    
                    $serverSave = $this->getParameter('app.serverMsgUpdate');
                    $data['msg'] = $serverSave;
                    $data['id1'] = $idCuenta;
                    $data['id2'] = $idPersona;
                }
                $em->getConnection()->commit();
                $em->close();
                $response->setData($data);
            } catch (\Exception $e) {
                var_dump($e);
                $em->getConnection()->rollback();
                $em->close();
                // var_dump($e);
                if (method_exists($e, 'getErrorCode')) {
                    switch (intval($e->getErrorCode())) {
                        case 2003:
                            $serverOffline = $this->getParameter('app.serverOffline');
                            $data['error'] = $serverOffline . '. CODE: ' . $e->getErrorCode();
                            break;
                        case 1062:
                            $serverDuplicate = $this->getParameter('app.serverDuplicateName');
                            $data['error'] = $serverDuplicate . "! CODE: " . $e->getErrorCode();
                            break;
                        default :
                            $data['error'] = "Error CODE: " . $e->getMessage();
                            break;
                    }
                } else {
                    $data['error'] = $e->getMessage();
                }
                $response->setData($data);
            }
        } else {
            $data['error'] = 'Ajax request';
            $response->setData($data);
        }
        return $response;
    }
    
    /**
     * Delete contacto
     *
     * @Route("/contact/delete", name="admin_contact_delete_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function deleteajaxAction(Request $request) {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if ($isAjax) {
            try {
                $ids = $request->get("param1");
                
                $response = new JsonResponse();
                
                $em = $this->getDoctrine()->getManager();
                foreach ($ids as $key => $id) {
                    $object = $em->getRepository('ERPCRMBundle:CrmContacto')->find($id);
                    if (count($object)) {
                        $object->setEstado(0);
                        $em->merge($object);
                        $em->flush();
                        $serverDelete = $this->getParameter('app.serverMsgDelete');
                        $data['msg'] = $serverDelete;
                    } else {                        
                        $data['error'] = "Error";
                    }
                }
                $response->setData($data);
            } catch (\Exception $e) {
                if (method_exists($e, 'getErrorCode')) {
                    switch (intval($e->getErrorCode())) {
                        case 2003:
                            $serverOffline = $this->getParameter('app.serverOffline');
                            $data['error'] = $serverOffline . '. CODE: ' . $e->getErrorCode();
                            break;
                        default :
                            $data['error'] = $e->getMessage();
                            break;
                    }
                } else {
                    $data['error'] = $e->getMessage();
                }
                $response->setData($data);
            }
        } else {
            $data['error'] = 'Ajax request';
            $response->setData($data);
        }
        return $response;
    }
    //Fin eliminar contacto
    
    /**
     * Retrieve clients
     *
     * @Route("/contact/retrieve", name="admin_contact_retrieve_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function retrieveajaxAction(Request $request) {
        try {
            $idContacto = $request->get("param1");
            //echo $idContacto;            
            $response = new JsonResponse();
            
            $em = $this->getDoctrine()->getManager();
            $crmContactoObj = $em->getRepository('ERPCRMBundle:CrmContacto')->find($idContacto);
            
            //$crmCuentaObj = $em->getRepository('ERPCRMBundle:CrmCuenta')->find($idCuenta);
            
            $ctlCorreoObj = $em->getRepository('ERPCRMBundle:CtlCorreo')->findBy(array ('persona'=>$crmContactoObj->getPersona()));//Obtenemos el correo 
            $crmFotoObj = $em->getRepository('ERPCRMBundle:CrmFoto')->findBy(array ('persona'=>$crmContactoObj->getPersona()));//Obtenemos el correo 
            $ctlTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTelefono')->findBy(array ('persona'=>$crmContactoObj->getPersona()));//Obtenemos  el telefono podemos sacar el tipo
            $ctlDireccionObj = $em->getRepository('ERPCRMBundle:CtlDireccion')->findBy(array ('persona'=>$crmContactoObj->getPersona()));//Obtenemos  la direccion y podemos obtener el resto
                      
            $crmContactoCuentaObj = $em->getRepository('ERPCRMBundle:CrmContactoCuenta')->findBy(array('contacto' =>$crmContactoObj));
            $crmCuentaObj = $crmContactoCuentaObj[0]->getCuenta(); 
            
            $ctlPersonaObj = $crmContactoObj->getPersona();
                        
            //var_dump($crmFotoObj);
            //die();
            /*$ctlTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTelefono')->findBy(array('cuenta' => $crmCuentaObj->getId()));
            $ctlCorreoObj = $em->getRepository('ERPCRMBundle:CtlCorreo')->findBy(array('cuenta' => $crmCuentaObj->getId()));
            $ctlDireccionObj = $em->getRepository('ERPCRMBundle:CtlDireccion')->findBy(array('cuenta' => $crmCuentaObj->getId()));
            $crmFotoObj = $em->getRepository('ERPCRMBundle:CrmFoto')->findBy(array('cuenta' => $crmCuentaObj->getId()));*/
                        
            // var_dump($ctlTelefonoObj);
            if (count($crmContactoObj) != 0) {
                
                //$object->setProbabilidad($);
                //$em->merge($object);
                //$em->flush();    
                //var_dump($crmCuentaObj);
                // die();
                $data['nombre'] = $ctlPersonaObj->getNombre();
                $data['apellido'] = $ctlPersonaObj->getApellido();
                $data['compania'] = $crmCuentaObj->getId();
                

                // var_dump(count($ctlDireccionObj));
                // var_dump(count($ctlTelefonoObj));
                // var_dump(count($ctlCorreoObj));
                // var_dump(count($crmFotoObj));
                if (count($ctlDireccionObj) != 0) {
                    $dirArray = array();
                    $cityArray = array();
                    $stateArray = array();
                    foreach ($ctlDireccionObj as $key => $value) {
                        array_push($dirArray, $value->getDireccion());
                        array_push($cityArray, $value->getCiudad()->getId());
                        array_push($stateArray, $value->getCiudad()->getEstado()->getId());
                    }
                    // $data['addressArray']=$ctlDireccionObj[0];
                    $data['addressArray'] = $dirArray;
                    $data['cityArray'] = $cityArray;
                    $data['stateArray'] = $stateArray;
                                        
                } else {
                    $data['addressArray'] = [];
                }
                if (count($ctlTelefonoObj) != 0) {

                    // $data['phoneArray']=$ctlTelefonoObj[0];
                    $telTipoArray = array();
                    $telArray = array();
                    $telExtArray = array();
                    foreach ($ctlTelefonoObj as $key => $value) {
                        array_push($telTipoArray, $value->getTipoTelefono()->getId());
                        array_push($telArray, $value->getNumTelefonico());
                        array_push($telExtArray, $value->getExtension());
                    }
                    // $data['addressArray']=$ctlDireccionObj[0];
                    $data['typePhoneArray'] = $telTipoArray;
                    $data['phoneArray'] = $telArray;
                    $data['extPhoneArray'] = $telExtArray;
                } else {
                    $data['phoneArray'] = '';
                    $data['phoneArray'] = '';
                    $data['phoneArray'] = '';
                }
                if (count($ctlCorreoObj) != 0) {
                    // $data['emailArray']=$ctlCorreoObj[0];
                    // $data['phoneArray']=$ctlTelefonoObj[0];
                    $dirArray = array();
                    foreach ($ctlCorreoObj as $key => $value) {
                        array_push($dirArray, $value->getEmail());
                    }
                    // $data['addressArray']=$ctlDireccionObj[0];
                    $data['emailArray'] = $dirArray;
                } else {
                    $data['emailArray'] = '';
                }
                if (count($crmFotoObj) != 0) {
                    // $data['src']=$crmFotoObj[0]->getSrc();                    
                    $dirArray = array();
                    foreach ($crmFotoObj as $key => $value) {
                        array_push($dirArray, $value->getSrc());
                    }
                    // $data['addressArray']=$ctlDireccionObj[0];
                    $data['src'] = $dirArray;
                } else {
                    $data['src'] = '';
                }

                // $data['addressArray']=$ctlDireccionObj[0];
                // $data['phoneArray']=$ctlTelefonoObj[0];
                // $data['emailArray']=$ctlCorreoObj[0];
                // $data['src']=$crmFotoObj[0]->getSrc();
                $data['titulo'] = $ctlPersonaObj->getTratamientoProtocolario()->getId();
                //$data['interes'] = $crmClientePotencialObj[0]->getNivelInteres()->getId();
                //$data['estado'] = $crmClientePotencialObj[0]->getEstadoClientePotencial()->getId();
                //$data['fuente'] = $crmClientePotencialObj[0]->getFuentePrincipal()->getId();
                // if ($crmClientePotencialObj[0]->getCampania()!=null) {
                //     $data['campania']=$crmClientePotencialObj[0]->getCampania()->getId();
                // } else {
                //     $data['campania']='';
                // }

                $data['id1'] = $crmContactoObj->getId();
                $data['id2'] = $crmContactoObj->getPersona()->getId();
            } else {
                $data['error'] = "Error";
            }

            $response->setData($data);
        } catch (\Exception $e) {
            // var_dump($e);
            if (method_exists($e, 'getErrorCode')) {
                switch (intval($e->getErrorCode())) {
                    case 2003:
                        $serverOffline = $this->getParameter('app.serverOffline');
                        $data['error'] = $serverOffline . '. CODE: ' . $e->getErrorCode();
                        break;
                    default :
                        $data['error'] = "Error CODE: " . $e->getMessage();
                        break;
                }
            } else {
                $data['error'] = $e->getMessage();
            }
            $response->setData($data);
        }

        return $response;
    }

}
