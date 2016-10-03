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
class UserRegisterType extends AbstractType {





    public function buildForm(FormBuilderInterface $builder, array $options) {



        $builder->add('email', 'email', array(
            'required' => true,
            'label'=>'Email'
        ));

        $builder->add('password_1', 'password', array(
            'required' => true,
            'label'=>'Password',
            'mapped'=>false,
        ));

        $builder->add('password_2', 'password', array(
            'required' => true,
            'label'=>'Repeat Password',
            'mapped'=>false,
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
