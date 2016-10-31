<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CtlIndustria;

/**
 * CtlIndustria controller.
 *
 * @Route("/admin/industry")
 */
class CtlIndustriaController extends Controller
{
    /**
     * Lists all CtlIndustria entities.
     *
     * @Route("/", name="admin_ctlindustria_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        
        return $this->render('ctlindustria/index.html.twig', array(
            'menuIndustriaA' => true,
        ));
    }

    /**
     * Creates a new CtlIndustria entity.
     *
     * @Route("/new", name="admin_ctlindustria_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ctlIndustrium = new CtlIndustria();
        $form = $this->createForm('ERP\CRMBundle\Form\CtlIndustriaType', $ctlIndustrium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlIndustrium);
            $em->flush();

            return $this->redirectToRoute('admin_ctlindustria_show', array('id' => $ctlIndustrium->getId()));
        }

        return $this->render('ctlindustria/new.html.twig', array(
            'ctlIndustrium' => $ctlIndustrium,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CtlIndustria entity.
     *
     * @Route("/{id}", name="admin_ctlindustria_show")
     * @Method("GET")
     */
    public function showAction(CtlIndustria $ctlIndustrium)
    {
        $deleteForm = $this->createDeleteForm($ctlIndustrium);

        return $this->render('ctlindustria/show.html.twig', array(
            'ctlIndustrium' => $ctlIndustrium,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CtlIndustria entity.
     *
     * @Route("/{id}/edit", name="admin_ctlindustria_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CtlIndustria $ctlIndustrium)
    {
        $deleteForm = $this->createDeleteForm($ctlIndustrium);
        $editForm = $this->createForm('ERP\CRMBundle\Form\CtlIndustriaType', $ctlIndustrium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlIndustrium);
            $em->flush();

            return $this->redirectToRoute('admin_ctlindustria_edit', array('id' => $ctlIndustrium->getId()));
        }

        return $this->render('ctlindustria/edit.html.twig', array(
            'ctlIndustrium' => $ctlIndustrium,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CtlIndustria entity.
     *
     * @Route("/{id}", name="admin_ctlindustria_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CtlIndustria $ctlIndustrium)
    {
        $form = $this->createDeleteForm($ctlIndustrium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ctlIndustrium);
            $em->flush();
        }

        return $this->redirectToRoute('admin_ctlindustria_index');
    }

    /**
     * Creates a form to delete a CtlIndustria entity.
     *
     * @param CtlIndustria $ctlIndustrium The CtlIndustria entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CtlIndustria $ctlIndustrium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_ctlindustria_delete', array('id' => $ctlIndustrium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * 
     *
     * @Route("/industry/data/as", name="admin_tipo_industria_data")
     */
    public function datapacienteAction(Request $request) {

        $start = $request->query->get('start');
        $draw = $request->query->get('draw');
        $longitud = $request->query->get('length');
        $busqueda = $request->query->get('search');        
        $em = $this->getDoctrine()->getEntityManager();
        $rowsTotal = $em->getRepository('ERPCRMBundle:CtlIndustria')->findBy(array('estado'=>1));

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
//            case 1:
//                $orderByText = "probability";
//                break;
//            case 2:
//                $orderByText = "state";
//                break;
        }

        $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
        if ($busqueda['value'] != '') {


            $dql = "SELECT CONCAT('<div id=\"',pac.id,'\" style=\"text-align:center;\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div id=\"',pac.id,'\" style=\"text-align:left;\">',pac.nombre,'</div>') as name, '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>' as actions FROM ERPCRMBundle:CtlIndustria pac "
                    . "WHERE pac.estado=1 AND CONCAT(upper(pac.nombre),' ') LIKE upper(:busqueda) "
                    . "ORDER BY " . $orderByText . " " . $orderDir;
            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda' => "%" . $busqueda['value'] . "%"))
                    ->getResult();

            $row['recordsFiltered'] = count($row['data']);

            $dql = "SELECT CONCAT('<div id=\"',pac.id,'\" style=\"text-align:center;\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div id=\"',pac.id,'\" style=\"text-align:left;\">',pac.nombre,'</div>') as name,'<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>' as actions FROM ERPCRMBundle:CtlIndustria pac "
                    . "WHERE pac.estado=1 AND CONCAT(upper(pac.nombre),' ') LIKE upper(:busqueda) "
                    . "ORDER BY " . $orderByText . " " . $orderDir;
            $row['data'] = $em->createQuery($dql)
                    ->setParameters(array('busqueda' => "%" . $busqueda['value'] . "%"))
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();
        } else {
            $dql = "SELECT CONCAT('<div id=\"',pac.id,'\" style=\"text-align:center;\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div id=\"',pac.id,'\" style=\"text-align:left;\">',pac.nombre,'</div>') as name, '<a ><i style=\"cursor:pointer;\"  class=\"infoPaciente fa fa-info-circle\"></i></a>' as actions, pac.estado as estado FROM ERPCRMBundle:CtlIndustria pac "
                    . "WHERE pac.estado = 1 "
                    . "ORDER BY " . $orderByText . " " . $orderDir;
            $row['data'] = $em->createQuery($dql)
                    ->setFirstResult($start)
                    ->setMaxResults($longitud)
                    ->getResult();
        }

        return new Response(json_encode($row));
    }
    
    /**
     * Insert data CtlIndustria.
     *
     * @Route("/insert", name="admin_tipoindustry_insert" , options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function insertindustriaAction(Request $request) {
        
        $crmCtlIndustry = new CtlIndustria();            
        $isAjax = $this->get('Request')->isXMLhttpRequest();
        $idindustry = $request->get('id');              
        
        if($idindustry == -1){
                    
            if($isAjax){
                $em = $this->getDoctrine()->getManager();
                $nameindustry = $request->get('name');              
                $date = date('Y-m-d');  
                $crmCtlIndustry->setNombre($nameindustry);
                $crmCtlIndustry->setEstado(1);
                $em = $this->getDoctrine()->getManager();
                $em->persist($crmCtlIndustry);
                $em->flush();
                $data['success'] = "Type of industry created";   
            }else{
                $data['failed'] = "Type of industry failed";   
            }
        }else{
            if($isAjax){
                $em = $this->getDoctrine()->getManager();
                $nameindustry = $request->get('name');                                              
                $em = $this->getDoctrine()->getManager();
                $crmCtlIndustry = $em->getRepository('ERPCRMBundle:CtlIndustria')->find($idindustry);
                $crmCtlIndustry->setNombre($nameindustry);                                
                $em->merge($crmCtlIndustry);
                $em->flush();
                $data['success'] = "Type of industry updated";   
            }else{
                $data['failed'] = "Type of industry failed";   
            }
        }
        
        $request = $this->getRequest();           
        return new Response(json_encode($data));
       
    }
    
    /**
     * Read data CtlIndustria.
     *
     * @Route("/read", name="admin_tipoindustry_read" , options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function readindustriaAction(Request $request){
        
        $response = new JsonResponse();
        $crmCtlIndustry = new CtlIndustria();            
        $isAjax = $this->get('Request')->isXMLhttpRequest();

        if($isAjax){            
            $em = $this->getDoctrine()->getManager();
            $id = $request->get('id');                                     
            $crmCtlIndustry = $em->getRepository('ERPCRMBundle:CtlIndustria')->find($id);
            $name = $crmCtlIndustry->getNombre();
            $data['id']= $crmCtlIndustry->getId();
            $data['name'] = $name;            
            $response->setData($data);
            
            return $response;       
        }
               
    }
    
    
    /**
     * Delete data CtlIndustria.
     *
     * @Route("/delete", name="admin_tipoindustry_delete" , options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function updateindustriaAction(Request $request) {

        //$response = new JsonResponse();
        $crmCtlIndustry = new CtlIndustria();
        $isAjax = $this->get('Request')->isXMLhttpRequest();

        if ($isAjax) {
            $em = $this->getDoctrine()->getManager();
            $ids = $request->get('ids');
            
            foreach ($ids as $key => $value) {
                //var_dump($value);
                $crmCtlIndustry = $em->getRepository('ERPCRMBundle:CtlIndustria')->find($value);
                $crmCtlIndustry->setEstado(0);
                $em->merge($crmCtlIndustry);
                $em->flush();
            }
            
            
            /*
            $crmCtlIndustry = $em->getRepository('ERPCRMBundle:CtlIndustria')->find($id);
            $name = $crmCtlIndustry->getNombre();
            $data['id'] = $crmCtlIndustry->getId();
            $data['success'] = "Type of industry updated";
            */
            $data['success'] = "Type of industry deleted";
            //$response->setData($data);
            //return $response;
            return new Response(json_encode($data));
        }
    }

}
