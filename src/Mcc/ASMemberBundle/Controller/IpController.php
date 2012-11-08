<?php

namespace Mcc\ASMemberBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mcc\ASMemberBundle\Entity\IpRange;
use Mcc\ASMemberBundle\Entity\AutonomousSystem;
use Mcc\ASMemberBundle\Entity\Ip;
use Mcc\ASMemberBundle\Form\IpType;

/**
 * Ip controller.
 *
 */
class IpController extends Controller {

    /**
     * Lists all Ip entities.
     *
     */
    public function indexAction() {
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
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MccASMemberBundle:Ip')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ip entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MccASMemberBundle:Ip:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to create a new Ip entity.
     *
     */
    public function newAction() {
        $entity = new Ip();
        $form = $this->createForm(new IpType(), $entity);

        return $this->render('MccASMemberBundle:Ip:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Creates a new Ip entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Ip();
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
                    'form' => $form->createView(),
                ));
    }

    /**
     * Displays a form to edit an existing Ip entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MccASMemberBundle:Ip')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ip entity.');
        }

        $editForm = $this->createForm(new IpType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MccASMemberBundle:Ip:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Edits an existing Ip entity.
     *
     */
    public function updateAction(Request $request, $id) {
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
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Deletes a Ip entity.
     *
     */
    public function deleteAction(Request $request, $id) {
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

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    //zwraca lista wszystkich adresow IP
    public function allIpAction() {
        
                $url = "156.17.231.34";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_NOBODY, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_exec($ch);
                $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if ($retcode >= 200 && $retcode <= 500) {
                    echo "work " . $retcode."<br/>";
                 
                } else {
                    echo "nie dziala " . $retcode."<br/>";
                 
                }

/*
        ini_set('max_execution_time', 30000000000);
        $em = $this->getDoctrine()->getEntityManager();
        $ases = $em->getRepository('MccASMemberBundle:AutonomousSystem')->findAll();

        foreach ($ases as $as) {
            $id_as = $as->getId();
            $as_ip_range = $em->getRepository('MccASMemberBundle:IpRange')->find($id_as);

            $ip_addr_cidr = $as_ip_range->getIpRangee();
            $ip_arr = explode('/', $ip_addr_cidr);

            $bin = '';
            for ($i = 1; $i <= 32; $i++) {
                $bin .= $ip_arr[1] >= $i ? '1' : '0';
            }
            $ip_arr[1] = bindec($bin);

            $ip = ip2long($ip_arr[0]);
            $nm = ip2long($ip_arr[1]);
            $nw = ($ip & $nm);
            $bc = $nw | (~$nm);

            for ($zm = 1; $zm <= ($bc - $nw - 1); $zm++) {
                //echo long2ip($nw + $zm) . "<br/>";}
                /* $em = $this->getDoctrine()->getEntityManager();
                  $ip_adr = new Ip();
                  $ip_adr->setIp(long2ip($nw + $zm));
                  $ip_adr->setAutonomousSytem($as);
                  $em->persist($ip_adr);


                  //  $em->flush();
                  if ($zm % 5000 == 0) {
                  // echo "Number of Hosts:    " . ($bc - $nw - 1) . "<br/>";
                  // echo $zm . " ";
                  $em->flush();
                  } */
             /*   echo $zm."<br/>";
                $url = long2ip($nw + $zm);
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_NOBODY, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_exec($ch);
                $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if ($retcode >= 200 && $retcode <= 500) {
                    echo "work " . $retcode . long2ip($nw + $zm)."<br/>";
                  $ip_adr = new Ip();
                  $ip_adr->setIp(long2ip($nw + $zm));
                  $ip_adr->setAutonomousSytem($as);
                  $ip_adr->setIswebserver(1);
                  $em->persist($ip_adr);
                  $em->flush();
                } else {
                    echo "nie dziala " . $retcode. long2ip($nw + $zm)."<br/>";
                  $ip_adr = new Ip();
                  $ip_adr->setIp(long2ip($nw + $zm));
                  $ip_adr->setAutonomousSytem($as);
                  $ip_adr->setIswebserver(0);
                  $em->persist($ip_adr);
                  $em->flush();
                }
            }
            echo "koniec";
            $em->flush();
        }
        /*
          $ip_addr_cidr = "192.256.0.0/16";
          $ip_arr = explode('/', $ip_addr_cidr);

          $bin = '';
          for ($i = 1; $i <= 32; $i++) {
          $bin .= $ip_arr[1] >= $i ? '1' : '0';
          }
          $ip_arr[1] = bindec($bin);

          $ip = ip2long($ip_arr[0]);
          $nm = ip2long($ip_arr[1]);
          $nw = ($ip & $nm);
          $bc = $nw | (~$nm);

          echo "Number of Hosts:    " . ($bc - $nw - 1) . "<br/>";
          echo "Host Range:         " . long2ip($nw + 1) . " -> " . long2ip($bc - 1) . "<br/>" . "<br/>";

          for ($zm = 1; ($nw + $zm) <= ($bc - 1); $zm++) {
          echo long2ip($nw + $zm) . "<br/>";

         */
    }

}