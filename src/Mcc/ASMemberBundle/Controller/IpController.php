<?php

namespace Mcc\ASMemberBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mcc\ASMemberBundle\Entity\IpRange;
use Mcc\ASMemberBundle\Entity\AutonomousSystem;
use Mcc\ASMemberBundle\Entity\Ip;
use Mcc\ASMemberBundle\Form\IpType;
use Symfony\Component\DomCrawler\Crawler;

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
        if ($retcode >= 100 && $retcode <= 505) {
            echo "work " . $retcode . "<br/>";
        } else {
            echo "nie dziala " . $retcode . "<br/>";
        }
    }

    /*
     * showIpAction będzie pokazywało informacje o danym Ip
     * jeżeli ono już było sprawdzane, jeżeli nie zwróci informacje
     * że nie było sprawdzane i umożliwy sprawdzanie tego 
     */

    public function showIpAction($ip, $rangeid) {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MccASMemberBundle:IpRange')->find($rangeid);

        $asId = $entity->getAsid();
        $as = $em->getRepository('MccASMemberBundle:AutonomousSystem')->findOneById($asId);
        $asidentifier = $as->getAsIdentifier();
        $asName = $as->getAsname();

        $ipRange = $entity->getIpRangee();

        $komunikat = 0;

        $ip_addr = $em->getRepository('MccASMemberBundle:Ip')->findOneByIp($ip);
        if (!$ip_addr) {
            $komunikat = 1;
        }

        return $this->render('MccASMemberBundle:Ip:showIp.html.twig', array(
                    'komunikat' => $komunikat,
                    // 'info' =>   $ip_addr_id,
                    'rangeId' => $rangeid,
                    'range' => $ipRange,
                    'ip_addr' => $ip_addr,
                    'asidentifier' => $asidentifier,
                    'asname' => $asName,
                    'ip' => $ip,
                ));
    }

    /*
     * checkIpAction będzie sprawdzało czy Ip jest serwerem czy nie
     */

    public function checkIpAction($ip, $rangeid) {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MccASMemberBundle:IpRange')->find($rangeid);

        $asId = $entity->getAsid();
        $as = $em->getRepository('MccASMemberBundle:AutonomousSystem')->findOneById($asId);
        $asidentifier = $as->getAsIdentifier();
        $asName = $as->getAsname();

        $ipRange = $entity->getIpRangee();

        $retcode = $this->checkIpByCurl($ip);

        if ($retcode >= 100 && $retcode <= 505) {

            $ip_adr = new Ip();
            $ip_adr->setIp($ip);
            $ip_adr->setAutonomousSytem($asId);
            $ip_adr->setIswebserver(1);
            $ip_adr->setLastcheck(new \DateTime('now'));
            $em->persist($ip_adr);
            $em->flush();
            $answer = $ip . " is web server";
        } else {
            $answer = $ip . " is not web server";
        }

        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {

            $returnArray = array("responseCode" => 200, "isWebServer" => $answer);
            $return = json_encode($returnArray); //jscon encode the array

            return new Response($return, 200);
        } else {

            return $this->render('MccASMemberBundle:Ip:checkIp.html.twig', array(
                        'answer' => $answer,
                        'rangeId' => $rangeid,
                        'range' => $ipRange,
                        'asidentifier' => $asidentifier,
                        'asname' => $asName,
                        'ip' => $ip,
                    ));
        }
    }

    /*
     * Sprawdza czy ip jest web serwerem używając curla
     * 
     * zwraca return code curla, 200 ok 40x -błąd po stronie klienta itp...
     */

    public function checkIpByCurl($ip) {

        $ch = curl_init($ip);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $retcode;
    }
    
    var $serwersFound = 0;

    public function findSerwersAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MccASMemberBundle:AutonomousSystem')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AutonomousSystem entity.');
        }

        $ranges = $em->getRepository('MccASMemberBundle:IpRange')->findByAsid($entity);
        $count = sizeof($ranges);
        GLOBAL $serwerArray;
        for ($c = 0; $c < $count; $c++) {
            ini_set('max_execution_time', 30000000000);
            $range[] = "range" . $c;
            ${$range[$c]} = array();
        }
  
        $wielkoscProbki = 4;
        $iloscBadan = 10;
        $serwersToBeFound = 10;
        $testy = 0;
        GLOBAL $serwersFound;
        $serwersFound = 0;
        for ($i1 = 0; $i1 < $count && $serwersFound < $serwersToBeFound; $i1+=$wielkoscProbki) {

            $wielkoscProbki = min($wielkoscProbki, $count - $i1);
            for ($i11 = 0; $i11 < $wielkoscProbki; $i11++) {

                $badanaProbka[$i11] = $this->rangeToArray($ranges[$i1 + $i11]);
                $bools[$i11] = true;
            }

            while ($this->checkBools($bools) && $serwersFound < $serwersToBeFound) {
                for ($i2 = 0; $i2 < $wielkoscProbki && $serwersFound < $serwersToBeFound ; $i2++) {
                    for ($i3 = 0; $i3 < $iloscBadan && $bools[$i2] && $serwersFound < $serwersToBeFound ; $i3++) {

                        $bools[$i2] = $this->doDNSReverse($badanaProbka[$i2],$id);
                        $testy++;

                    }
                }
            }

        }


        return $this->render('MccASMemberBundle:Ip:find_serwers.html.twig', array(
            'entity' => $entity,
            'serwerArray'=>$serwerArray,
            'prob'=>$testy,
            'iloscSerwerow'=>$serwersFound,
                ));
    }

    public function checkBools($bools) {
        foreach ($bools as $bool) {
            if ($bool)
                return true;
        }
        return false;
    }

    public function rangeToArray($range) {
        /*
         * Zwraca tablice zapelniona adresami ip dla danego rangea
         */
        $ip_range = $range;
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



        for ($zm = 1; ($nw + $zm) <= ($bc - 1); $zm++) {
            $array[$zm] = long2ip($nw + $zm);
        }
        return $array;
    }

    public function doDNSReverse($array,$id) {
        /*
         * 1. Losuje liczbe z zasiegu 0-> sizeof($array)
         * 2. Bada dla wylosowanej liczby
         * 3. Usuwa wylosowana liczbe z array (metoda ktora znalzl konrad)
         * 4. Jesli tablica jest pusta zwraca false, else zwraca true
         */
        $losowa = rand(0, sizeof($array));
        $ip = $array[$losowa];
        $reversedns = gethostbyaddr($ip);
        if ($reversedns != $ip and $reversedns != FALSE) {
            GLOBAL $serwersFound;
              $em = $this->getDoctrine()->getManager();
              $ip_adr = new Ip();
              $ip_adr->setIp($ip);
              $ip_adr->setHostname($reversedns);
              $entity = $em->getRepository('MccASMemberBundle:AutonomousSystem')->find($id);
              $ip_adr->setAutonomousSytem($entity);
              $ip_adr->setIswebserver(1);
              $ip_adr->setLastcheck(new \DateTime('now'));
              $em->persist($ip_adr);
              $em->flush(); 
              $links = $this->search($reversedns);
              $this->saveFiles($links, $ip_adr);
              global $serwerArray;
              $serwerArray[$serwersFound]= $ip;
              $serwersFound++;

        }

        unset($array[$losowa]); //musimy zmienic na to ze usuwa i przesuwa tablice
        $array = array_values($array);

        if (sizeof($array) > 1) {
            return true;
        }
        return false;
    }
    
    function search($domain)
    {
	$question= urlencode($domain);
        $sites[] = "http://www.google.ca/search?as_sitesearch={$question}&as_filetype=pdf";
        $sites[] = "http://www.google.ca/search?as_sitesearch={$question}&as_filetype=svg";
        $sites[] = "http://www.google.ca/search?as_sitesearch={$question}&as_filetype=doc";
        $sites[] = "http://www.google.ca/search?as_sitesearch={$question}&as_filetype=xls";
        $sites[] = "http://www.google.ca/search?as_sitesearch={$question}&as_filetype=docs";
        $sites[] = "http://www.google.ca/search?as_sitesearch={$question}&as_filetype=rtf";
        $sites[] = "http://www.google.ca/search?as_sitesearch={$question}&as_filetype=txt";
        
        $links = array();
        foreach ($sites as $site) {
            
        
	$handler = curl_init();
	curl_setopt($handler, CURLOPT_URL, $site);
	curl_setopt($handler, CURLOPT_HEADER, 0);
	curl_setopt($handler, CURLOPT_RETURNTRANSFER, 1);

        $urlContent = curl_exec($handler);

	curl_close($handler);
        
        $crawler = new Crawler($urlContent);
        $crawler->filter('cite')->each(function ($node, $i) use (&$links){
        
            $links[] = $node->nodeValue;
        });
     }
     
        return $links;
    }
    
    function searchTestAction()
    {
        
        return new Response(var_dump($this->search("wp.pl")));       		
    }
    
    public function download($url)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Sitepoint Examples (thread 581410; http://www.sitepoint.com/forums/showthread.php?t=581410)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        set_time_limit(65);

        curl_exec($ch);
        $info = curl_getinfo($ch);

        // Time spent downloading, I think
        $time = $info['total_time']
              - $info['namelookup_time']
              - $info['connect_time']
              - $info['pretransfer_time']
              - $info['starttransfer_time']
              - $info['redirect_time'];
        
        $statistics['size_download'] = $info['size_download'];
        $statistics['time'] = $time;
        $statistics['speedCurl'] = $info['speed_download'] * 8 / 1024 / 1024;
        $statistics['speedObtained'] = $info['size_download'] * 8 / $time / 1024 / 1024;
        
        unset($ch);
        return $statistics;
    }
    
    public function downloadTestAction()
    {
        return new Response(var_dump($this->download("diety.wp.pl/regulamin_serwisu_wp.pdf")));
    }
    
    public function saveFiles($links, $ip_adr)
    {
        $em = $this->getDoctrine()->getManager();
        $fileRepository = $em->getRepository('MccASMemberBundle:File');
        foreach ($links as $link) 
        {
            $statistics = $this->download($link);
            
            $file = $fileRepository->getByAddress($link);
            if($file == null)
            {
                $file = new File();
                $file->setAddress($link);
                $file->setIpid($ip_adr);
                $file->setSize($statistics['size_download']);
                $em->persist($file);
            }
            
            $history = new \Mcc\ASMemberBundle\Entity\History();
            $history->setFileId();
            $history->setSpeedCurl($statistics['speedCurl']);
            $history->setSpeedObtained($statistics['speedObtained']);
            $history->setTime($statistics['time']);
            $history->setWhenchecked(time());
            
            $em->persist($history);
            
        }
        $em->flush();
    }
}