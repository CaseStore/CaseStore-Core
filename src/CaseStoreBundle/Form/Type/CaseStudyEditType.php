<?php

namespace CaseStoreBundle\Form\Type;

use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormBuilderInterface;



/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyEditType extends AbstractType {


    protected $container;

    /** @var Project */
    protected $project;

    /** @var  CaseStudy */
    protected $caseStudy;

    protected $caseStudyFieldDefinitions;

    /**
     * CaseStudyNewType constructor.
     * @param $doctrine
     * @param Project $project
     */
    public function __construct($container, Project $project, CaseStudy $caseStudy)
    {
        $this->container = $container;
        $this->project = $project;
        $this->caseStudy = $caseStudy;

        $doctrine = $this->container->get('doctrine')->getManager();

        $this->caseStudyFieldDefinitions = $doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinition')->getForProject($this->project);

    }

    public function getCaseStudyFieldDefinitions()
    {
        return $this->caseStudyFieldDefinitions;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        foreach($this->caseStudyFieldDefinitions as $caseStudyFieldDefinition) {
            if ($caseStudyFieldDefinition->isTypeString()) {

                $oldValue = $this->container->get('case_study_field_type_finder')->getFieldTypeById($caseStudyFieldDefinition->getType())->getLatestValue($caseStudyFieldDefinition, $this->caseStudy);

                $builder->add('field_'.$caseStudyFieldDefinition->getPublicId(), 'text', array(
                    'required' => false,
                    'label'=> $caseStudyFieldDefinition->getTitle(),
                    'data' => $oldValue ? $oldValue->getValue() : '',
                ));

            } else if ($caseStudyFieldDefinition->isTypeText()) {


                $oldValue = $this->container->get('case_study_field_type_finder')->getFieldTypeById($caseStudyFieldDefinition->getType())->getLatestValue($caseStudyFieldDefinition, $this->caseStudy);

                $builder->add('field_'.$caseStudyFieldDefinition->getPublicId(), 'textarea', array(
                    'required' => false,
                    'label'=>$caseStudyFieldDefinition->getTitle(),
                    'data' => $oldValue ? $oldValue->getValue() : '',
                ));

            }
        }



    }

    public function getName() {
        return 'tree';
    }

    public function getDefaultOptions(array $options) {
        return array(
        );
    }
}
