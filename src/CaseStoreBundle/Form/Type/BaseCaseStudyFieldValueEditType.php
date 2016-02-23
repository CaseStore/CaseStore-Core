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
abstract class BaseCaseStudyFieldValueEditType extends AbstractType
{

    /** @var  CaseStudyFieldDefinition */
    protected $fieldDefinition;

    protected $oldValue;

    /**
     * BaseCaseStudyFieldValueEditType constructor.
     * @param CaseStudyFieldDefinition $fieldDefinition
     */
    public function __construct(CaseStudyFieldDefinition $fieldDefinition, $oldValue)
    {
        $this->fieldDefinition = $fieldDefinition;
        $this->oldValue = $oldValue;
    }


}

