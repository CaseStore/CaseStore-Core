<?php

namespace CaseStoreCaseStudyFieldTypeTextBundle;

use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use CaseStoreBundle\Repository\QueryBuilder\CaseStudyQueryBuilderFieldSearchInterface;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyQueryBuilderFieldTypeTextSearch implements CaseStudyQueryBuilderFieldSearchInterface {

    /**
     * @var CaseStudyFieldDefinition
     */
    protected $fieldDefinition;

    protected $value;

    /**
     * CaseStudyQueryBuilderFieldSearch constructor.
     * @param $value
     */
    public function __construct(CaseStudyFieldDefinition $fieldDefinition, $value)
    {
        $this->fieldDefinition = $fieldDefinition;
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }


    public function getQueryBuilderJoins()
    {
        return array(
            ' JOIN cs.fieldValueTextCache csfvsc ',
        );
    }

    public function getQueryBuilderWheres()
    {
        return array(
            ' csfvsc.fieldDefinition = :csfvscFieldDef AND csfvsc.value LIKE :csfvscValue '
        );
    }

    public function getQueryBuilderParams()
    {
        return array(
            'csfvscFieldDef' => $this->fieldDefinition,
            'csfvscValue' => '%' . $this->value . '%',
        );
    }

}
