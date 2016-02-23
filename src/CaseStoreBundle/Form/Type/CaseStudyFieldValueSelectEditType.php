<?php

namespace CaseStoreBundle\Form\Type;

use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormBuilderInterface;



/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyFieldValueSelectEditType extends BaseCaseStudyFieldValueEditType {

    protected $choices;


    protected $oldValues;


    /**
     * BaseCaseStudyFieldValueEditType constructor.
     * @param CaseStudyFieldDefinition $fieldDefinition
     */
    public function __construct(CaseStudyFieldDefinition $fieldDefinition, $choices, $oldValues)
    {
        $this->fieldDefinition = $fieldDefinition;
        $this->choices = array();
        foreach($choices as $choice) {
            $this->choices[$choice->getTitle()] = $choice->getPublicId();
        }
        $this->oldValues = $oldValues;
    }


    public function buildForm(FormBuilderInterface $builder, array $options) {


        $data = array();
        foreach($this->oldValues as $oldValue) {
            $data[] = $oldValue->getOption()->getPublicId();
        }

        $builder->add('value', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, array(
            'required' => false,
            'label'=>$this->fieldDefinition->getTitle(),
            'choices' => $this->choices,
            'choices_as_values'=>true,
            'multiple' => true,
            'expanded' => true,
            'data'=> $data,
        ));

    }

    public function getName() {
        return 'tree';
    }

    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'CaseStoreBundle\Entity\CaseStudyFieldValueSelect',
        );
    }

}


