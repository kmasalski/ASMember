<?php

namespace Mcc\ASMemberBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Mcc\ASMemberBundle\Entity\File;

/**
 * File controller.
 *
 */
class FileController extends Controller
{
    /**
     * Lists all File entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MccASMemberBundle:File')->findAll();

        return $this->render('MccASMemberBundle:File:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a File entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MccASMemberBundle:File')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }

        return $this->render('MccASMemberBundle:File:show.html.twig', array(
            'entity'      => $entity,
        ));
    }
    
}
