<?php

namespace Ugap\ProjectBundle\SipiaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SipiaController extends Controller
{
    public function indexAction()
    {
      return $this->render('UgapSipiaBundle:Sipia:index.html.twig', array('nom' => 'SIPIA'));
    }
}
