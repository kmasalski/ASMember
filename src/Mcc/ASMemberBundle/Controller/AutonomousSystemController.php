<?php

namespace Mcc\ASMemberBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelector;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mcc\ASMemberBundle\Entity\AutonomousSystem;
use Mcc\ASMemberBundle\Entity\IpRange;
use Mcc\ASMemberBundle\Entity\Ip;
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
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entities, $this->get('request')->query->get('page', 1)/* page number */, 50/* limit per page */
        );
        return $this->render('MccASMemberBundle:AutonomousSystem:index.html.twig', array(
                    'entities' => $pagination,
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

        $ranges = $em->getRepository('MccASMemberBundle:IpRange')->findByAsid($entity);

        $deleteForm = $this->createDeleteForm($id);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $ranges, $this->get('request')->query->get('page', 1)/* page number */, 15/* limit per page */
        );

        return $this->render('MccASMemberBundle:AutonomousSystem:show.html.twig', array(
                    'entity' => $entity,
                    /* 'ranges' => $ranges, */
                    'rangeslist' => $pagination,
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
        $ipRange = $em->getRepository('MccASMemberBundle:IpRange');
        echo $asId;
        $pageAddress = 'http://www.cidr-report.org/cgi-bin/as-report?as=' . $as->getAsIdentifier() . '&view=2.0';

        //$pageAddress= 'http://www.cidr-report.org/cgi-bin/as-report?as=AS8970&view=2.0';
        $crawler = new Crawler(file_get_contents($pageAddress));

        $asName = $crawler->filterXpath('//body/ul')->text();

        //return new Response(var_dump($asName));

        $as->setAsname($asName);
        $em->persist($as);
//(array('my_field' => 'value'))
        $crawler->filter('a.black')->each(function ($node, $i) use (&$em, &$as, &$ipRange) {
                    //  if (is_null($em->getRepository('MccASMemberBundle:IpRange')->findOneBy(array('IpRange'=>$node->nodeValue))))/*IpRange($node->nodeValue)))*/ {
                    $ipRange = new IpRange();
                    $ipRange->setAsId($as);
                    $ipRange->setDateCheck(new \DateTime('now'));
                    $ipRange->setIpRange($node->nodeValue);
                    $em->persist($ipRange);
                    if (!$i % 1000) {
                        $em->flush();
                    }
                    // }
                });

        /*
          $crawler->filter('a.red')->each(function ($node, $i) use (&$em, &$as, &$ipRange) {
          // if (is_null($em->getRepository('MccASMemberBundle:IpRange')->findOneBy(array('IpRange'=>$node->nodeValue)))) {
          $ipRange = new IpRange();
          $ipRange->setAsId($as);
          $ipRange->setDateCheck(new \DateTime('now'));
          $ipRange->setIpRange($node->nodeValue);
          $em->persist($ipRange);
          if (!$i % 300) {
          $em->flush();
          }
          // }
          });
          $crawler->filter('a.green')->each(function ($node, $i) use (&$em, &$as, &$ipRange) {
          // if (is_null($em->getRepository('MccASMemberBundle:IpRange')->findOneBy(array('IpRange'=>$node->nodeValue)))) {
          $ipRange = new IpRange();
          $ipRange->setAsId($as);
          $ipRange->setDateCheck(new \DateTime('now'));
          $ipRange->setIpRange($node->nodeValue);
          $em->persist($ipRange);
          if (!$i % 300) {
          $em->flush();
          }
          //  }
          }); */
        $em->flush();
        return new Response('Everything went ok');
    }

    /**
     * Calls parse function for every AS id
     */
    public function parseAllAction() {
        /* $em = $this->getDoctrine()->getManager();
          ini_set('max_execution_time', 30000000000);
          $ases = $em->getRepository('MccASMemberBundle:AutonomousSystem')->findAll();

          foreach ($ases as $as) {
          $zmienna=$as->getID();
          if($zmienna>19664){
          //echo $zmienna;
          $this->parseAction($as->getId());
          }
          }
          return new Response('Everything went ok'); */
        return new Response('Ta funkcja jest zakodowana w Controlle-rze aby nie doszło do aktualizacji bazy danych przez prypadek.');
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
                    if ($i % 100 == 0) {
                        $em->flush();
                    }
                });
        $em->flush();
        return new Response(var_dump($crawler));
    }

    /*
      public function testingAction() {

      // $em = $this->getDoctrine()->getManager();
      ini_set('max_execution_time', 30000000000);
      //  $as = $em->getRepository('MccASMemberBundle:AutonomousSystem')->find($asId);
      //echo $asId;
      $pageAddress = 'http://www.cidr-report.org/cgi-bin/as-report?as=AS4134&view=2.0';

      // $pageAddress= 'http://www.cidr-report.org/cgi-bin/as-report?as=AS8970&view=2.0';
      $crawler = new Crawler(file_get_contents($pageAddress));

      //$asName = $crawler->filterXpath('//body/ul')->text();

      //return new Response(var_dump($asName));

      //$as->setAsname($asName);
      // $em->persist($as);

      // $kino=$crawler->filterXpath(('//body/ul')->extract(array('_text', 'class'));
      $attributes = $crawler->filterXpath('//body/ul')->extract(array('a', 'black'));

      return new Response(var_dump($attributes));


      }
     */

    public function serwersAction($id) {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MccASMemberBundle:Ip')->findByAsidentifier($id);
        $zmienna = 1;

        if (!$entity) {
            $zmienna = 0;
            $as = $em->getRepository('MccASMemberBundle:AutonomousSystem')->find($id);
            return $this->render('MccASMemberBundle:AutonomousSystem:serwers.html.twig', array(
                        'as' => $as,
                        'help' => $zmienna,
                    ));
        }




        $as = $em->getRepository('MccASMemberBundle:AutonomousSystem')->find($id);



        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entity, $this->get('request')->query->get('page', 1)/* page number */, 25/* limit per page */
        );

        return $this->render('MccASMemberBundle:AutonomousSystem:serwers.html.twig', array(
                    'as' => $as,
                    'serwers' => $pagination,
                    'help' => $zmienna,
                ));
    }

    public function checkAllAction($id) {

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

        for ($i = 1; $i < sizeof($ip_addr) + 1; $i++) {
            
            $url = $ip_addr[$i];
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_exec($ch);
            $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($retcode >= 200 && $retcode <= 500) {

                $ip_adr = new Ip();
                $ip_adr->setIp($ip);
                $ip_adr->setAutonomousSytem($asId);
                $ip_adr->setIswebserver(1);
                $ip_adr->setLastcheck(new \DateTime('now'));
                $em->persist($ip_adr);
                $em->flush();
                
            }
        }
        return $this->render('MccASMemberBundle:AutonomousSystem:checkAllIp.html.twig', array(
                    'as' => $as,
                ));
    }

}
