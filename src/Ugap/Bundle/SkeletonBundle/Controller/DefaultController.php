<?php

namespace Ugap\Bundle\SkeletonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('UgapSkeletonBundle:Default:index.html.twig', array('name' => $name));
    }
}
