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
            ' JOIN cs.fieldValueSelectCache csfvsc ',
        );
    }

    public function getQueryBuilderWheres()
    {
        return array(
            ' csfvsc.fieldDefinition = :csfvscFieldDef AND csfvsc.option = :csfvscValue '
        );
    }

    public function getQueryBuilderParams()
    {
        return array(
            'csfvscFieldDef' => $this->fieldDefinition,
            'csfvscValue' =>  $this->value,
        );
    }

}
