<?php

namespace CaseStoreBundle\Controller;

use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Form\Type\ProjectNewType;
use CaseStoreBundle\Security\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
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

        $doctrine = $this->getDoctrine()->getManager();
        $caseStudyFieldDefinitions = $doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinition')->getForProject($this->project);

        return $this->render('CaseStoreBundle:Project:index.html.twig', array(
            'project'=>$this->project,
            'newCaseStudyAllowed'=>((boolean)$this->getUser()),
            'newFieldDefinitionAllowed'=>((boolean)$this->getUser()),
            'fieldDefinitions'=>$caseStudyFieldDefinitions,
            'isAdminAccessAllowed'=>$this->isGranted(ProjectVoter::ADMIN, $this->project),
        ));
    }


    public function mapAction($projectId)
    {
        // build
        $this->build($projectId);
        //data

        $doctrine = $this->getDoctrine()->getManager();

        return $this->render('CaseStoreBundle:Project:map.html.twig', array(
            'project'=>$this->project,
        ));
    }

    public function mapDataJSONAction($projectId)
    {
        // build
        $this->build($projectId);

        //data
        $doctrine = $this->getDoctrine()->getManager();

        $data = array();
        foreach($doctrine->getRepository('CaseStoreBundle:CaseStudyLocation')->getInProject($this->project) as $location) {
            $data[] = array(
                'lat' => $location->getLat(),
                'lng' => $location->getLng(),
                'title' => $location->getCaseStudy()->getTitle(),
                'url' => $this->generateUrl('case_store_case_study', array('projectId'=>$this->project->getPublicId(), 'caseStudyId'=>$location->getCaseStudy()->getPublicId())),
            );
        }

        $response = new Response(json_encode(array('data'=>$data)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;

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

    public function myCaseStudiesAction($projectId)
    {
        // build
        $this->build($projectId);

        if (!$this->getUser()) {
            throw new  NotFoundHttpException('Not found');
        }

        //data
        $doctrine = $this->getDoctrine()->getManager();
        $caseStudies = $doctrine->getRepository('CaseStoreBundle:CaseStudy')->getForUserInProject($this->getUser(), $this->project);

        return $this->render('CaseStoreBundle:Project:myCaseStudies.html.twig', array(
            'project'=>$this->project,
            'caseStudies'=>$caseStudies,
        ));
    }


    public function outputsAction($projectId)
    {
        // build
        $this->build($projectId);
        //data

        $doctrine = $this->getDoctrine()->getManager();
        $outputs = $doctrine->getRepository('CaseStoreBundle:Output')->findBy(array('project'=>$this->project));

        return $this->render('CaseStoreBundle:Project:outputs.html.twig', array(
            'project'=>$this->project,
            'outputs'=>$outputs,
        ));
    }

}
