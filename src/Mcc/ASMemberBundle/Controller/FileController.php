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

        printf("Downloaded %d bytes in %0.4f seconds.\n", $info['size_download'], $time);
        printf("Which is %0.4f mbps\n", $info['size_download'] * 8 / $time / 1024 / 1024);
        printf("CURL said %0.4f mbps\n", $info['speed_download'] * 8 / 1024 / 1024);

        echo "\n\ncurl_getinfo() said:\n", str_repeat('-', 31 + strlen($url)), "\n";
        foreach ($info as $label => $value)
        {
            printf("%-30s %s\n", $label, $value);
        }
    }
    
    public function downloadTestAction()
    {
        return new Response(var_dump($this->download("diety.wp.pl/regulamin_serwisu_wp.pdf")));
    }

}
