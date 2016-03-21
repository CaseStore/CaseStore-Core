<?php

namespace CaseStoreBundle\Controller;

use CaseStoreBundle\Entity\Output;
use CaseStoreBundle\Entity\OutputFieldDefinition;
use CaseStoreBundle\Entity\OutputFieldDefinitionOption;
use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Form\Type\OutputFieldDefinitionOptionNewType;
use CaseStoreBundle\Form\Type\ProjectNewType;
use CaseStoreBundle\Security\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class ProjectAdminOutputFieldDefinitionController extends Controller
{

    /** @var  Project */
    protected $project;

    /** @var  OutputFieldDefinition */
    protected $outputFieldDefinition;

    protected function build($projectId, $outputFieldDefinitionId) {
        $doctrine = $this->getDoctrine()->getManager();
        // load
        $repository = $doctrine->getRepository('CaseStoreBundle:Project');
        $this->project = $repository->findOneByPublicId($projectId);
        if (!$this->project) {
            throw new  NotFoundHttpException('Not found');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::ADMIN, $this->project);
        // load
        $repository = $doctrine->getRepository('CaseStoreBundle:OutputFieldDefinition');
        $this->outputFieldDefinition = $repository->findOneBy(array('project'=>$this->project, 'publicId'=>$outputFieldDefinitionId));
        if (!$this->outputFieldDefinition) {
            throw new  NotFoundHttpException('Not found');
        }
    }


    public function indexAction($projectId, $outputFieldDefinitionId)
    {
        // build
        $this->build($projectId, $outputFieldDefinitionId);
        //data
        return $this->render('CaseStoreBundle:ProjectAdminOutputFieldDefinition:index.html.twig', array(
            'project'=>$this->project,
            'outputFieldDefinition'=>$this->outputFieldDefinition,));
    }


}
