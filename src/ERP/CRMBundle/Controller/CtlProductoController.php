<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ERP\CRMBundle\Entity\CtlProducto;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ctlproducto controller.
 *
 * @Route("admin/ctlproduct")
 */
class CtlProductoController extends Controller
{
    /**
     * Lists all ctlProducto entities.
     *
     * @Route("/", name="admin_ctlproduct_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ctlProductos = $em->getRepository('ERPCRMBundle:CtlProducto')->findAll();

        return $this->render('ctlproducto/index.html.twig', array(
            //'ctlProductos' => $ctlProductos,
            'menuCtlProductoA' => true,
        ));
    }

    /**
     * Creates a new ctlProducto entity.
     *
     * @Route("/new", name="admin_ctlproduct_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ctlProducto = new Ctlproducto();
        $form = $this->createForm('ERP\CRMBundle\Form\CtlProductoType', $ctlProducto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlProducto);
            $em->flush($ctlProducto);

            return $this->redirectToRoute('admin_ctlproduct_show', array('id' => $ctlProducto->getId()));
        }

        return $this->render('ctlproducto/new.html.twig', array(
            'ctlProducto' => $ctlProducto,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ctlProducto entity.
     *
     * @Route("/{id}", name="admin_ctlproduct_show")
     * @Method("GET")
     */
    public function showAction(CtlProducto $ctlProducto)
    {
        $deleteForm = $this->createDeleteForm($ctlProducto);

        return $this->render('ctlproducto/show.html.twig', array(
            'ctlProducto' => $ctlProducto,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ctlProducto entity.
     *
     * @Route("/{id}/edit", name="admin_ctlproduct_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CtlProducto $ctlProducto)
    {
        $deleteForm = $this->createDeleteForm($ctlProducto);
        $editForm = $this->createForm('ERP\CRMBundle\Form\CtlProductoType', $ctlProducto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_ctlproduct_edit', array('id' => $ctlProducto->getId()));
        }

        return $this->render('ctlproducto/edit.html.twig', array(
            'ctlProducto' => $ctlProducto,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ctlProducto entity.
     *
     * @Route("/{id}", name="admin_ctlproduct_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CtlProducto $ctlProducto)
    {
        $form = $this->createDeleteForm($ctlProducto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ctlProducto);
            $em->flush($ctlProducto);
        }

        return $this->redirectToRoute('admin_ctlproduct_index');
    }

    /**
     * Creates a form to delete a ctlProducto entity.
     *
     * @param CtlProducto $ctlProducto The ctlProducto entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CtlProducto $ctlProducto)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_ctlproduct_delete', array('id' => $ctlProducto->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * List contact
     *
     * @Route("/campaigns/data/list", name="admin_contact_data")
    */
    public function datacontactsAction(Request $request) {
        try {
            $start = $request->query->get('start');
            $draw = $request->query->get('draw');
            $longitud = $request->query->get('length');
            $busqueda = $request->query->get('search');

            $em = $this->getDoctrine()->getEntityManager();
            $rowsTotal = $em->getRepository('ERPCRMBundle:CtlProducto')->findBy(array('estado' => 1));
            
            $row['draw'] = $draw++;
            $row['recordsTotal'] = count($rowsTotal);
            $row['recordsFiltered'] = count($rowsTotal);
            $row['data'] = array();

            $arrayFiltro = explode(' ', $busqueda['value']);

            $orderParam = $request->query->get('order');
            $orderBy = $orderParam[0]['column'];
            $orderDir = $orderParam[0]['dir'];
            //var_dump($orderDir);
            $orderByText = "";
            switch (intval($orderBy)) {
                case 1:
                    $orderByText = "name";
                    break;

                case 2:
                    $orderByText = "state";
                    break;
            }
            $busqueda['value'] = str_replace(' ', '%', $busqueda['value']);
            if ($busqueda['value'] != '') {
                $sql = "SELECT CONCAT('<div id=\"',obj.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',obj.nombre,'</div>') as name,
                                CASE
                                WHEN obj.estado =1 THEN 'Active'
                                ELSE 'Inactive'
                                END AS state FROM ERPCRMBundle:CtlProducto obj "
                        . "WHERE obj.estado=1 AND upper(obj.nombre) LIKE upper(:busqueda) "
                        . "AND obj.estado=1 ORDER BY " . $orderByText . " " . $orderDir;
                $row['data'] = $em->createQuery($sql)
                        ->setParameters(array('busqueda' => "%" . $busqueda['value'] . "%"))
                        ->getResult();
                $row['recordsFiltered'] = count($row['data']);
                $sql = "SELECT CONCAT('<div id=\"',obj.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',obj.nombre,'</div>') as name,
                                CASE
                                WHEN obj.estado =1 THEN 'Active'
                                ELSE 'Inactive'
                                END AS state FROM ERPCRMBundle:CtlProducto obj "
                        . "WHERE obj.estado=1 AND upper(obj.nombre) LIKE upper(:busqueda) "
                        . "AND pac.estado=1 ORDER BY " . $orderByText . " " . $orderDir;
                $row['data'] = $em->createQuery($sql)
                        ->setParameters(array('busqueda' => "%" . $busqueda['value'] . "%"))
                        ->setFirstResult($start)
                        ->setMaxResults($longitud)
                        ->getResult();
            } else {
                $sql = "SELECT CONCAT('<div id=\"',obj.id,'\" style=\"text-align:left\"><input style=\"z-index:5;\" class=\"chkItem\" type=\"checkbox\"></div>') as chk, CONCAT('<div style=\"text-align:left\">',obj.nombre,'</div>') as name,
                                CASE
                                WHEN obj.estado =1 THEN 'Active'
                                ELSE 'Inactive'
                                END AS state FROM ERPCRMBundle:CtlProducto obj "
                        . " WHERE obj.estado=1 ORDER BY " . $orderByText . " " . $orderDir;
                $row['data'] = $em->createQuery($sql)
                        ->setFirstResult($start)
                        ->setMaxResults($longitud)
                        ->getResult();
            }
            //var_dump($row);
            return new Response(json_encode($row));
        } catch (\Exception $e) {
            //var_dump($e);
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

}
