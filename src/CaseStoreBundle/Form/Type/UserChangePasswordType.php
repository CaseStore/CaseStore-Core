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
class UserChangePasswordType extends AbstractType {





    public function buildForm(FormBuilderInterface $builder, array $options) {



        $builder->add('old_password', 'password', array(
            'required' => true,
            'label'=>'Old Password'
        ));

        $builder->add('new_password_1', 'password', array(
            'required' => true,
            'label'=>'New Password'
        ));
        $builder->add('new_password_2', 'password', array(
            'required' => true,
            'label'=>'Repeat New Password'
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
