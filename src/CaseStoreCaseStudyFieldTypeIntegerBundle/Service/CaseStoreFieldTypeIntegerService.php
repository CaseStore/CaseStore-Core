<?php

namespace CaseStoreCaseStudyFieldTypeIntegerBundle\Service;


use CaseStoreBundle\CaseStudyFieldTypeServiceInterface;
use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use Symfony\Component\HttpFoundation\Request;

class CaseStoreFieldTypeIntegerService  implements CaseStudyFieldTypeServiceInterface {

    public function getId()
    {
        return 'integer';
    }

    public function getTitle()
    {
        return 'Integer';
    }

    /** @return boolean */
    public function hasSearchFilter()
    {
        return false;
    }

    public function getSearchFilterTemplateInfo(CaseStudyFieldDefinition $fieldDefinition)
    {
    }

    public function getFieldSearchFromSearchFilter(CaseStudyFieldDefinition $fieldDefinition, Request $request)
    {
    }


    public function updateCaches(CaseStudyFieldDefinition $fieldDefinition, CaseStudy $caseStudy = null) {
    }

    public function getLatestValue(CaseStudyFieldDefinition $fieldDefinition, CaseStudy $caseStudy)
    {
        // TODO: Implement getLatestValue() method.
    }

    /** @return boolean */
    public function hasAValue(CaseStudyFieldDefinition $fieldDefinition, CaseStudy $caseStudy)
    {
        // TODO: Implement hasAValue() method.
    }
}
