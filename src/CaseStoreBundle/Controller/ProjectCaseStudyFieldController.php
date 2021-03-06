<?php

namespace CaseStoreBundle\Controller;

use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Form\Type\ProjectNewType;
use CaseStoreBundle\Security\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class ProjectCaseStudyFieldController extends ProjectController
{

    /** @var  CaseStudyFieldDefinition */
    protected $caseStudyFieldDefinition;



    protected function buildForField($projectId, $fieldId) {
        $this->build($projectId);

        $doctrine = $this->getDoctrine()->getManager();
        // load
        $repository = $doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinition');
        $this->caseStudyFieldDefinition = $repository->findOneBy(array('project'=>$this->project, 'publicId'=>$fieldId));
        if (!$this->caseStudyFieldDefinition) {
            throw new  NotFoundHttpException('Not found');
        }
    }

    public function selectFieldAction($projectId, $fieldId)
    {
        $this->buildForField($projectId, $fieldId);
        if (!$this->caseStudyFieldDefinition->isTypeSelect()) {
            throw new  NotFoundHttpException('Not found');
        }


        $doctrine = $this->getDoctrine()->getManager();
        $options = $doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinitionOption')->findBy(array('fieldDefinition'=>$this->caseStudyFieldDefinition));

        return $this->render('CaseStoreBundle:ProjectCaseStudyField:selectField.html.twig', array(
            'project'=>$this->project,
            'caseStudyFieldDefinition'=>$this->caseStudyFieldDefinition,
            'options'=>$options,
        ));


    }

    public function selectFieldOptionAction($projectId, $fieldId, $optionId)
    {
        $this->buildForField($projectId, $fieldId);
        if (!$this->caseStudyFieldDefinition->isTypeSelect()) {
            throw new  NotFoundHttpException('Not found');
        }

        $doctrine = $this->getDoctrine()->getManager();
        $option = $doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinitionOption')->findOneBy(
            array('fieldDefinition'=>$this->caseStudyFieldDefinition, 'publicId'=>$optionId)
        );
        if (!$option) {
            throw new  NotFoundHttpException('Not found');
        }

        // data

        $caseStudies = $doctrine->getRepository('CaseStoreBundle:CaseStudy')->findBySelectOption($option);


        return $this->render('CaseStoreBundle:ProjectCaseStudyField:selectFieldOption.html.twig', array(
            'project'=>$this->project,
            'caseStudyFieldDefinition'=>$this->caseStudyFieldDefinition,
            'option'=>$option,
            'caseStudies'=>$caseStudies,
        ));


    }

}
