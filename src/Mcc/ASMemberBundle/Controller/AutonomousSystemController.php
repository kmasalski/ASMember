<?php

namespace Mcc\ASMemberBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelector;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mcc\ASMemberBundle\Entity\AutonomousSystem;
use Mcc\ASMemberBundle\Entity\IpRange;
use Mcc\ASMemberBundle\Form\AutonomousSystemType;

/**
 * AutonomousSystem controller.
 *
 */
class AutonomousSystemController extends Controller {

    /**
     * Lists all AutonomousSystem entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MccASMemberBundle:AutonomousSystem')->findAll();

        return $this->render('MccASMemberBundle:AutonomousSystem:index.html.twig', array(
                    'entities' => $entities,
                ));
    }

    /**
     * Finds and displays a AutonomousSystem entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MccASMemberBundle:AutonomousSystem')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AutonomousSystem entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MccASMemberBundle:AutonomousSystem:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to create a new AutonomousSystem entity.
     *
     */
    public function newAction() {
        $entity = new AutonomousSystem();
        $form = $this->createForm(new AutonomousSystemType(), $entity);

        return $this->render('MccASMemberBundle:AutonomousSystem:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Creates a new AutonomousSystem entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new AutonomousSystem();
        $form = $this->createForm(new AutonomousSystemType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('autonomoussystem_show', array('id' => $entity->getId())));
        }

        return $this->render('MccASMemberBundle:AutonomousSystem:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Displays a form to edit an existing AutonomousSystem entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MccASMemberBundle:AutonomousSystem')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AutonomousSystem entity.');
        }

        $editForm = $this->createForm(new AutonomousSystemType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MccASMemberBundle:AutonomousSystem:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Edits an existing AutonomousSystem entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MccASMemberBundle:AutonomousSystem')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AutonomousSystem entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AutonomousSystemType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('autonomoussystem_edit', array('id' => $id)));
        }

        return $this->render('MccASMemberBundle:AutonomousSystem:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Deletes a AutonomousSystem entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MccASMemberBundle:AutonomousSystem')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find AutonomousSystem entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('autonomoussystem'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    /**
     * Parses cidr report to get ranges
     * saves ip ranges to database
     */
    public function parseAction($asId) {
        $em = $this->getDoctrine()->getManager();
        ini_set('max_execution_time', 30000000000);
        $as = $em->getRepository('MccASMemberBundle:AutonomousSystem')->find($asId);
        echo $asId;
        $pageAddress = 'http://www.cidr-report.org/cgi-bin/as-report?as=' . $as->getAsIdentifier() . '&view=2.0';

        //$pageAddress= 'http://www.cidr-report.org/cgi-bin/as-report?as=AS8970&view=2.0';
        $crawler = new Crawler(file_get_contents($pageAddress));

        $asName = $crawler->filterXpath('//body/ul')->text();

        //return new Response(var_dump($asName));

        $as->setAsname($asName);
        $em->persist($as);

        $crawler->filter('a.black','a.red','a.green')->each(function ($node, $i) use (&$em, &$as) {
                    $ipRange = new IpRange();
                    $ipRange->setAsId($as);
                    $ipRange->setDateCheck(new \DateTime('now'));
                    $ipRange->setIpRange($node->nodeValue);
                    $em->persist($ipRange);
                    if(!$i%10)
                    {
                        $em->flush();
                    }
                });
        $em->flush();
        return new Response('Everything went ok');
    }

    /**
     * Calls parse function for every AS id
     */
    public function parseAllAction() {
        $em = $this->getDoctrine()->getManager();
        ini_set('max_execution_time', 30000000000);
        $ases = $em->getRepository('MccASMemberBundle:AutonomousSystem')->findAll();
                   
        foreach ($ases as $as) {
            $this->parseAction($as->getId());
        }
        return new Response('Everything went ok');
    }

    public function parseAsNameAction() {

        $pageAddress = 'http://www.cidr-report.org/as2.0/bgp-originas.html';
        ini_set('max_execution_time', 30000000000);
        $crawler = new Crawler(file_get_contents($pageAddress));
        $em = $this->getDoctrine()->getEntityManager();

        $crawler = $crawler->filter('a')->each(function ($node, $i)use (&$em) {

                    $ASys = new AutonomousSystem();
                    $ASys->setAs($node->nodeValue);
                    //$em = $this->getDoctrine()->getEntityManager();
                    $em->persist($ASys);
                    if($i%100 == 0)
                    {
                        $em->flush();
                    }
                });
        $em->flush();
        return new Response(var_dump($crawler));
    }

}
