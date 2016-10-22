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
        $em = $this->getDoctrine()->getManager();

        //Fuentes
        $fuentes = $em->getRepository('ERPCRMBundle:CtlFuente')->findBy(array('estado'=>1));
        
        //Tipos de cuenta
        $tiposCuenta = $em->getRepository('ERPCRMBundle:CrmTipoCuenta')->findBy(array('estado'=>1));
        
        //Etapas de venta
        $etapasVenta = $em->getRepository('ERPCRMBundle:CtlEtapaVenta')->findBy(array('estado'=>1));
        
        //Campañas
        $campanias = $em->getRepository('ERPCRMBundle:CrmCampania')->findBy(array('estado'=>1));
        
        //Persona-usuarios
        $personas = $em->getRepository('ERPCRMBundle:CtlUsuario')->findAll();

        return $this->render('crm_oportunidad/index.html.twig', array(
            'tiposCuenta'=>$tiposCuenta,
            'fuentes'=>$fuentes,
            'etapasVenta'=>$etapasVenta,
            'campanias'=>$campanias,
            'personas'=>$personas,
            'menuOportunidadesA' => true,
        ));
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
        
        $em = $this->getDoctrine()->getEntityManager();
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
                        $arrayAux=array();    
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
