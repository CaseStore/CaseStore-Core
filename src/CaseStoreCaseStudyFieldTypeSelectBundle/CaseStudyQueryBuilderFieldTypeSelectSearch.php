<?php

namespace CaseStoreCaseStudyFieldTypeSelectBundle;

use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use CaseStoreBundle\Entity\CaseStudyFieldDefinitionOption;
use CaseStoreBundle\Repository\QueryBuilder\CaseStudyQueryBuilderFieldSearchInterface;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyQueryBuilderFieldTypeSelectSearch implements CaseStudyQueryBuilderFieldSearchInterface {

    /**
     * @var CaseStudyFieldDefinition
     */
    protected $fieldDefinition;

    /**
     * @var CaseStudyFieldDefinitionOption
     */
    protected $value;

    /**
     * CaseStudyQueryBuilderFieldSearch constructor.
     * @param $value
     */
    public function __construct(CaseStudyFieldDefinition $fieldDefinition, CaseStudyFieldDefinitionOption $value)
    {
        $this->fieldDefinition = $fieldDefinition;
        $this->value = $value;
    }

    /**
     * @return CaseStudyFieldDefinitionOption
     */
    public function getValue()
    {
        return $this->value;
    }


    public function getQueryBuilderJoins()
    {
        return array(
            ' JOIN cs.fieldValueSelectCache csfvsc'.$this->fieldDefinition->getId().' ',
        );
    }

    public function getQueryBuilderWheres()
    {
        return array(
            ' csfvsc'.$this->fieldDefinition->getId().'.fieldDefinition = :csfvsc'.$this->fieldDefinition->getId().'FieldDef '.
            'AND csfvsc'.$this->fieldDefinition->getId().'.option = :csfvsc'.$this->fieldDefinition->getId().'Value '
        );
    }

    public function getQueryBuilderParams()
    {
        return array(
            'csfvsc'.$this->fieldDefinition->getId().'FieldDef' => $this->fieldDefinition,
            'csfvsc'.$this->fieldDefinition->getId().'Value' =>  $this->value,
        );
    }

}
