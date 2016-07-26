<?php

namespace CaseStoreCaseStudyFieldTypeStringBundle\Service;

use CaseStoreBundle\CaseStudyFieldTypeSearchFilterTemplateInfo;
use CaseStoreBundle\CaseStudyFieldTypeServiceInterface;
use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use CaseStoreCaseStudyFieldTypeStringBundle\CaseStudyQueryBuilderFieldTypeStringSearch;
use CaseStoreCaseStudyFieldTypeStringBundle\Entity\CaseStudyFieldValueStringCache;
use Symfony\Component\HttpFoundation\Request;

class CaseStoreFieldTypeStringService  implements CaseStudyFieldTypeServiceInterface {

    protected  $container;

    public function __construct($container)
    {
        $this->container = $container;
    }


    public function getId()
    {
        return 'string';
    }

    public function getTitle()
    {
        return 'String';
    }

    /** @return boolean */
    public function hasSearchFilter()
    {
        return true;
    }


    public function getSearchFilterTemplateInfo(CaseStudyFieldDefinition $fieldDefinition)
    {
        return  new CaseStudyFieldTypeSearchFilterTemplateInfo('CaseStoreCaseStudyFieldTypeStringBundle::caseStudyFieldTypeStringSearch.html.twig');
    }

    public function getFieldSearchFromSearchFilter(CaseStudyFieldDefinition $fieldDefinition, Request $request)
    {
        $data = trim($request->get('field'.$fieldDefinition->getPublicId()));
        if ($data) {
            return new CaseStudyQueryBuilderFieldTypeStringSearch($fieldDefinition, $data);
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
                ->getRepository('CaseStoreCaseStudyFieldTypeStringBundle:CaseStudyFieldValueStringCache')
                ->findOneBy(array('caseStudy'=>$caseStudy, 'fieldDefinition'=>$fieldDefinition));
            if (!$cache) {
                $cache = new CaseStudyFieldValueStringCache();
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
            getRepository('CaseStoreCaseStudyFieldTypeStringBundle:CaseStudyFieldValueString')->
            getLatestValueFor($fieldDefinition, $caseStudy);
    }

    /** @return boolean */
    public function hasAValue(CaseStudyFieldDefinition $fieldDefinition, CaseStudy $caseStudy)
    {
        // TODO: Implement hasAValue() method.
    }
}
