<?php

namespace Mcc\ASMemberBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mcc\ASMemberBundle\Entity\History;

/**
 * History controller.
 *
 */
class HistoryController extends Controller
{
    /**
     * Lists all History entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MccASMemberBundle:History')->findAll();

        return $this->render('MccASMemberBundle:History:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a History entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MccASMemberBundle:History')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find History entity.');
        }

        return $this->render('MccASMemberBundle:History:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

}
