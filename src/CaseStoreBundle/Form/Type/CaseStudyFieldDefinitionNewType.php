<?php

namespace CaseStoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyFieldDefinitionNewType extends AbstractType {


    public function buildForm(FormBuilderInterface $builder, array $options) {


        $builder->add('title', 'text', array(
            'required' => true,
            'label'=>'Title'
        ));

        // TODO enforce slug like!
        $builder->add('publicId', 'text', array(
            'required' => true,
            'label'=>'Key'
        ));

        $builder->add('type', ChoiceType::class, array(
            'choices'  => array(
                'String' => 'STRING',
                'Text' => 'TEXT',
                'Select' => 'SELECT',
                'Integer' => 'INTEGER',
            ),
            'required' => true,
        ));
    }

    public function getName() {
        return 'tree';
    }

    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'CaseStoreBundle\Entity\CaseStudyFieldDefinition',
        );
    }
}
