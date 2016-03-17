<?php

namespace CaseStoreBundle\Form\Type;

use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormBuilderInterface;



/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class OutputFieldValueStringEditType extends BaseOutputFieldValueEditType {




    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('value', 'text', array(
            'required' => false,
            'label'=>$this->fieldDefinition->getTitle(),
            'data'=> ($this->oldValue ? $this->oldValue->getValue() : ''),
        ));

    }

    public function getName() {
        return 'tree';
    }

    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'CaseStoreBundle\Entity\OutputFieldValueText',
        );
    }

}


