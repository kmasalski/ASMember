<?php

namespace Mcc\ASMemberBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mcc\ASMemberBundle\Entity\Ip;
use Mcc\ASMemberBundle\Form\IpType;

/**
 * Ip controller.
 *
 */
class IpController extends Controller
{
    /**
     * Lists all Ip entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MccASMemberBundle:Ip')->findAll();

        return $this->render('MccASMemberBundle:Ip:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Ip entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MccASMemberBundle:Ip')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ip entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MccASMemberBundle:Ip:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Ip entity.
     *
     */
    public function newAction()
    {
        $entity = new Ip();
        $form   = $this->createForm(new IpType(), $entity);

        return $this->render('MccASMemberBundle:Ip:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Ip entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Ip();
        $form = $this->createForm(new IpType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ip_show', array('id' => $entity->getId())));
        }

        return $this->render('MccASMemberBundle:Ip:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Ip entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MccASMemberBundle:Ip')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ip entity.');
        }

        $editForm = $this->createForm(new IpType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MccASMemberBundle:Ip:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Ip entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MccASMemberBundle:Ip')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ip entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new IpType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ip_edit', array('id' => $id)));
        }

        return $this->render('MccASMemberBundle:Ip:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Ip entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MccASMemberBundle:Ip')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Ip entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ip'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
