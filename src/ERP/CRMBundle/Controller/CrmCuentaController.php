<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CrmCuenta;
use ERP\CRMBundle\Form\CrmCuentaType;

/**
 * CrmCuenta controller.
 *
 * @Route("/admin/accounts")
 */
class CrmCuentaController extends Controller
{
    /**
     * Lists all CrmCuenta entities.
     *
     * @Route("/", name="admin_accounts_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $crmCuentas = $em->getRepository('ERPCRMBundle:CrmCuenta')->findAll();

        return $this->render('crmcuenta/index.html.twig', array(
            'crmCuentas' => $crmCuentas,
        ));
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
    public function datacampaignsAction(Request $request)
    {
            try {
                $start = $request->query->get('start');
                $draw = $request->query->get('draw');
                $longitud = $request->query->get('length');
                $busqueda = $request->query->get('search');
                
                $em = $this->getDoctrine()->getEntityManager();
                //$rowsTotal = $em->getRepository('ERPCRMBundle:CrmCuenta')->findAll();
                

                $sql = "SELECT obj.id as id FROM ERPCRMBundle:CrmCuenta obj "
                            ."JOIN obj.tipoCuenta tc"
                            . " WHERE tc.id=1";
                $rowsTotal = $em->createQuery($sql)
                            ->getResult();
                //var_dump($rowsTotal);
                //die();

                $row['draw']=$draw++;  
                $row['recordsTotal'] = count($rowsTotal);
                $row['recordsFiltered']= count($rowsTotal);
                $row['data']= array();

                $arrayFiltro = explode(' ',$busqueda['value']);
                
                $orderParam = $request->query->get('order');
                $orderBy = $orderParam[0]['column'];
                $orderDir = $orderParam[0]['dir'];
        //var_dump($orderDir);
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
                            $sql = "SELECT CONCAT('<div id=\"',obj.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',obj.nombre,'</div>') as name,
                                CASE
                                WHEN obj.estado =1 THEN 'Active'
                                ELSE 'Inactive'
                                END AS state FROM ERPCRMBundle:CtlNivelSatisfaccion obj "
                                        . "WHERE obj.estado=1 AND upper(obj.nombre) LIKE upper(:busqueda) "
                                        . "AND obj.estado=1 ORDER BY ".$orderByText." ".$orderDir;
                            $row['data'] = $em->createQuery($sql)
                                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                                    ->getResult();                    
                            $row['recordsFiltered']= count($row['data']);
                            $sql = "SELECT CONCAT('<div id=\"',obj.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',obj.nombre,'</div>') as name,
                                CASE
                                WHEN obj.estado =1 THEN 'Active'
                                ELSE 'Inactive'
                                END AS state FROM ERPCRMBundle:CtlNivelSatisfaccion obj "
                                                . "WHERE obj.estado=1 AND upper(obj.nombre) LIKE upper(:busqueda) "
                                                . "AND pac.estado=1 ORDER BY ".$orderByText." ".$orderDir;
                            $row['data'] = $em->createQuery($sql)
                                    ->setParameters(array('busqueda'=>"%".$busqueda['value']."%"))
                                    ->setFirstResult($start)
                                    ->setMaxResults($longitud)
                                    ->getResult();
                }
                else{
                    $sql = "SELECT CONCAT('<div id=\"',obj.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',obj.nombre,'</div>') as name, obj.email as email, obj.industria as industry
                                FROM ERPCRMBundle:CrmCuenta obj "
                                ."JOIN obj.contactoCuenta cc "
                                ."JOIN cc.contacto c "
                                ."JOIN c.persona per "
                                        . " WHERE obj.estado=1 ORDER BY ".$orderByText." ".$orderDir;
                    $row['data'] = $em->createQuery($sql)
                            ->setFirstResult($start)
                            ->setMaxResults($longitud)
                            ->getResult();
                            
                }
                //var_dump($row);
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





    //////Fin de proveedores






}
