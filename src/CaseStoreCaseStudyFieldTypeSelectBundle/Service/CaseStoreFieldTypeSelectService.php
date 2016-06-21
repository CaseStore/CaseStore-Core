<?php

namespace CaseStoreCaseStudyFieldTypeSelectBundle\Service;

use CaseStoreBundle\CaseStudyFieldTypeServiceInterface;
use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use Symfony\Component\HttpFoundation\Request;

class CaseStoreFieldTypeSelectService  implements CaseStudyFieldTypeServiceInterface {

    public function getId()
    {
        return 'select';
    }

    public function getTitle()
    {
        return 'Select';
    }

    /** @return boolean */
    public function hasSearchFilter()
    {
        return false;
    }


    public function getSearchFilterTemplatePath()
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
}
