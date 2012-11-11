<?php

namespace Mcc\ASMemberBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mcc\ASMemberBundle\Entity\AutonomousSystem;
use Mcc\ASMemberBundle\Entity\IpRange;
use Mcc\ASMemberBundle\Form\IpRangeType;

/**
 * IpRange controller.
 *
 */
class IpRangeController extends Controller {

    /**
     * Lists all IpRange entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MccASMemberBundle:IpRange')->findAll();
        ini_set('max_execution_time', 30000000000);
        return $this->render('MccASMemberBundle:IpRange:index.html.twig', array(
                    'entities' => $entities,
                ));
    }

    /**
     * Finds and displays a IpRange entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MccASMemberBundle:IpRange')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find IpRange entity.');
        }
        /* START ASid
         * Pobieram AsId dla danego IP-Range że by pobrać nazwa AS oraz numer AS aby móć ich wyświetlić 
         */
        $asId = $entity->getAsid();
        $as = $em->getRepository('MccASMemberBundle:AutonomousSystem')->findOneById($asId);
        /* END ASid
         */
        $deleteForm = $this->createDeleteForm($id);

        /* START Rozpiski IP
         * Rozpisanie wszystkie możliwe IP dla danego zakresu
         */
        ini_set('max_execution_time', 30000000000);

        $ip_range = $entity->getIpRangee();
        $ip_arr = explode('/', $ip_range);

        $bin = '';
        for ($i = 1; $i <= 32; $i++) {
            $bin .= $ip_arr[1] >= $i ? '1' : '0';
        }
        $ip_arr[1] = bindec($bin);

        $ip = ip2long($ip_arr[0]);
        $nm = ip2long($ip_arr[1]);
        $nw = ($ip & $nm);
        $bc = $nw | (~$nm);

        $number_of_host = ($bc - $nw - 1);
        $host_range = long2ip($nw + 1) . " -> " . long2ip($bc - 1);
        $ip_addr = array();

        for ($zm = 1; ($nw + $zm) <= ($bc - 1); $zm++) {
            $ip_addr[$zm] = long2ip($nw + $zm);
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $ip_addr, $this->get('request')->query->get('page', 1)/* page number */, 255/* limit per page */
        );


        /* END Rozpiska IP
         */

        return $this->render('MccASMemberBundle:IpRange:show.html.twig', array(
                    'entity' => $entity,
                    'numberOfHosts' => $number_of_host,
                    'hostRange' => $host_range,
                    'ipAddr' => $pagination,
                    'asId' => $as,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to create a new IpRange entity.
     *
     */
    public function newAction() {
        $entity = new IpRange();
        $form = $this->createForm(new IpRangeType(), $entity);

        return $this->render('MccASMemberBundle:IpRange:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Creates a new IpRange entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new IpRange();
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
                    'form' => $form->createView(),
                ));
    }

    /**
     * Displays a form to edit an existing IpRange entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MccASMemberBundle:IpRange')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find IpRange entity.');
        }

        $editForm = $this->createForm(new IpRangeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MccASMemberBundle:IpRange:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Edits an existing IpRange entity.
     *
     */
    public function updateAction(Request $request, $id) {
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
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Deletes a IpRange entity.
     *
     */
    public function deleteAction(Request $request, $id) {
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

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    public function allIpForThisAS() {
        
    }

}
