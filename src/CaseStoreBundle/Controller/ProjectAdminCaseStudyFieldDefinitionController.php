<?php

namespace CaseStoreBundle\Controller;

use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use CaseStoreBundle\Entity\CaseStudyFieldDefinitionOption;
use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Form\Type\CaseStudyFieldDefinitionOptionNewType;
use CaseStoreBundle\Form\Type\ProjectNewType;
use CaseStoreBundle\Security\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class ProjectAdminCaseStudyFieldDefinitionController extends Controller
{

    /** @var  Project */
    protected $project;

    /** @var  CaseStudyFieldDefinition */
    protected $caseStudyFieldDefinition;

    protected function build($projectId, $caseStudyFieldDefinitionId) {
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
    }


    public function indexAction($projectId, $caseStudyFieldDefinitionId)
    {
        // build
        $this->build($projectId, $caseStudyFieldDefinitionId);
        //data
        $doctrine = $this->getDoctrine()->getManager();
        $options = $this->caseStudyFieldDefinition->isTypeSelect() ?
            $doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinitionOption')->findBy(array('fieldDefinition'=>$this->caseStudyFieldDefinition)) :
            array();
        return $this->render('CaseStoreBundle:ProjectAdminCaseStudyFieldDefinition:index.html.twig', array(
            'project'=>$this->project,
            'caseStudyFieldDefinition'=>$this->caseStudyFieldDefinition,
            'options'=>$options,
            'newOptionAllowed'=>($this->caseStudyFieldDefinition->isTypeSelect() && (boolean)$this->getUser()),
        ));
    }

    public function newOptionAction($projectId, $caseStudyFieldDefinitionId)
    {
        // build
        $this->build($projectId, $caseStudyFieldDefinitionId);

        if (!$this->caseStudyFieldDefinition->isTypeSelect()) {
            throw new  NotFoundHttpException('Not found');
        }

        if (!$this->getUser()) {
            throw new  NotFoundHttpException('Not found');
        }


        //
        $doctrine = $this->getDoctrine()->getManager();
        $caseStudyFieldDefinitionOption  = new CaseStudyFieldDefinitionOption();
        $caseStudyFieldDefinitionOption->setFieldDefinition($this->caseStudyFieldDefinition);
        $caseStudyFieldDefinitionOption->setSort(0);
        $caseStudyFieldDefinitionOption->setAddedBy($this->getUser());

        $form = $this->createForm(new CaseStudyFieldDefinitionOptionNewType(), $caseStudyFieldDefinitionOption);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $doctrine->persist($caseStudyFieldDefinitionOption);
                $doctrine->flush();
                return $this->redirect($this->generateUrl('case_store_project_admin_case_study_field_definition', array(
                    'projectId' => $this->project->getPublicId(),
                    'caseStudyFieldDefinitionId' => $this->caseStudyFieldDefinition->getPublicId(),
                    )));
            }
        }

        return $this->render('CaseStoreBundle:ProjectAdminCaseStudyFieldDefinition:newOption.html.twig', array(
            'project'=>$this->project,
            'caseStudyFieldDefinition'=>$this->caseStudyFieldDefinition,
            'form' => $form->createView(),
        ));

    }

}
