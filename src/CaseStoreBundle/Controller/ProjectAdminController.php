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
class ProjectAdminController extends Controller
{

    protected $project;


    protected function build($projectId)
    {
        $doctrine = $this->getDoctrine()->getManager();
        // load
        $repository = $doctrine->getRepository('CaseStoreBundle:Project');
        $this->project = $repository->findOneByPublicId($projectId);
        if (!$this->project) {
            throw new  NotFoundHttpException('Not found');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::ADMIN, $this->project);
    }


    public function indexAction($projectId)
    {
        // build
        $this->build($projectId);
        //data

        return $this->render('CaseStoreBundle:ProjectAdmin:index.html.twig', array(
            'project' => $this->project,
        ));
    }

}

