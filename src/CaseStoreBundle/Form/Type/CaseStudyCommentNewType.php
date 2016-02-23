<?php

namespace CaseStoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormBuilderInterface;



/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyCommentNewType extends AbstractType {


    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('body', 'textarea', array(
            'required' => true,
            'label'=>'Comment'
        ));

    }

    public function getName() {
        return 'tree';
    }

    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'CaseStoreBundle\Entity\CaseStudyComment',
        );
    }

}
