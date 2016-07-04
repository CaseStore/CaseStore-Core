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
            ' JOIN cs.fieldValueTextCache csfvsc'.$this->fieldDefinition->getId().' ',
        );
    }

    public function getQueryBuilderWheres()
    {
        return array(
            ' csfvsc'.$this->fieldDefinition->getId().'.fieldDefinition = :csfvsc'.$this->fieldDefinition->getId().'FieldDef '.
            'AND csfvsc'.$this->fieldDefinition->getId().'.value LIKE :csfvsc'.$this->fieldDefinition->getId().'Value '
        );
    }

    public function getQueryBuilderParams()
    {
        return array(
            'csfvsc'.$this->fieldDefinition->getId().'FieldDef' => $this->fieldDefinition,
            'csfvsc'.$this->fieldDefinition->getId().'Value' => '%' . $this->value . '%',
        );
    }

}
