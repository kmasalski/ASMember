<?php

namespace Mcc\ASMemberBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mcc\ASMemberBundle\Entity\IpRange;
use Mcc\ASMemberBundle\Form\IpRangeType;

/**
 * IpRange controller.
 *
 */
class IpRangeController extends Controller
{
    /**
     * Lists all IpRange entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MccASMemberBundle:IpRange')->findAll();

        return $this->render('MccASMemberBundle:IpRange:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a IpRange entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MccASMemberBundle:IpRange')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find IpRange entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MccASMemberBundle:IpRange:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new IpRange entity.
     *
     */
    public function newAction()
    {
        $entity = new IpRange();
        $form   = $this->createForm(new IpRangeType(), $entity);

        return $this->render('MccASMemberBundle:IpRange:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new IpRange entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new IpRange();
        $form = $this->createForm(new IpRangeType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('iprange_show', array('id' => $entity->getId())));
        }

        return $this->render('MccASMemberBundle:IpRange:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing IpRange entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MccASMemberBundle:IpRange')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find IpRange entity.');
        }

        $editForm = $this->createForm(new IpRangeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MccASMemberBundle:IpRange:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing IpRange entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MccASMemberBundle:IpRange')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find IpRange entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new IpRangeType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('iprange_edit', array('id' => $id)));
        }

        return $this->render('MccASMemberBundle:IpRange:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a IpRange entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MccASMemberBundle:IpRange')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find IpRange entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('iprange'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    public function allIpForThisAS(){
        
    }
    
}
