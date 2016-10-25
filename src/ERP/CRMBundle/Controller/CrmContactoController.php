<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CrmContacto;
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
        $em = $this->getDoctrine()->getManager();

        $crmContactos = $em->getRepository('ERPCRMBundle:CrmContacto')->findAll();

        return $this->render('crmcontacto/index.html.twig', array(
            'crmContactos' => $crmContactos,
        ));
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
    
    
    
    /**
    * Ajax utilizado para buscar informacion de un contacto
    *  
    * @Route("/search-contact-select/data/", name="busqueda_contacto_select_info",  options={"expose"=true}))
    */
    public function busquedaContactoAction(Request $request)
    {
        $busqueda = $request->query->get('q');
        $page = $request->query->get('page');
        
        //var_dump($page);
        
        $em = $this->getDoctrine()->getEntityManager();
        $dql = "SELECT CONCAT(per.nombre,' ',per.apellido) as nombre,c.id "
                        . "FROM ERPCRMBundle:CrmContacto c "
                        . "JOIN c.persona per "
                        . "WHERE CONCAT(upper(per.nombre),' ',per.apellido) LIKE upper(:busqueda) AND c.estado=1 "
                        . "ORDER BY per.nombre ASC ";
        
        $row['data'] = $em->createQuery($dql)
                ->setParameters(array('busqueda'=>"%".$busqueda."%"))
                
                ->getResult();
//        var_dump($row['data']);
        return new Response(json_encode($row));
    }
}
