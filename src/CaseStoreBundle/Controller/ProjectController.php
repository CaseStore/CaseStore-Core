<?php

namespace CaseStoreBundle\Controller;

use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Form\Type\ProjectNewType;
use CaseStoreBundle\Security\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class ProjectController extends Controller
{

    protected $project;


    protected function build($projectId) {
        $doctrine = $this->getDoctrine()->getManager();
        // load
        $repository = $doctrine->getRepository('CaseStoreBundle:Project');
        $this->project = $repository->findOneByPublicId($projectId);
        if (!$this->project) {
            throw new  NotFoundHttpException('Not found');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $this->project);
    }


    public function indexAction($projectId)
    {
        // build
        $this->build($projectId);
        //data
        return $this->render('CaseStoreBundle:Project:index.html.twig', array(
            'project'=>$this->project,
            'newCaseStudyAllowed'=>((boolean)$this->getUser()),
            'newFieldDefinitionAllowed'=>((boolean)$this->getUser()),
        ));
    }


    public function mapAction($projectId)
    {
        // build
        $this->build($projectId);
        //data

        $doctrine = $this->getDoctrine()->getManager();

        $locations =  $doctrine->getRepository('CaseStoreBundle:CaseStudyLocation')->getInProject($this->project);

        return $this->render('CaseStoreBundle:Project:map.html.twig', array(
            'project'=>$this->project,
            'locations'=>$locations,
        ));
    }

    public function caseStudyFieldDefinitionsAction($projectId)
    {
        // build
        $this->build($projectId);
        //data

        $doctrine = $this->getDoctrine()->getManager();
        $caseStudyFieldDefinitions = $doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinition')->getForProject($this->project);

        return $this->render('CaseStoreBundle:Project:caseStudyFieldDefinitions.html.twig', array(
            'project'=>$this->project,
            'caseStudyFieldDefinitions'=>$caseStudyFieldDefinitions,
        ));
    }


    public function caseStudiesAction($projectId)
    {
        // build
        $this->build($projectId);
        //data

        $doctrine = $this->getDoctrine()->getManager();
        $caseStudies = $doctrine->getRepository('CaseStoreBundle:CaseStudy')->findBy(array('project'=>$this->project));

        return $this->render('CaseStoreBundle:Project:caseStudies.html.twig', array(
            'project'=>$this->project,
            'caseStudies'=>$caseStudies,
        ));
    }



}
