<?php



namespace CaseStoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormBuilderInterface;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyDocumentNewType extends AbstractType {


    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('title', 'text', array(
            'required' => true,
            'label'=>'Title'
        ));

        $builder->add('file', 'file', array(
            'required' => true,
            'label'=>'Document'
        ));

        $builder->add('isCaseStudyUsersOnly', 'checkbox', array(
            'required' => false,
            'label'=>'Is for Involved Staff Only?'
        ));


    }

    public function getName() {
        return 'node';
    }

    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'CaseStoreBundle\Entity\CaseStudyDocument',
        );
    }
}
