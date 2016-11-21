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
 * CrmCuenta controller.
 *
 * @Route("/admin/account")
 */
class CrmCuentaController extends Controller
{
    /**
     * Lists all CrmCuenta entities.
     *
     * @Route("/providers", name="admin_providers_accounts_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        try{
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
            //Tipo industria
            $industrias = $em->getRepository('ERPCRMBundle:CtlIndustria')->findAll();
            //Tipos telefono
            $tiposTelefono = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->findAll();
            //Tipos de cuenta
            $tiposCuenta = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->findBy(array('estado'=>1));
            //Tipos de etiqueta
            $etiquetas = $em->getRepository('ERPCRMBundle:CrmEtiqueta')->findAll();

            return $this->render('crmcuenta/index_provider.html.twig', array(
                // 'crmCuentas' => $crmCuentas,
                'items'=>$items,
                //'estados'=>$estados,
                //'ciudades'=>$ciudades,
                'personas'=>$personas,
                'industrias'=>$industrias,
                'tiposTelefono'=>$tiposTelefono,
                'tiposCuenta'=>$tiposCuenta,
                'etiquetas'=>$etiquetas,
                'menuProveedorA' => 1,
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




    ///Method for management accounts
    /**
     * Lists all CrmCuenta entities.
     *
     * @Route("/accounts/{nombre}", name="admin_accounts_index")
     * @Method("GET")
     */
    public function indexaccountsAction($nombre)
    {
        try{
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
            //Tipo industria
            $industrias = $em->getRepository('ERPCRMBundle:CtlIndustria')->findAll();
            //Tipos telefono
            $tiposTelefono = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->findAll();
            //Tipos de cuenta
            $tiposCuenta = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->findBy(array('estado'=>1));
            //Tipos de etiqueta
            $etiquetas = $em->getRepository('ERPCRMBundle:CrmEtiqueta')->findAll();

            return $this->render('crmcuenta/index_account.html.twig', array(
                // 'crmCuentas' => $crmCuentas,
                'items'=>$items,
                //'estados'=>$estados,
                //'ciudades'=>$ciudades,
                'personas'=>$personas,
                'industrias'=>$industrias,
                'tiposTelefono'=>$tiposTelefono,
                'tiposCuenta'=>$tiposCuenta,
                'etiquetas'=>$etiquetas,
                'opcion' => $nombre,
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





/////Accounts

    /**
     * List all accounts, cliente, cliente potrencial, proveedor, etc
     *
     * @Route("/accounts/data/list", name="admin_accounts_data",  options={"expose"=true}))
     */
    public function dataaccountsAction(Request $request)
    {
            try {
                $start = $request->query->get('start');
                $draw = $request->query->get('draw');
                $longitud = $request->query->get('length');
                $busqueda = $request->query->get('search');
                // var_dump("edwed");
                $tagId = $request->query->get('param1');
                $nombre = $request->query->get('nombre');
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
                        $orderByText = "account";
                        break;
                    case 3:
                        $orderByText = "email";
                        break;
                    case 4:
                        $orderByText = "phone";
                        break;
                    case 5:
                        $orderByText = "industry";
                        break;
                }
                $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
                if($busqueda['value']!=''){
                    if ($tagId==0) {
                            $sql = "SELECT DISTINCT(c.id), CONCAT('<div id=\"',c.id,'-',per.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',per.nombre,' ',per.apellido,'</div>') as name, c.id, (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.cuenta=c.id LIMIT 0,1 ) as phone,(SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.cuenta=c.id LIMIT 1) as email, ind.nombre as industry, CONCAT('<div style=\"text-align:left\">',c.nombre,'</div>') as account, tip.nombre as tipo, c.fecha_registro as dateReg
                                        FROM crm_contacto_cuenta cc
                                        INNER JOIN crm_cuenta c on(cc.cuenta=c.id)
                                        INNER JOIN ctl_industria ind on(c.industria=ind.id)
                                        INNER JOIN crm_contacto con on(cc.contacto=con.id)
                                        INNER JOIN ctl_persona per on(con.persona=per.id)
                                        INNER JOIN crm_tipo_cuenta tip on(c.tipo_cuenta=tip.id)
                                        WHERE tip.nombre='".$nombre."' AND  per.id<>1 AND c.estado=1 
                                        GROUP BY 1
                                        HAVING CONCAT(name,' ',account,' ', email,' ',phone,' ',industry) LIKE upper('%".$busqueda['value']."%')
                                        ORDER BY ". $orderByText." ".$orderDir;
                            $stmt = $em->getConnection()->prepare($sql);
                            $stmt->execute();
                            $row['data'] = $stmt->fetchAll();
                            $row['recordsFiltered']= count($row['data']);
                            $sql = "SELECT DISTINCT(c.id), CONCAT('<div id=\"',c.id,'-',per.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',per.nombre,' ',per.apellido,'</div>') as name, c.id, (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.cuenta=c.id LIMIT 0,1 ) as phone,(SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.cuenta=c.id LIMIT 1) as email, ind.nombre as industry, CONCAT('<div style=\"text-align:left\">',c.nombre,'</div>') as account, tip.nombre as tipo, c.fecha_registro as dateReg
                                        FROM crm_contacto_cuenta cc
                                        INNER JOIN crm_cuenta c on(cc.cuenta=c.id)
                                        INNER JOIN ctl_industria ind on(c.industria=ind.id)
                                        INNER JOIN crm_contacto con on(cc.contacto=con.id)
                                        INNER JOIN ctl_persona per on(con.persona=per.id)
                                        INNER JOIN crm_tipo_cuenta tip on(c.tipo_cuenta=tip.id)
                                        WHERE tip.nombre='".$nombre."' AND per.id<>1 AND c.estado=1
                                        GROUP BY 1
                                        HAVING CONCAT(name,' ',account,' ', email,' ',phone,' ',industry) LIKE upper('%".$busqueda['value']."%')
                                        ORDER BY ". $orderByText." ".$orderDir." LIMIT " . $start . "," . $longitud;
                            $stmt = $em->getConnection()->prepare($sql);
                            $stmt->execute();
                            $row['data'] = $stmt->fetchAll();
                    }
                    else{
                        $sql = "SELECT DISTINCT(c.id), CONCAT('<div id=\"',c.id,'-',per.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',per.nombre,' ',per.apellido,'</div>') as name, c.id, (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.cuenta=c.id LIMIT 0,1 ) as phone,(SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.cuenta=c.id LIMIT 1) as email, ind.nombre as industry, CONCAT('<div style=\"text-align:left\">',c.nombre,'</div>') as account, tip.nombre as tipo, c.fecha_registro as dateReg
                                    FROM crm_contacto_cuenta cc
                                    INNER JOIN crm_cuenta c on(cc.cuenta=c.id)
                                    INNER JOIN ctl_industria ind on(c.industria=ind.id)
                                    INNER JOIN crm_contacto con on(cc.contacto=con.id)
                                    INNER JOIN ctl_persona per on(con.persona=per.id)
                                    INNER JOIN crm_tipo_cuenta tip on(c.tipo_cuenta=tip.id)
                                    INNER JOIN crm_etiqueta_cuenta ec on(ec.cuenta=c.id)
                                    INNER JOIN crm_etiqueta e on(ec.etiqueta=e.id)
                                    WHERE tip.nombre='".$nombre."' AND  per.id<>1 AND c.estado=1 AND e.id =".$tagId."
                                    GROUP BY 1
                                    HAVING CONCAT(name,' ',account,' ', email,' ',phone,' ',industry) LIKE upper('%".$busqueda['value']."%')
                                    ORDER BY ". $orderByText." ".$orderDir;
                            $stmt = $em->getConnection()->prepare($sql);
                            $stmt->execute();
                            $row['data'] = $stmt->fetchAll();
                            $row['recordsFiltered']= count($row['data']);
                            $sql = "SELECT DISTINCT(c.id), CONCAT('<div id=\"',c.id,'-',per.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',per.nombre,' ',per.apellido,'</div>') as name, c.id, (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.cuenta=c.id LIMIT 0,1 ) as phone,(SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.cuenta=c.id LIMIT 1) as email, ind.nombre as industry, CONCAT('<div style=\"text-align:left\">',c.nombre,'</div>') as account, tip.nombre as tipo, c.fecha_registro as dateReg
                                        FROM crm_contacto_cuenta cc
                                        INNER JOIN crm_cuenta c on(cc.cuenta=c.id)
                                        INNER JOIN ctl_industria ind on(c.industria=ind.id)
                                        INNER JOIN crm_contacto con on(cc.contacto=con.id)
                                        INNER JOIN ctl_persona per on(con.persona=per.id)
                                        INNER JOIN crm_tipo_cuenta tip on(c.tipo_cuenta=tip.id)
                                        INNER JOIN crm_etiqueta_cuenta ec on(ec.cuenta=c.id)
                                        INNER JOIN crm_etiqueta e on(ec.etiqueta=e.id)
                                        WHERE tip.nombre='".$nombre."' AND per.id<>1 AND c.estado=1 AND e.id =".$tagId."
                                        GROUP BY 1
                                        HAVING CONCAT(name,' ',account,' ', email,' ',phone,' ',industry) LIKE upper('%".$busqueda['value']."%')
                                        ORDER BY ". $orderByText." ".$orderDir." LIMIT " . $start . "," . $longitud;
                            $stmt = $em->getConnection()->prepare($sql);
                            $stmt->execute();
                            $row['data'] = $stmt->fetchAll();
                    }
                }
                else{
                    if ($tagId==0) {
                            $sql = "SELECT DISTINCT(c.id), CONCAT('<div id=\"',c.id,'-',per.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',per.nombre,' ',per.apellido,'</div>') as name, c.id, (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.cuenta=c.id LIMIT 0,1 ) as phone,(SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.cuenta=c.id LIMIT 1) as email, ind.nombre as industry, CONCAT('<div style=\"text-align:left\">',c.nombre,'</div>') as account, tip.nombre as tipo, c.fecha_registro as dateReg
                                        FROM crm_contacto_cuenta cc
                                        INNER JOIN crm_cuenta c on(cc.cuenta=c.id)
                                        INNER JOIN ctl_industria ind on(c.industria=ind.id)
                                        INNER JOIN crm_contacto con on(cc.contacto=con.id)
                                        INNER JOIN ctl_persona per on(con.persona=per.id)
                                        INNER JOIN crm_tipo_cuenta tip on(c.tipo_cuenta=tip.id)
                                        WHERE per.id<>1 AND c.estado=1 AND tip.nombre='".$nombre."'
                                        GROUP BY 1
                                        ORDER BY ". $orderByText." ".$orderDir;
                            $stmt = $em->getConnection()->prepare($sql);
                            $stmt->execute();
                            $row['data'] = $stmt->fetchAll();
                    }
                    else{
                        $sql = "SELECT DISTINCT(c.id), CONCAT('<div id=\"',c.id,'-',per.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',per.nombre,' ',per.apellido,'</div>') as name, c.id, (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.cuenta=c.id LIMIT 0,1 ) as phone,(SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.cuenta=c.id LIMIT 1) as email, ind.nombre as industry, CONCAT('<div style=\"text-align:left\">',c.nombre,'</div>') as account, tip.nombre as tipo, c.fecha_registro as dateReg
                                        FROM crm_contacto_cuenta cc
                                        INNER JOIN crm_cuenta c on(cc.cuenta=c.id)
                                        INNER JOIN ctl_industria ind on(c.industria=ind.id)
                                        INNER JOIN crm_contacto con on(cc.contacto=con.id)
                                        INNER JOIN ctl_persona per on(con.persona=per.id)
                                        INNER JOIN crm_tipo_cuenta tip on(c.tipo_cuenta=tip.id)
                                        INNER JOIN crm_etiqueta_cuenta ec on(ec.cuenta=c.id)
                                        INNER JOIN crm_etiqueta e on(ec.etiqueta=e.id)
                                        WHERE per.id<>1 AND c.estado=1 AND tip.nombre='".$nombre."' AND e.id =".$tagId."
                                        GROUP BY 1
                                        ORDER BY ". $orderByText." ".$orderDir;
                            $stmt = $em->getConnection()->prepare($sql);
                            $stmt->execute();
                            $row['data'] = $stmt->fetchAll();
                    }
                }
                return new Response(json_encode($row));
            } catch (\Exception $e) {
                // var_dump($e);
                if(method_exists($e,'getErrorCode')){
                    switch (intval($e->getErrorCode()))
                    {
                        case 2003: 
                            $serverOffline= $this->getParameter('app.serverOffline');
                            $row['data'][0]['name'] =$serverOffline.'. CODE: '.$e->getErrorCode();
                        break;
                        default:
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


    //////Fin de accounts






    /////Proveedores

    /**
     * List level of provders
     *
     * @Route("/providers/data/list", name="admin_providers_data",  options={"expose"=true}))
     */
    public function dataprovidersAction(Request $request)
    {
            try {
                $start = $request->query->get('start');
                $draw = $request->query->get('draw');
                $longitud = $request->query->get('length');
                $busqueda = $request->query->get('search');
                $tagId = $request->query->get('param1');
                // var_dump($tagId);
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
                        $orderByText = "account";
                        break;
                    case 3:
                        $orderByText = "email";
                        break;
                    case 4:
                        $orderByText = "phone";
                        break;
                    case 5:
                        $orderByText = "industry";
                        break;
                }
                $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
                if($busqueda['value']!=''){
                    // echo "buscando";
                    if ($tagId==0) {

                                $sql = "SELECT DISTINCT(c.id), CONCAT('<div id=\"',c.id,'-',per.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',per.nombre,' ',per.apellido,'</div>') as name, c.id, (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.cuenta=c.id LIMIT 0,1 ) as phone,(SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.cuenta=c.id LIMIT 1) as email, ind.nombre as industry, CONCAT('<div style=\"text-align:left\">',c.nombre,'</div>') as account
                                            FROM crm_contacto_cuenta cc
                                            INNER JOIN crm_cuenta c on(cc.cuenta=c.id)
                                            INNER JOIN ctl_industria ind on(c.industria=ind.id)
                                            INNER JOIN crm_contacto con on(cc.contacto=con.id)
                                            INNER JOIN ctl_persona per on(con.persona=per.id)
                                            WHERE c.tipo_cuenta=1 AND  per.id<>1 AND c.estado=1
                                            GROUP BY 1
                                            HAVING CONCAT(name,' ',phone,' ',email,' ',industry,' ',account) LIKE upper('%".$busqueda['value']."%')  
                                            ORDER BY ". $orderByText." ".$orderDir;
                                $stmt = $em->getConnection()->prepare($sql);
                                $stmt->execute();
                                $row['data'] = $stmt->fetchAll();
                                $row['recordsFiltered']= count($row['data']);
                                $sql = "SELECT DISTINCT(c.id), CONCAT('<div id=\"',c.id,'-',per.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',per.nombre,' ',per.apellido,'</div>') as name, c.id, (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.cuenta=c.id LIMIT 0,1 ) as phone,(SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.cuenta=c.id LIMIT 1) as email, ind.nombre as industry, CONCAT('<div style=\"text-align:left\">',c.nombre,'</div>') as account
                                            FROM crm_contacto_cuenta cc
                                            INNER JOIN crm_cuenta c on(cc.cuenta=c.id)
                                            INNER JOIN ctl_industria ind on(c.industria=ind.id)
                                            INNER JOIN crm_contacto con on(cc.contacto=con.id)
                                            INNER JOIN ctl_persona per on(con.persona=per.id)
                                            WHERE c.tipo_cuenta=1 AND per.id<>1 AND c.estado=1
                                            GROUP BY 1
                                            HAVING CONCAT(name,' ',phone,' ',email,' ',industry,' ',account) LIKE upper('%".$busqueda['value']."%')  
                                            ORDER BY ". $orderByText." ".$orderDir." LIMIT " . $start . "," . $longitud;
                                $stmt = $em->getConnection()->prepare($sql);
                                $stmt->execute();
                                $row['data'] = $stmt->fetchAll();
                    }
                    else{
                                $sql = "SELECT DISTINCT(c.id), CONCAT('<div id=\"',c.id,'-',per.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',per.nombre,' ',per.apellido,'</div>') as name, c.id, (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.cuenta=c.id LIMIT 0,1 ) as phone,(SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.cuenta=c.id LIMIT 1) as email, ind.nombre as industry, CONCAT('<div style=\"text-align:left\">',c.nombre,'</div>') as account
                                                FROM crm_contacto_cuenta cc
                                                INNER JOIN crm_cuenta c on(cc.cuenta=c.id)
                                                INNER JOIN ctl_industria ind on(c.industria=ind.id)
                                                INNER JOIN crm_contacto con on(cc.contacto=con.id)
                                                INNER JOIN ctl_persona per on(con.persona=per.id)
                                                INNER JOIN crm_etiqueta_cuenta ec on(ec.cuenta=c.id)
                                                INNER JOIN crm_etiqueta e on(ec.etiqueta=e.id)
                                                WHERE c.tipo_cuenta=1 AND  per.id<>1 AND c.estado=1 AND e.id =".$tagId."
                                                GROUP BY 1
                                                HAVING CONCAT(name,' ',phone,' ',email,' ',industry,' ',account) LIKE upper('%".$busqueda['value']."%')  
                                                ORDER BY ". $orderByText." ".$orderDir;
                                    $stmt = $em->getConnection()->prepare($sql);
                                    $stmt->execute();
                                    $row['data'] = $stmt->fetchAll();
                                    $row['recordsFiltered']= count($row['data']);
                                    $sql = "SELECT DISTINCT(c.id), CONCAT('<div id=\"',c.id,'-',per.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',per.nombre,' ',per.apellido,'</div>') as name, c.id, (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.cuenta=c.id LIMIT 0,1 ) as phone,(SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.cuenta=c.id LIMIT 1) as email, ind.nombre as industry, CONCAT('<div style=\"text-align:left\">',c.nombre,'</div>') as account
                                                FROM crm_contacto_cuenta cc
                                                INNER JOIN crm_cuenta c on(cc.cuenta=c.id)
                                                INNER JOIN ctl_industria ind on(c.industria=ind.id)
                                                INNER JOIN crm_contacto con on(cc.contacto=con.id)
                                                INNER JOIN ctl_persona per on(con.persona=per.id)
                                                INNER JOIN crm_etiqueta_cuenta ec on(ec.cuenta=c.id)
                                                INNER JOIN crm_etiqueta e on(ec.etiqueta=e.id)
                                                WHERE c.tipo_cuenta=1 AND per.id<>1 AND c.estado=1 AND e.id =".$tagId."
                                                GROUP BY 1
                                                HAVING CONCAT(name,' ',phone,' ',email,' ',industry,' ',account) LIKE upper('%".$busqueda['value']."%')  
                                                ORDER BY ". $orderByText." ".$orderDir." LIMIT " . $start . "," . $longitud;
                                    $stmt = $em->getConnection()->prepare($sql);
                                    $stmt->execute();
                                    $row['data'] = $stmt->fetchAll();
                    }   
                }
                else{
                    if ($tagId==0) {
                        $sql = "SELECT DISTINCT(c.id), CONCAT('<div id=\"',c.id,'-',per.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',per.nombre,' ',per.apellido,'</div>') as name, c.id, (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.cuenta=c.id LIMIT 0,1 ) as phone,(SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.cuenta=c.id LIMIT 1) as email, ind.nombre as industry, CONCAT('<div style=\"text-align:left\">',c.nombre,'</div>') as account
                                        FROM crm_contacto_cuenta cc
                                        INNER JOIN crm_cuenta c on(cc.cuenta=c.id)
                                        INNER JOIN ctl_industria ind on(c.industria=ind.id)
                                        INNER JOIN crm_contacto con on(cc.contacto=con.id)
                                        INNER JOIN ctl_persona per on(con.persona=per.id)
                                        WHERE per.id<>1 AND c.estado=1 AND c.tipo_cuenta=1
                                        GROUP BY 1
                                        ORDER BY ". $orderByText." ".$orderDir;
                    }
                    else{
                        $sql = "SELECT DISTINCT(c.id), CONCAT('<div id=\"',c.id,'-',per.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',per.nombre,' ',per.apellido,'</div>') as name, c.id, (SELECT CONCAT('<div style=\"text-align:left\">',num_telefonico,'</div>') FROM ctl_telefono tel WHERE tel.cuenta=c.id LIMIT 0,1 ) as phone,(SELECT CONCAT('<div style=\"text-align:left\">', corr.email,'</div>') FROM ctl_correo corr WHERE corr.cuenta=c.id LIMIT 1) as email, ind.nombre as industry, CONCAT('<div style=\"text-align:left\">',c.nombre,'</div>') as account
                                        FROM crm_contacto_cuenta cc
                                        INNER JOIN crm_cuenta c on(cc.cuenta=c.id)
                                        INNER JOIN ctl_industria ind on(c.industria=ind.id)
                                        INNER JOIN crm_contacto con on(cc.contacto=con.id)
                                        INNER JOIN ctl_persona per on(con.persona=per.id)
                                        INNER JOIN crm_etiqueta_cuenta ec on(ec.cuenta=c.id)
                                        INNER JOIN crm_etiqueta e on(ec.etiqueta=e.id)
                                        WHERE per.id<>1 AND c.estado=1 AND c.tipo_cuenta=1 AND e.id =".$tagId."
                                        GROUP BY 1
                                        ORDER BY ". $orderByText." ".$orderDir;
                    }
                            $stmt = $em->getConnection()->prepare($sql);
                            $stmt->execute();
                            $row['data'] = $stmt->fetchAll();
                }
                return new Response(json_encode($row));
            } catch (\Exception $e) {  
                // var_dump($e);
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
                $addressArray = $_POST['address'];//direccion persona
                $addressCityArray = $_POST['addressCity'];//city
                $addressDepartamentoArray = $_POST['addressDepartamento'];//state
                $zipCodeArray = $_POST['zipcode'];//zipcode

                //Busqueda objetos a partir de ids
                $industriaObj = $em->getRepository('ERPCRMBundle:CtlIndustria')->find($industriaId);
                $tipoEntidadObj = $em->getRepository('ERPCRMBundle:CtlTipoEntidad')->find($tipoEntidadId);
                $tratamientoProtocolarioObj = $em->getRepository('ERPCRMBundle:CtlTratamientoProtocolario')->find($tratamientoProtocolarioId);
                $crmTipoCuentaObj = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->find(1); //Proveedor

                
                // contactos
                $contactos = $_POST['contactos'];
                
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
                    //$ctlTipoTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->find($phoneTypeArray[0]);//Para definir la variable $ctlTipoTelefonoObj
                    foreach ($phoneArray as $key => $phone) {
                                          
                        $ctlTelefonoObj = new CtlTelefono();
                        $ctlTelefonoObj->setCuenta($crmCuentaObj);
                        
                        $ctlTipoTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->find($phoneTypeArray[$key]);
                        $ctlTelefonoObj->setTipoTelefono($ctlTipoTelefonoObj);
                        //var_dump($key);
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
                    // var_dump(count($addressArray));
                    // var_dump($addressLenght);
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
                    //$crmContacto = $em->getRepository('ERPCRMBundle:CrmContacto')->find($contactos[0]);//Para definir la variable
                    foreach ($contactos as $key => $contact) {
                        if($contact!=0){
                            $crmContactoCuenta = new CrmContactoCuenta();
                            $crmContactoCuenta->setCuenta($crmCuentaObj);
                            //var_dump($key);
                            //if ($key<$contactsLenght && $key!=0) {
                                //if ($contact[$key]==$contact[$key-1]) {
                                    //No buscar en la base contacto
                                    //$crmContactoCuenta->setContacto($crmContacto);
                                //} else {
                                    //Buscar en la base el tipo de telefono
                            $crmContacto = $em->getRepository('ERPCRMBundle:CrmContacto')->find($contactos[$key]);//Para definir la variable
                            $crmContactoCuenta->setContacto($crmContacto);
                            $crmContactoCuenta->setTitular(0);
                                    //var_dump('buscar base tipo telefono');
                                //}
                            //} else {
                                    //Buscar en la base el tipo de telefono, primera iteracion debe buscar el tipo de telefono
                                    //$ctlTipoTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->find($phoneTypeArray[$key]);
                                    //$crmContactoCuenta->setContacto($crmContacto);
                                    //var_dump('no buscar base tipo telefono');
                            //}
                            $em->persist($crmContactoCuenta);
                            $em->flush();
                        }
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
                        
                        
                        //Eliminar contactos
                        $crmContactosCuentaArrayObj = $em->getRepository('ERPCRMBundle:CrmContactoCuenta')->findBy(array('cuenta'=>$idCuenta));
                        foreach ($crmContactosCuentaArrayObj as $key => $value) {
                            if($value->getContacto()->getPersona()->getId()!=$idPersona){
                                $em->remove($value);
                                $em->flush();
                            }
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
                        
                        $ctlTipoTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->find($phoneTypeArray[$key]);
                        $ctlTelefonoObj->setTipoTelefono($ctlTipoTelefonoObj);
                        // var_dump("key".$key);
                        // var_dump("\nphone length".$phoneLenght);
                        // var_dump("\n expresion".($key<$phoneLenght && $key!=0));
                        /*if ($key<$phoneLenght && $key!=0) {
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
                    $addressLenght=count($addressArray);//Cantidad de direccion ingresados, menos 1 para index de array
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
                    foreach ($contactos as $key => $contact) {
                        if($contact!=0){
                            $crmContactoCuenta = new CrmContactoCuenta();
                            $crmContactoCuenta->setCuenta($crmCuentaObj);
                            $crmContacto = $em->getRepository('ERPCRMBundle:CrmContacto')->find($contactos[$key]);//Para definir la variable
                            $crmContactoCuenta->setContacto($crmContacto);
                            if($idPersona==$crmContacto->getPersona()->getId()){
                                $crmContactoCuenta->setTitular(1);
                            }
                            else{
                                $crmContactoCuenta->setTitular(0);
                            }
                            $em->persist($crmContactoCuenta);
                            $em->flush();
                        }
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
     * Retrieve providers
     *
     * @Route("/providers/retrieve", name="admin_provider_retrieve_ajax",  options={"expose"=true}))
     * @Method("GET")
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
            $crmContactoCuentaObj = $em->getRepository('ERPCRMBundle:CrmContactoCuenta')->findBy(array('cuenta'=>$crmCuentaObj->getId()));
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
//                        array_push($dirArray, $value->getDireccion());
//                        array_push($cityArray, $value->getCiudad()->getId());
//                        array_push($stateArray, $value->getCiudad()->getEstado()->getId());
                    }
                    // $data['addressArray']=$ctlDireccionObj[0];
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
                
                
                if(count($crmContactoCuentaObj)!=0){
                    // $data['src']=$crmFotoObj[0]->getSrc();
                    $conNombreArray=array();
                    $conIdArray=array();
                    $data['idContactos']=array();
                    $data['nombreContactos']=array();
                    $data['telefonoContactos']=array();
                    $data['correoContactos']=array();
                    foreach ($crmContactoCuentaObj as $key=>$contactoCuenta){
                        //var_dump($contactoCuenta->getContacto()->getPersona()->getId());
                    //if($key==0){
                        $dataTmp['correo']='';
                        $dataTmp['telefono']='';
                        $crmContactoObj = $contactoCuenta->getContacto();
                        $crmContactoId = $contactoCuenta->getContacto()->getPersona()->getId();
                        $crmCuentaId = $contactoCuenta->getCuenta()->getId();
                        $row=array();
//                        var_dump($crmCuentaId);
//                        var_dump($idPersona);
//                        var_dump($crmContactoId);
                        //die();
                        if(intval($crmContactoId)!=intval($idPersona)){
                        if($crmContactoObj!=null){
                            if($crmContactoObj->getPersona()!=null){
                                
                                $personaContacto= $crmContactoObj->getPersona()->getNombre().' '.$crmContactoObj->getPersona()->getApellido();
                                $ctlCorreo = $em->getRepository('ERPCRMBundle:CtlCorreo')->findBy(array('persona'=>$crmContactoId));
                                $ctlTelefono= $em->getRepository('ERPCRMBundle:CtlTelefono')->findBy(array('persona'=>$crmContactoId));

                                //var_dump(count($ctlCorreo));

                                if(count($ctlCorreo)==0){

                                    $ctlObjTemp = $em->getRepository('ERPCRMBundle:CrmContacto')->findBy(array('persona'=>$crmContactoId));

                                    $ctlObjTemp = $em->getRepository('ERPCRMBundle:CrmContactoCuenta')->findBy(array('titular'=>1,'contacto'=>$ctlObjTemp[0]->getId()));

                                    //var_dump($ctlObjTemp[0]->getCuenta());
                                    //die();
                                    if(count($ctlObjTemp)!=0){
                                        $ctlCorreo = $em->getRepository('ERPCRMBundle:CtlCorreo')->findBy(array('cuenta'=>$ctlObjTemp[0]->getCuenta()->getId()));
                                    }

                                }
                                //var_dump($ctlCorreo);
                                //die;
                                foreach ($ctlCorreo as $key=>$correo){
                                    if($key==0){
                                        $dataTmp['correo'].=$correo->getEmail();
                                    }
                                    else{
                                        $dataTmp['correo'].=', '.$correo->getEmail();
                                    }
                                }
                                if(count($ctlTelefono)==0){
                                    if(count($ctlObjTemp)!=0){
                                        $ctlTelefono= $em->getRepository('ERPCRMBundle:CtlTelefono')->findBy(array('cuenta'=>$ctlObjTemp[0]->getCuenta()->getId()));
                                    }
                                }
                                foreach ($ctlTelefono as $key=>$telefono){
                                    if($key==0){
                                        $dataTmp['telefono'].=$telefono->getNumTelefonico();
                                    }
                                    else{
                                        $dataTmp['telefono'].=', '.$telefono->getNumTelefonico();
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

                            }
                    }
                    }
                }
                else{
                    $data['contactoNombre']='';
                    $data['contactoId']='';
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
                $data['id2']=$ctlPersonaObj->getId();
                
                              
                
                
                $sql = "SELECT ec.id as id, e.nombre as nombre FROM ERPCRMBundle:CrmEtiquetaCuenta ec"
                            ." JOIN ec.etiqueta e "
                            ." JOIN ec.cuenta c "
                            ." WHERE c.id=:idCuenta";
                $tags = $em->createQuery($sql)
                                    ->setParameters(array('idCuenta'=>$idCuenta))
                                    ->getResult();
                
                $data['tags']=$tags;
                
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
     * Convert provider to clients
     *
     * @Route("/provider/convert/client", name="admin_provider_to_cliente_convert_ajax",  options={"expose"=true}))
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
                $em->getConnection()->beginTransaction();
                $tipoCuentaObj = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->find(3);//////Clientes
                foreach ($ids as $key => $id) {
                    $idArray = explode('-', $id);

                    $objectCuenta = $em->getRepository('ERPCRMBundle:CrmCuenta')->find($idArray[0]);
                    $objectPersona = $em->getRepository('ERPCRMBundle:CtlPersona')->find($idArray[1]);
                    $objectTelefonoCuenta = $em->getRepository('ERPCRMBundle:CtlTelefono')->findBy(array('cuenta'=>$objectCuenta->getId()));
                    $objectCorreoCuenta = $em->getRepository('ERPCRMBundle:CtlCorreo')->findBy(array('cuenta'=>$objectCuenta->getId()));
                    $objectDireccionCuenta = $em->getRepository('ERPCRMBundle:CtlDireccion')->findBy(array('cuenta'=>$objectCuenta->getId()));
                    // $cuentaTipoCliente = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->find(3);//Cliente

                    if(count($objectCuenta) && count($objectPersona)){

                        /////Nueva cuenta
                        // $objectCuenta->setTipoCuenta($tipoCuentaObj);
                        $nuevoCliente = new CrmCuenta();

                        $nuevoCliente->setTipoCuenta($tipoCuentaObj);
                        $nuevoCliente->setIndustria($objectCuenta->getIndustria());
                        $nuevoCliente->setClientePotencial($objectCuenta->getClientePotencial());
                        $nuevoCliente->setNivelSatisfaccion($objectCuenta->getNivelSatisfaccion());
                        $nuevoCliente->setTipoEntidad($objectCuenta->getTipoEntidad());
                        $nuevoCliente->setNombre($objectCuenta->getNombre());
                        $nuevoCliente->setDescripcion($objectCuenta->getDescripcion());
                        $nuevoCliente->setFechaRegistro(new \DateTime('now'));
                        $nuevoCliente->setSitioWeb($objectCuenta->getSitioWeb());
                        $nuevoCliente->setEstado($objectCuenta->getEstado());
                        

                        $em->persist($nuevoCliente);
                        $em->flush();

                        /////Nuevo contacto
                        $crmContactoObj = new CrmContacto();
                        $crmContactoObj->setPersona($objectPersona);
                        $crmContactoObj->setEstado(1);
                        $em->persist($crmContactoObj);
                        $em->flush();
                        
                        /////Nuevo contacto cuenta
                        $crmContactoCuentaObj = new CrmContactoCuenta();
                        $crmContactoCuentaObj->setCuenta($nuevoCliente);
                        $crmContactoCuentaObj->setContacto($crmContactoObj);
                        $em->persist($crmContactoCuentaObj);
                        $em->flush();


                        /////Creación de correos
                        foreach ($objectCorreoCuenta as $key => $correo) {
                            $ctlCorreoObj = new CtlCorreo();
                            $ctlCorreoObj->setEmpresa($correo->getEmpresa());
                            $ctlCorreoObj->setPersona($correo->getPersona());
                            $ctlCorreoObj->setCuenta($nuevoCliente);
                            $ctlCorreoObj->setEmail($correo->getEmail());
                            $ctlCorreoObj->setEstado($correo->getEstado());
                            //Persist ctlCorreo
                            $em->persist($ctlCorreoObj);
                            $em->flush();
                        }
                        /////Creación de telefonos
                        foreach ($objectTelefonoCuenta as $key => $phone) {
                                // var_dump('for');
                                $ctlTelefonoObj = new CtlTelefono();
                                $ctlTelefonoObj->setCuenta($nuevoCliente);
                                
                                $ctlTelefonoObj->setTipoTelefono($phone->getTipoTelefono());
                                
                                $ctlTelefonoObj->setNumTelefonico($phone->getNumTelefonico());
                                $ctlTelefonoObj->setExtension($phone->getExtension());
                                $ctlTelefonoObj->setPersona($phone->getPersona());
                                $ctlTelefonoObj->setEmpresa($phone->getEmpresa());
                                $ctlTelefonoObj->setSucursal($phone->getSucursal());
                                
                                //Persist ctlTelefono
                                $em->persist($ctlTelefonoObj);
                                $em->flush();
                        }
                        foreach ($objectDireccionCuenta as $key => $direccion) {
                                              
                            $ctlDireccionObj = new CtlDireccion();
                            $ctlDireccionObj->setCuenta($nuevoCliente);
                            $ctlDireccionObj->setCiudad($direccion->getCiudad());
                            
                            
                            $ctlDireccionObj->setDireccion($direccion->getDireccion());
                            $ctlDireccionObj->setPersona($direccion->getPersona());
                            $ctlDireccionObj->setEmpresa($direccion->getEmpresa());
                            
                            $ctlDireccionObj->setLatitud($direccion->getLatitud());
                            $ctlDireccionObj->setLongitud($direccion->getLongitud());
                            $ctlDireccionObj->setCity($direccion->getCity());
                            $ctlDireccionObj->setState($direccion->getState());
                            $ctlDireccionObj->setZipCode($direccion->getZipCode());
                            $ctlDireccionObj->setEstado($direccion->getEstado());
                            //Persist ctlDireccion
                            $em->persist($ctlDireccionObj);
                            $em->flush();
                            
                        }                



                        $em->getConnection()->commit();
                        $em->close();



                        $serverConvert = $this->getParameter('app.serverMsgConverted');
                        $data['msg']=$serverConvert;
                    }
                    else{
                        // echo "sdcasdc";
                        $data['error']="Error";
                        $em->getConnection()->rollback();
                        $em->close();
                    }
                }
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







    /**
     * Add comment providers
     *
     * @Route("/providers/comment/add", name="admin_providers_comment_add_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function commentajaxAction(Request $request)
    {
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        if($isAjax){
            try {

                $crmComentarioCuenta = new CrmComentarioCuenta();
                $idCuenta=$request->get("param1");
                $comment=$request->get("param2");
                $fechaRegistro = new \DateTime('now');
                $response = new JsonResponse();
                $usuarioObj = $this->get('security.token_storage')->getToken()->getUser();

                $em = $this->getDoctrine()->getManager();

                $cuentaObj = $em->getRepository('ERPCRMBundle:CrmCuenta')->find($idCuenta);
                // $usuarioObj = $em->getRepository('ERPCRMBundle:CtlUsuario')->find($idCuenta);


                $crmComentarioCuenta->setComentario($comment);
                $crmComentarioCuenta->setFechaRegistro($fechaRegistro);
                $crmComentarioCuenta->setCuenta($cuentaObj);
                $crmComentarioCuenta->setUsuario($usuarioObj);
                $crmComentarioCuenta->setTipoComentario(1);//Comentario

                $em->persist($crmComentarioCuenta);
                $em->flush();

                $sql="SELECT COUNT(*) as total FROM seguimiento where cuenta=".$idCuenta;
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();
                $reg= $stmt->fetchAll();

                // var_dump($reg);
                // die();
                $data['usuario']=$usuarioObj->getPersona()->getNombre().' '.$usuarioObj->getPersona()->getApellido();
                $data['comentario']=$comment;
                $data['tipocomentario']=$crmComentarioCuenta->getTipoComentario();
                $data['fecha']=$fechaRegistro->format('Y-m-d H:i');
                $data['numeroItems']=$reg[0]['total'];
                //$data['usuario']=$usuario->getPersona()->getNombre().' '.$usuario->getPersona()->getApellido();
                
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












    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/*/**/*/*/*/*/*///-*/-/-*/-*/*-/*/-*/-*/-*///////
    //Gestion de accounts
    /**
     * Save account
     *
     * @Route("/account/save", name="admin_account_save_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function saveaccountajaxAction(Request $request)
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
                $tipoEntidadId = null;//tipo entidad, Id
                $nombreCuenta = $_POST['compania'];//cliente potencial
                $descripcionCuenta = '';//descripcion
                $fechaRegistro = new \DateTime('now');//descripcion
                $sitioWeb = $_POST['website'];//descripcion
                $tipoAccount = $_POST['tipoAccount'];//nombre del tipo de cuenta
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

                //Busqueda objetos a partir de ids
                $industriaObj = $em->getRepository('ERPCRMBundle:CtlIndustria')->find($industriaId);
                // $tipoEntidadObj = $em->getRepository('ERPCRMBundle:CtlTipoEntidad')->find($tipoEntidadId);
                $tipoEntidadObj = null;
                $tratamientoProtocolarioObj = $em->getRepository('ERPCRMBundle:CtlTratamientoProtocolario')->find($tratamientoProtocolarioId);
                $crmTipoCuentaObj = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->findBy(array('nombre'=>$tipoAccount)); //Segun el parametro que se le envie
                
                // contactos
                $contactos = $_POST['contactos'];
                if($idCuenta=='' && $idPersona==''){

                    //Tabla crmCuenta, ids
                    $crmCuentaObj = new CrmCuenta();
                    
                    $crmCuentaObj->setTipoCuenta($crmTipoCuentaObj[0]);
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
                    $crmContactoCuentaObj->setTitular(1);
                    
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
                        $ctlTipoTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->find($phoneTypeArray[$key]);
                        $ctlTelefonoObj->setTipoTelefono($ctlTipoTelefonoObj);
                        //var_dump($key);
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
                    // var_dump(count($addressArray));
                    // var_dump($addressLenght);
                    foreach ($addressArray as $key => $val) {
                        
                        $ctlDireccionObj = new CtlDireccion();
                        $ctlDireccionObj->setCuenta($crmCuentaObj);
                        $ctlDireccionObj->setDireccion($addressArray[$key]);
                        $ctlDireccionObj->setZipCode($zipCodeArray[$key]);
                        $ctlDireccionObj->setCity($addressCityArray[$key]);
                        $ctlDireccionObj->setState($addressDepartamentoArray[$key]);

                        /*$ctlDireccionObj = new CtlDireccion();
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
                        }*/
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
                    
                    
                    
                    //////Contactos
                    $contactsLenght=count($contactos)-1;//Cantidad de telefono ingresados, menos 1 para index de array
                    $crmContacto = $em->getRepository('ERPCRMBundle:CrmContacto')->find($contactos[0]);//Para definir la variable
                    foreach ($contactos as $key => $contact) {
                        if($contact!=0){
                            $crmContactoCuenta = new CrmContactoCuenta();
                            $crmContactoCuenta->setCuenta($crmCuentaObj);
                            //var_dump($key);
                            //if ($key<$contactsLenght && $key!=0) {
                                //if ($contact[$key]==$contact[$key-1]) {
                                    //No buscar en la base contacto
                                    //$crmContactoCuenta->setContacto($crmContacto);
                                //} else {
                                    //Buscar en la base el tipo de telefono
                            $crmContacto = $em->getRepository('ERPCRMBundle:CrmContacto')->find($contactos[$key]);//Para definir la variable
                            $crmContactoCuenta->setContacto($crmContacto);
                            $crmContactoCuenta->setTitular(0);
                                    //var_dump('buscar base tipo telefono');
                                //}
                            //} else {
                                    //Buscar en la base el tipo de telefono, primera iteracion debe buscar el tipo de telefono
                                    //$ctlTipoTelefonoObj = $em->getRepository('ERPCRMBundle:CtlTipoTelefono')->find($phoneTypeArray[$key]);
                                    //$crmContactoCuenta->setContacto($crmContacto);
                                    //var_dump('no buscar base tipo telefono');
                            //}
                            $em->persist($crmContactoCuenta);
                            $em->flush();
                        }
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
                        $crmCuentaObj->setTipoCuenta($crmTipoCuentaObj[0]);
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
                        
                        //Eliminar contactos
                        $crmContactosCuentaArrayObj = $em->getRepository('ERPCRMBundle:CrmContactoCuenta')->findBy(array('cuenta'=>$idCuenta));
                        foreach ($crmContactosCuentaArrayObj as $key => $value) {
                            if($value->getContacto()->getPersona()->getId()!=$idPersona){
                                $em->remove($value);
                                $em->flush();
                            }
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
                    foreach ($contactos as $key => $contact) {
                        if($contact!=0){
                            $crmContactoCuenta = new CrmContactoCuenta();
                            $crmContactoCuenta->setCuenta($crmCuentaObj);
                            $crmContacto = $em->getRepository('ERPCRMBundle:CrmContacto')->find($contactos[$key]);//Para definir la variable
                            $crmContactoCuenta->setContacto($crmContacto);
                            if($idPersona==$crmContacto->getPersona()->getId()){
                                $crmContactoCuenta->setTitular(1);
                            }
                            else{
                                $crmContactoCuenta->setTitular(0);
                            }
                            $em->persist($crmContactoCuenta);
                            $em->flush();
                        }
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
     * Retrieve accounts
     *
     * @Route("/accounts/retrieve", name="admin_accounts_retrieve_ajax",  options={"expose"=true}))
     * @Method("POST")
     */
    public function retrieveasccountsajaxAction(Request $request)
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
            
            $crmContactoCuentaObj = $em->getRepository('ERPCRMBundle:CrmContactoCuenta')->findBy(array('cuenta'=>$crmCuentaObj->getId()));
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
//                        array_push($dirArray, $value->getDireccion());
//                        array_push($cityArray, $value->getCiudad()->getId());
//                        array_push($stateArray, $value->getCiudad()->getEstado()->getId());
                    }
                    // $data['addressArray']=$ctlDireccionObj[0];
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
                
                
                
                if(count($crmContactoCuentaObj)!=0){
                    // $data['src']=$crmFotoObj[0]->getSrc();
                    $conNombreArray=array();
                    $conIdArray=array();
                    $data['idContactos']=array();
                    $data['nombreContactos']=array();
                    $data['telefonoContactos']=array();
                    $data['correoContactos']=array();
                    foreach ($crmContactoCuentaObj as $key=>$contactoCuenta){
                        //var_dump($contactoCuenta->getContacto()->getPersona()->getId());
                    //if($key==0){
                        $dataTmp['correo']='';
                        $dataTmp['telefono']='';
                        $crmContactoObj = $contactoCuenta->getContacto();
                        $crmContactoId = $contactoCuenta->getContacto()->getPersona()->getId();
                        $crmCuentaId = $contactoCuenta->getCuenta()->getId();
                        $row=array();
//                        var_dump($crmCuentaId);
//                        var_dump($idPersona);
//                        var_dump($crmContactoId);
                        //die();
                        if(intval($crmContactoId)!=intval($idPersona)){
                        if($crmContactoObj!=null){
                            if($crmContactoObj->getPersona()!=null){
                                
                                $personaContacto= $crmContactoObj->getPersona()->getNombre().' '.$crmContactoObj->getPersona()->getApellido();
                                $ctlCorreo = $em->getRepository('ERPCRMBundle:CtlCorreo')->findBy(array('persona'=>$crmContactoId));
                                $ctlTelefono= $em->getRepository('ERPCRMBundle:CtlTelefono')->findBy(array('persona'=>$crmContactoId));

                                //var_dump(count($ctlCorreo));

                                if(count($ctlCorreo)==0){

                                    $ctlObjTemp = $em->getRepository('ERPCRMBundle:CrmContacto')->findBy(array('persona'=>$crmContactoId));

                                    $ctlObjTemp = $em->getRepository('ERPCRMBundle:CrmContactoCuenta')->findBy(array('titular'=>1,'contacto'=>$ctlObjTemp[0]->getId()));

                                    //var_dump($ctlObjTemp[0]->getCuenta());
                                    //die();
                                    if(count($ctlObjTemp)!=0){
                                        $ctlCorreo = $em->getRepository('ERPCRMBundle:CtlCorreo')->findBy(array('cuenta'=>$ctlObjTemp[0]->getCuenta()->getId()));
                                    }

                                }
                                //var_dump($ctlCorreo);
                                //die;
                                foreach ($ctlCorreo as $key=>$correo){
                                    if($key==0){
                                        $dataTmp['correo'].=$correo->getEmail();
                                    }
                                    else{
                                        $dataTmp['correo'].=', '.$correo->getEmail();
                                    }
                                }
                                if(count($ctlTelefono)==0){
                                    if(count($ctlObjTemp)!=0){
                                        $ctlTelefono= $em->getRepository('ERPCRMBundle:CtlTelefono')->findBy(array('cuenta'=>$ctlObjTemp[0]->getCuenta()->getId()));
                                    }
                                }
                                foreach ($ctlTelefono as $key=>$telefono){
                                    if($key==0){
                                        $dataTmp['telefono'].=$telefono->getNumTelefonico();
                                    }
                                    else{
                                        $dataTmp['telefono'].=', '.$telefono->getNumTelefonico();
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

                            }
                    }
                    }
                }
                else{
                    $data['contactoNombre']='';
                    $data['contactoId']='';
                }
                
                

                // $data['addressArray']=$ctlDireccionObj[0];
                // $data['phoneArray']=$ctlTelefonoObj[0];
                // $data['emailArray']=$ctlCorreoObj[0];
                // $data['src']=$crmFotoObj[0]->getSrc();
                $data['titulo']=$ctlPersonaObj->getTratamientoProtocolario()->getId();
                $data['pesona']=$ctlPersonaObj->getId();
                // $data['entidad']=$crmCuentaObj->getTipoEntidad()->getId();
                $data['industria']=$crmCuentaObj->getIndustria()->getId();
                $data['website']=$crmCuentaObj->getSitioWeb();
                
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
    * Ajax utilizado para buscar informacion de una consulta de estetica
    *  
    * @Route("/search-account-select/data/", name="busqueda_cuenta_select_info",  options={"expose"=true}))
    */
    public function busquedaAccountAction(Request $request)
    {
        $busqueda = $request->query->get('q');
        $page = $request->query->get('page');
        
        //var_dump($page);
        
        $em = $this->getDoctrine()->getEntityManager();
        $dql = "SELECT cu.nombre,cu.id "
                        . "FROM ERPCRMBundle:CrmCuenta cu "
                        . "WHERE upper(cu.nombre) LIKE upper(:busqueda) AND cu.estado=1 "
                        . "ORDER BY cu.nombre ASC ";
        
        $row['data'] = $em->createQuery($dql)
                ->setParameters(array('busqueda'=>"%".$busqueda."%"))
                ->setMaxResults( 10 )
                ->getResult();
//        var_dump($row['data']);
        return new Response(json_encode($row));
    }




    /**
    * Ajax utilizado para buscar informacion de los ultimos 6 seguimientos de una cuenta
    *  
    * @Route("/seguimiento/data/account", name="busqueda_cuenta_seguimiento_info",  options={"expose"=true}))
    */
    public function busquedaSeguimientoAction(Request $request)
    {
        try {
            $response = new JsonResponse();
            $idCuenta=$request->get("param1"); /////Id de la cuenta que se esta viendo
            // $longitud=$request->get("param2"); /////Numero de items a recuperar por click
            $longitud = $this->getParameter('app.serverSeguimientoLongitud'); /////Numero de items a recuperar por click y al inicio
            $files = $this->getParameter('app.serverFileAttached'); /////
            $numPedidos=$request->get("param2"); /////Numero de veces solicitado, para el paginado
            // sleep ( 5 );
            $inicio=($longitud*$numPedidos)-$longitud;
            $em = $this->getDoctrine()->getEntityManager();
            $sql="SELECT * FROM seguimiento where cuenta=".$idCuenta. " ORDER BY fecha_registro DESC LIMIT ".$inicio.",".$longitud;
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
    
    


     /**
    * Ajax utilizado para buscar informacion de los ultimos 6 seguimientos de una cuenta
    *  
    * @Route("/seguimiento/data/account/comet", name="busqueda_cuenta_seguimiento_comet_info",  options={"expose"=true}))
    */
    public function busquedaSeguimientoCometAction(Request $request)
    {
        try {
            $response = new JsonResponse();
            $idCuenta=$request->get("param1"); /////Id de la cuenta que se esta viendo
            // $longitud=$request->get("param2"); /////Numero de items a recuperar por click
            $primeraFecha=$request->get("param2"); /////Numero de veces solicitado, para el paginado
            //sleep ( 15 );
            
            $em = $this->getDoctrine()->getEntityManager();
            $sql="SELECT * FROM seguimiento where cuenta=".$idCuenta. " AND fecha_registro>'".$primeraFecha."' ORDER BY fecha_registro DESC";
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $data['data']= $stmt->fetchAll();
            // return new Response(json_encode($data));
        } catch (\Exception $e) {
            $data['error']=$e->getMessage();
        }
        $response->setData($data);
        return $response;
    }


}
