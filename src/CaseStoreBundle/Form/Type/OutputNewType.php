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
class OutputNewType extends AbstractType {


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

        $outputFieldDefinitions = $this->doctrine->getRepository('CaseStoreBundle:OutputFieldDefinition')->getForProject($this->project);

        foreach($outputFieldDefinitions as $outputFieldDefinition) {
            if ($outputFieldDefinition->isTypeString()) {

                $builder->add('field_'.$outputFieldDefinition->getPublicId(), 'text', array(
                    'required' => false,
                    'label'=>$outputFieldDefinition->getTitle(),
                ));

            } else if ($outputFieldDefinition->isTypeText()) {

                $builder->add('field_'.$outputFieldDefinition->getPublicId(), 'textarea', array(
                    'required' => false,
                    'label'=>$outputFieldDefinition->getTitle(),
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
