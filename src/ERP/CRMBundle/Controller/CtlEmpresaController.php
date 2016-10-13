<?php

namespace ERP\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ERP\CRMBundle\Entity\CtlEmpresa;
use ERP\CRMBundle\Form\CtlEmpresaType;

/**
 * CtlEmpresa controller.
 *
 * @Route("/admin/company")
 */
class CtlEmpresaController extends Controller
{
    /**
     * Lists all CtlEmpresa entities.
     *
     * @Route("/", name="admin_company_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ctlEmpresas = $em->getRepository('ERPCRMBundle:CtlEmpresa')->findAll();

        return $this->render('ctlempresa/index.html.twig', array(
            'ctlEmpresas' => $ctlEmpresas,
        ));
    }        

    /**
     * Creates a new CtlEmpresa entity.
     *
     * @Route("/new", name="admin_company_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ctlEmpresa = new CtlEmpresa();
        $form = $this->createForm('ERP\CRMBundle\Form\CtlEmpresaType', $ctlEmpresa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlEmpresa);
            $em->flush();

            return $this->redirectToRoute('._admin_company_show', array('id' => $ctlEmpresa->getId()));
        }

        return $this->render('ctlempresa/new.html.twig', array(
            'ctlEmpresa' => $ctlEmpresa,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CtlEmpresa entity.
     *
     * @Route("/{id}", name="admin_company_show")
     * @Method("GET")
     */
    public function showAction(CtlEmpresa $ctlEmpresa)
    {
        $deleteForm = $this->createDeleteForm($ctlEmpresa);

        return $this->render('ctlempresa/show.html.twig', array(
            'ctlEmpresa' => $ctlEmpresa,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CtlEmpresa entity.
     *
     * @Route("/{id}/edit", name="admin_company_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CtlEmpresa $ctlEmpresa)
    {
        $deleteForm = $this->createDeleteForm($ctlEmpresa);
        $editForm = $this->createForm('ERP\CRMBundle\Form\CtlEmpresaType', $ctlEmpresa);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ctlEmpresa);
            $em->flush();

            return $this->redirectToRoute('._admin_company_edit', array('id' => $ctlEmpresa->getId()));
        }

        return $this->render('ctlempresa/edit.html.twig', array(
            'ctlEmpresa' => $ctlEmpresa,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CtlEmpresa entity.
     *
     * @Route("/{id}", name="admin_company_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CtlEmpresa $ctlEmpresa)
    {
        $form = $this->createDeleteForm($ctlEmpresa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ctlEmpresa);
            $em->flush();
        }

        return $this->redirectToRoute('._admin_company_index');
    }

    /**
     * Creates a form to delete a CtlEmpresa entity.
     *
     * @param CtlEmpresa $ctlEmpresa The CtlEmpresa entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CtlEmpresa $ctlEmpresa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('._admin_company_delete', array('id' => $ctlEmpresa->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
