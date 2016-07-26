<?php

namespace CaseStoreCaseStudyFieldTypeTextBundle\Service;

use CaseStoreBundle\CaseStudyFieldTypeSearchFilterTemplateInfo;
use CaseStoreBundle\CaseStudyFieldTypeServiceInterface;
use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use CaseStoreCaseStudyFieldTypeTextBundle\CaseStudyQueryBuilderFieldTypeTextSearch;
use CaseStoreCaseStudyFieldTypeTextBundle\Entity\CaseStudyFieldValueTextCache;
use Symfony\Component\HttpFoundation\Request;

class CaseStoreFieldTypeTextService implements CaseStudyFieldTypeServiceInterface {

    protected  $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getId()
    {
        return 'text';
    }

    public function getTitle()
    {
        return 'Text';
    }

    /** @return boolean */
    public function hasSearchFilter()
    {
        return true;
    }


    public function getSearchFilterTemplateInfo(CaseStudyFieldDefinition $fieldDefinition)
    {
        return new CaseStudyFieldTypeSearchFilterTemplateInfo('CaseStoreCaseStudyFieldTypeTextBundle::caseStudyFieldTypeTextSearch.html.twig');
    }

    public function getFieldSearchFromSearchFilter(CaseStudyFieldDefinition $fieldDefinition, Request $request)
    {
        $data = trim($request->get('field'.$fieldDefinition->getPublicId()));
        if ($data) {
            return new CaseStudyQueryBuilderFieldTypeTextSearch($fieldDefinition, $data);
        }
    }

    public function updateCaches(CaseStudyFieldDefinition $fieldDefinition, CaseStudy $caseStudy = null) {

        $doctrine = $this->container->get('doctrine');

        if (!$caseStudy) {
            foreach($doctrine->getRepository('CaseStoreBundle:CaseStudy')->findBy(array('project'=>$fieldDefinition->getProject())) as $caseStudy) {
                $this->updateCaches($fieldDefinition, $caseStudy);
            }
        } else {
            $cache = $doctrine
                ->getRepository('CaseStoreCaseStudyFieldTypeTextBundle:CaseStudyFieldValueTextCache')
                ->findOneBy(array('caseStudy'=>$caseStudy, 'fieldDefinition'=>$fieldDefinition));
            if (!$cache) {
                $cache = new CaseStudyFieldValueTextCache();
                $cache->setFieldDefinition($fieldDefinition);
                $cache->setCaseStudy($caseStudy);
            }
            $latestValue = $this->getLatestValue($fieldDefinition, $caseStudy);
            if ($latestValue) {
                $cache->setValue($latestValue->getValue());
            } else {
                $cache->setValue(null);
            }
            $doctrine->getManager()->persist($cache);
            $doctrine->getManager()->flush($cache);
        }

    }


    public function getLatestValue(CaseStudyFieldDefinition $fieldDefinition, CaseStudy $caseStudy)
    {
        $doctrine = $this->container->get('doctrine');
        return $doctrine->
        getRepository('CaseStoreCaseStudyFieldTypeTextBundle:CaseStudyFieldValueText')->
        getLatestValueFor($fieldDefinition, $caseStudy);
    }

    /** @return boolean */
    public function hasAValue(CaseStudyFieldDefinition $fieldDefinition, CaseStudy $caseStudy)
    {
        $latestValue = $this->getLatestValue($fieldDefinition, $caseStudy);
        return $latestValue && $latestValue->getValue();
    }

}
