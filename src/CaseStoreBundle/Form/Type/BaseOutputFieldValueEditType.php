<?php

namespace CaseStoreBundle\Form\Type;

use CaseStoreBundle\Entity\OutputFieldDefinition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackValidator;



/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
abstract class BaseOutputFieldValueEditType extends AbstractType
{

    /** @var  OutputFieldDefinition */
    protected $fieldDefinition;

    protected $oldValue;

    /**
     * BaseCaseStudyFieldValueEditType constructor.
     * @param OutputFieldDefinition $fieldDefinition
     */
    public function __construct(OutputFieldDefinition $fieldDefinition, $oldValue)
    {
        $this->fieldDefinition = $fieldDefinition;
        $this->oldValue = $oldValue;
    }


}

