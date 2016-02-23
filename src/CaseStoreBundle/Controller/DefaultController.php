<?php

namespace CaseStoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class DefaultController extends Controller
{
    public function indexAction()
    {

        $doctrine = $this->getDoctrine()->getManager();
        $projects = $doctrine->getRepository('CaseStoreBundle:Project')->findAll();

        return $this->render('CaseStoreBundle:Default:index.html.twig',array(
            'projects'=>$projects,
        ));

    }


    public function youAction()
    {

        return $this->render('CaseStoreBundle:Default:you.html.twig',array(
        ));

    }



}
