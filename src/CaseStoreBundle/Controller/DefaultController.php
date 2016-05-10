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
        $projectRepo = $doctrine->getRepository('CaseStoreBundle:Project');

        $project = $projectRepo->findOneBy( array('isSystemDefault' => true) );
        if ($project) {
            return $this->redirect($this->generateUrl('case_store_project', array(
                'projectId'=>$project->getPublicId(),
            )));
        } else {
            return $this->redirect($this->generateUrl('case_store_homepage_projects'));
        }

    }

    public function projectAction()
    {

        $doctrine = $this->getDoctrine()->getManager();
        $projectRepo = $doctrine->getRepository('CaseStoreBundle:Project');

        $projects = $projectRepo->findAll();

        return $this->render('CaseStoreBundle:Default:project.html.twig',array(
            'projects'=>$projects,
        ));

    }

    public function youAction()
    {

        return $this->render('CaseStoreBundle:Default:you.html.twig',array(
        ));

    }



}
