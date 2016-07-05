<?php

namespace CaseStoreBundle\Controller;

use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use CaseStoreBundle\Entity\CaseStudyFieldDefinitionOption;
use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Security\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class ProjectAdminCaseStudyFieldDefinitionOptionController extends Controller
{

    /** @var  Project */
    protected $project;

    /** @var  CaseStudyFieldDefinition */
    protected $caseStudyFieldDefinition;

    /** @var  CaseStudyFieldDefinitionOption */
    protected $caseStudyFieldDefinitionOption;

    protected function build($projectId, $caseStudyFieldDefinitionId, $optionId) {
        $doctrine = $this->getDoctrine()->getManager();
        // load
        $repository = $doctrine->getRepository('CaseStoreBundle:Project');
        $this->project = $repository->findOneByPublicId($projectId);
        if (!$this->project) {
            throw new  NotFoundHttpException('Not found');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::ADMIN, $this->project);
        // load
        $repository = $doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinition');
        $this->caseStudyFieldDefinition = $repository->findOneBy(array('project'=>$this->project, 'publicId'=>$caseStudyFieldDefinitionId));
        if (!$this->caseStudyFieldDefinition) {
            throw new  NotFoundHttpException('Not found');
        }
        // load
        $repository = $doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinitionOption');
        $this->caseStudyFieldDefinitionOption = $repository->findOneBy(array('fieldDefinition'=>$this->caseStudyFieldDefinition, 'publicId'=>$optionId));
        if (!$this->caseStudyFieldDefinitionOption) {
            throw new  NotFoundHttpException('Not found');
        }
    }


    public function indexAction($projectId, $caseStudyFieldDefinitionId, $optionId)
    {
        // build
        $this->build($projectId, $caseStudyFieldDefinitionId, $optionId);
        //data

        return $this->render('CaseStoreBundle:ProjectAdminCaseStudyFieldDefinitionOption:index.html.twig', array(
            'project'=>$this->project,
            'caseStudyFieldDefinition'=>$this->caseStudyFieldDefinition,
            'option'=>$this->caseStudyFieldDefinitionOption,
        ));
    }

}
