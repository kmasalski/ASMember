<?php
/* To jest proba*/
namespace Mcc\Bundle\ASMemberBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MccASMemberBundle:Default:index.html.twig', array('name' => $name));
    }
}
