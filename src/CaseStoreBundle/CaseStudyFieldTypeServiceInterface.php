<?php

namespace CaseStoreBundle;
use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use Symfony\Component\HttpFoundation\Request;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */

interface CaseStudyFieldTypeServiceInterface
{

    public function getId();

    public function getTitle();

    /** @return boolean */
    public function hasSearchFilter();

    /** @return CaseStudyFieldTypeSearchFilterTemplateInfoInterface */
    public function getSearchFilterTemplateInfo(CaseStudyFieldDefinition $fieldDefinition);

    public function getFieldSearchFromSearchFilter(CaseStudyFieldDefinition $fieldDefinition, Request $request);

    public function getLatestValue(CaseStudyFieldDefinition $fieldDefinition, CaseStudy $caseStudy);

    public function updateCaches(CaseStudyFieldDefinition $fieldDefinition, CaseStudy $caseStudy = null);

}
