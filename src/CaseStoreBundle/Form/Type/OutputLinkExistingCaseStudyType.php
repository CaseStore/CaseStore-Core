<?php

namespace CaseStoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormBuilderInterface;



/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class OutputLinkExistingCaseStudyType extends AbstractType {




    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('public_id', 'text', array(
            'required' => true,
            'label'=>'ID',
        ));

    }

    public function getName() {
        return 'tree';
    }

    public function getDefaultOptions(array $options) {
        return array(
        );
    }

}

