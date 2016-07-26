<?php

namespace CaseStoreCaseStudyFieldTypeSelectBundle\Service;

use CaseStoreBundle\CaseStudyFieldTypeSearchFilterTemplateInfo;
use CaseStoreBundle\CaseStudyFieldTypeServiceInterface;
use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use CaseStoreCaseStudyFieldTypeSelectBundle\CaseStudyQueryBuilderFieldTypeSelectSearch;
use CaseStoreCaseStudyFieldTypeSelectBundle\Entity\CaseStudyFieldValueSelectCache;
use Symfony\Component\HttpFoundation\Request;

class CaseStoreFieldTypeSelectService  implements CaseStudyFieldTypeServiceInterface {

    protected  $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

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
        return true;
    }


    public function getSearchFilterTemplateInfo(CaseStudyFieldDefinition $fieldDefinition)
    {
        $doctrine = $this->container->get('doctrine');

        $values = $doctrine->
            getRepository('CaseStoreBundle:CaseStudyFieldDefinitionOption')->
            getCurrentForFieldDefinition($fieldDefinition);

        return new CaseStudyFieldTypeSearchFilterTemplateInfo('CaseStoreCaseStudyFieldTypeSelectBundle::caseStudyFieldTypeSelectSearch.html.twig', array(
            'options'=>$values,
        ));
    }

    public function getFieldSearchFromSearchFilter(CaseStudyFieldDefinition $fieldDefinition, Request $request)
    {
        $data = trim($request->get('field'.$fieldDefinition->getPublicId()));
        if ($data) {
            $doctrine = $this->container->get('doctrine');
            $value = $doctrine->
                getRepository('CaseStoreBundle:CaseStudyFieldDefinitionOption')->
                findOneBy(array('fieldDefinition'=>$fieldDefinition, 'publicId'=>$data));
            if ($value) {
                return new CaseStudyQueryBuilderFieldTypeSelectSearch($fieldDefinition, $value);
            }
        }
    }



    public function updateCaches(CaseStudyFieldDefinition $fieldDefinition, CaseStudy $caseStudy = null) {

        $doctrine = $this->container->get('doctrine');

        if (!$caseStudy) {
            foreach($doctrine->getRepository('CaseStoreBundle:CaseStudy')->findBy(array('project'=>$fieldDefinition->getProject())) as $caseStudy) {
                $this->updateCaches($fieldDefinition, $caseStudy);
            }
        } else {

            $currentValues = array();

            // Step 1: Loop thought each current value and make sure there is a cache item for it.

            foreach($doctrine->
                getRepository('CaseStoreCaseStudyFieldTypeSelectBundle:CaseStudyFieldValueSelect')->
                getLatestValuesFor($fieldDefinition, $caseStudy) as $selectValue) {


                $currentValues[] = $selectValue->getOption()->getId();

                $cache = $doctrine
                    ->getRepository('CaseStoreCaseStudyFieldTypeSelectBundle:CaseStudyFieldValueSelectCache')
                    ->findOneBy(array('caseStudy' => $caseStudy, 'fieldDefinition' => $fieldDefinition , 'option' => $selectValue->getOption()));
                if (!$cache) {
                    $cache = new CaseStudyFieldValueSelectCache();
                    $cache->setFieldDefinition($fieldDefinition);
                    $cache->setCaseStudy($caseStudy);
                    $cache->setOption($selectValue->getOption());
                    $doctrine->getManager()->persist($cache);
                    $doctrine->getManager()->flush($cache);
                }

            }

            // Step 2: Loop through each item cached, and if it's not current, delete it.

            foreach($doctrine->
                getRepository('CaseStoreCaseStudyFieldTypeSelectBundle:CaseStudyFieldValueSelectCache')->
                findBy(array('caseStudy'=>$caseStudy, 'fieldDefinition'=>$fieldDefinition)) as $cachedValue) {

                if (!in_array($cachedValue->getOption()->getId(), $currentValues)) {
                    $doctrine->getManager()->remove($cachedValue);
                    $doctrine->getManager()->flush($cachedValue);
                }

            }
        }


    }

    public function getLatestValue(CaseStudyFieldDefinition $fieldDefinition, CaseStudy $caseStudy)
    {
        // TODO: Implement getLatestValue() method.
    }

    public function getLatestValues(CaseStudyFieldDefinition $fieldDefinition, CaseStudy $caseStudy)
    {

    }

    /** @return boolean */
    public function hasAValue(CaseStudyFieldDefinition $fieldDefinition, CaseStudy $caseStudy)
    {
        // TODO: Implement hasAValue() method.
    }
}
