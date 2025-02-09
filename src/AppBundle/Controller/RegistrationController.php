<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Registration;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Registration controller.
 *
 * @Route("registration")
 */
class RegistrationController extends Controller
{
    /**
     * Lists all registration entities.
     *
     * @Route("/", name="registration_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $registrations = $em->getRepository('AppBundle:Registration')->findAll();

        return $this->render('registration/index.html.twig', array(
            'registrations' => $registrations,
        ));
    }

    /**
     * Creates a new registration entity.
     *
     * @Route("/new", name="registration_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $registration = new Registration();
        $form = $this->createForm('AppBundle\Form\RegistrationType', $registration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($registration);
            $em->flush();

            return $this->redirectToRoute('registration_show', array('id' => $registration->getId()));
        }

        return $this->render('registration/new.html.twig', array(
            'registration' => $registration,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a registration entity.
     *
     * @Route("/{id}", name="registration_show")
     * @Method("GET")
     */
    public function showAction(Registration $registration)
    {
        $deleteForm = $this->createDeleteForm($registration);

        return $this->render('registration/show.html.twig', array(
            'registration' => $registration,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing registration entity.
     *
     * @Route("/{id}/edit", name="registration_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Registration $registration)
    {
        $deleteForm = $this->createDeleteForm($registration);
        $editForm = $this->createForm('AppBundle\Form\RegistrationType', $registration);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('registration_edit', array('id' => $registration->getId()));
        }

        return $this->render('registration/edit.html.twig', array(
            'registration' => $registration,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a registration entity.
     *
     * @Route("/{id}", name="registration_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Registration $registration)
    {
        $form = $this->createDeleteForm($registration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($registration);
            $em->flush();
        }

        return $this->redirectToRoute('registration_index');
    }

    /**
     * Creates a form to delete a registration entity.
     *
     * @param Registration $registration The registration entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Registration $registration)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('registration_delete', array('id' => $registration->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
