<?php

namespace CaseStoreBundle\Form\Type;

use CaseStoreBundle\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormBuilderInterface;



/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyNewType extends AbstractType {


    protected $doctrine;

    /** @var Project */
    protected $project;

    /**
     * CaseStudyNewType constructor.
     * @param $doctrine
     * @param Project $project
     */
    public function __construct($doctrine, Project $project)
    {
        $this->doctrine = $doctrine;
        $this->project = $project;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $caseStudyFieldDefinitions = $this->doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinition')->getForProject($this->project);

        foreach($caseStudyFieldDefinitions as $caseStudyFieldDefinition) {
            if ($caseStudyFieldDefinition->isTypeString()) {

                $builder->add('field_'.$caseStudyFieldDefinition->getPublicId(), 'text', array(
                    'required' => false,
                    'label'=>$caseStudyFieldDefinition->getTitle(),
                ));

            } else if ($caseStudyFieldDefinition->isTypeText()) {

                $builder->add('field_'.$caseStudyFieldDefinition->getPublicId(), 'textarea', array(
                    'required' => false,
                    'label'=>$caseStudyFieldDefinition->getTitle(),
                    'data'=>$caseStudyFieldDefinition->getDefaultValue(),
                ));

            } else if ($caseStudyFieldDefinition->isTypeInteger()) {

                $builder->add('field_'.$caseStudyFieldDefinition->getPublicId(), 'integer', array(
                    'required' => false,
                    'label'=>$caseStudyFieldDefinition->getTitle(),
                ));

            } else if ($caseStudyFieldDefinition->isTypeSelect()) {

                $choices = array();
                foreach($this->doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinitionOption')->getCurrentForFieldDefinition($caseStudyFieldDefinition) as $option) {
                    $choices[$option->getTitle()] = $option;
                }

                $builder->add('field_'.$caseStudyFieldDefinition->getPublicId(), 'choice', array(
                    'required' => false,
                    'multiple' => true,
                    'choices' => $choices,
                    'choices_as_values' => true,
                    'label'=>$caseStudyFieldDefinition->getTitle(),
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
