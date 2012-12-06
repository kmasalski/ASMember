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
        $fileRepository = $em->getRepository('MccASsMember:File');
        foreach ($links as $link) 
        {
            $statistics = $this->download($link);
            
            //tworzymy lub nie obiekt file
            
            $file = $fileRepository->getFileByAddress($link);
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
