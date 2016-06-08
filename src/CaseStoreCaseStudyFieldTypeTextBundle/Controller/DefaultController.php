<?php

namespace CaseStoreCaseStudyFieldTypeTextBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CaseStoreCaseStudyFieldTypeTextBundle:Default:index.html.twig');
    }
}
