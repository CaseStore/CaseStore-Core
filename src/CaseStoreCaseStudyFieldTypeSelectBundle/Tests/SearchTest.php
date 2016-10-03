<?php

namespace CaseStoreCaseStudyFieldTypeSelectBundle\Tests;


use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use CaseStoreBundle\Entity\CaseStudyFieldDefinitionOption;
use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Entity\User;
use CaseStoreBundle\Tests\BaseTestWithDataBase;
use CaseStoreCaseStudyFieldTypeSelectBundle\CaseStudyQueryBuilderFieldTypeSelectSearch;
use CaseStoreCaseStudyFieldTypeSelectBundle\Entity\CaseStudyFieldValueSelect;
use CaseStoreCaseStudyFieldTypeSelectBundle\Service\CaseStoreFieldTypeSelectService;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyPurgeActionTest extends BaseTestWithDataBase
{

    function testSearch() {

        // Build Data
        $user = new User();
        $user->setEmail("test@example.com");
        $user->setPassword("ouhosu");
        $this->em->persist($user);

        $project = new Project();
        $project->setTitle('Test');
        $project->setPublicId('test');
        $project->setOwner($user);
        $this->em->persist($project);

        $caseStudyFieldDefinition = new CaseStudyFieldDefinition();
        $caseStudyFieldDefinition->setProject($project);
        $caseStudyFieldDefinition->setType('select');
        $caseStudyFieldDefinition->setPublicId('title');
        $caseStudyFieldDefinition->setTitle('Title');
        $caseStudyFieldDefinition->setSort(10);
        $caseStudyFieldDefinition->setAddedBy($user);
        $this->em->persist($caseStudyFieldDefinition);

        $caseStudyFieldDefinitionOption1 = new CaseStudyFieldDefinitionOption();
        $caseStudyFieldDefinitionOption1->setFieldDefinition($caseStudyFieldDefinition);
        $caseStudyFieldDefinitionOption1->setPublicId('1');
        $caseStudyFieldDefinitionOption1->setTitle('1');
        $caseStudyFieldDefinitionOption1->setAddedBy($user);
        $caseStudyFieldDefinitionOption1->setSort(10);
        $this->em->persist($caseStudyFieldDefinitionOption1);

        $caseStudyFieldDefinitionOption2 = new CaseStudyFieldDefinitionOption();
        $caseStudyFieldDefinitionOption2->setFieldDefinition($caseStudyFieldDefinition);
        $caseStudyFieldDefinitionOption2->setPublicId('2');
        $caseStudyFieldDefinitionOption2->setTitle('2');
        $caseStudyFieldDefinitionOption2->setAddedBy($user);
        $caseStudyFieldDefinitionOption2->setSort(20);
        $this->em->persist($caseStudyFieldDefinitionOption2);

        $caseStudy = new CaseStudy();
        $caseStudy->setPublicId('test');
        $caseStudy->setProject($project);
        $this->em->persist($caseStudy);

        $this->em->flush();

        // Test: get a case study

        $repo = $this->em->getRepository('CaseStoreBundle:CaseStudy');

        $queryBuilder = $repo->getQueryBuilder($project);

        $caseStudies = $queryBuilder->getQuery()->getResult();

        $this->assertEquals(1, count($caseStudies));

        // Set option 1 only!

        $this->em->getRepository('CaseStoreCaseStudyFieldTypeSelectBundle:CaseStudyFieldValueSelect')
            ->addOptionToCaseStudyField($caseStudyFieldDefinitionOption1, $caseStudy, $user);
        $this->em->getRepository('CaseStoreCaseStudyFieldTypeSelectBundle:CaseStudyFieldValueSelect')
            ->removeOptionFromCaseStudyField($caseStudyFieldDefinitionOption2, $caseStudy, $user);
        $this->em->flush();

        // update cache

        $this->container->get('case_store_case_study_field_type_select.type')->updateCaches($caseStudyFieldDefinition, $caseStudy);


        // Test: search for field with option 1

        $repo = $this->em->getRepository('CaseStoreBundle:CaseStudy');

        $queryBuilder = $repo->getQueryBuilder($project);
        $queryBuilder->addFieldSearch(new CaseStudyQueryBuilderFieldTypeSelectSearch($caseStudyFieldDefinition, $caseStudyFieldDefinitionOption1));

        $caseStudies = $queryBuilder->getQuery()->getResult();

        $this->assertEquals(1, count($caseStudies));

        // Test: search for field with option 2

        $repo = $this->em->getRepository('CaseStoreBundle:CaseStudy');

        $queryBuilder = $repo->getQueryBuilder($project);
        $queryBuilder->addFieldSearch(new CaseStudyQueryBuilderFieldTypeSelectSearch($caseStudyFieldDefinition, $caseStudyFieldDefinitionOption2));

        $caseStudies = $queryBuilder->getQuery()->getResult();

        $this->assertEquals(0, count($caseStudies));


        // Set option 2 only!

        $this->em->getRepository('CaseStoreCaseStudyFieldTypeSelectBundle:CaseStudyFieldValueSelect')
            ->removeOptionFromCaseStudyField($caseStudyFieldDefinitionOption1, $caseStudy, $user);
        $this->em->getRepository('CaseStoreCaseStudyFieldTypeSelectBundle:CaseStudyFieldValueSelect')
            ->addOptionToCaseStudyField($caseStudyFieldDefinitionOption2, $caseStudy, $user);
        $this->em->flush();

        // update cache

        $this->container->get('case_store_case_study_field_type_select.type')->updateCaches($caseStudyFieldDefinition, $caseStudy);


        // Test: search for field with option 1

        $repo = $this->em->getRepository('CaseStoreBundle:CaseStudy');

        $queryBuilder = $repo->getQueryBuilder($project);
        $queryBuilder->addFieldSearch(new CaseStudyQueryBuilderFieldTypeSelectSearch($caseStudyFieldDefinition, $caseStudyFieldDefinitionOption1));

        $caseStudies = $queryBuilder->getQuery()->getResult();

        $this->assertEquals(0, count($caseStudies));

        // Test: search for field with option 2

        $repo = $this->em->getRepository('CaseStoreBundle:CaseStudy');

        $queryBuilder = $repo->getQueryBuilder($project);
        $queryBuilder->addFieldSearch(new CaseStudyQueryBuilderFieldTypeSelectSearch($caseStudyFieldDefinition, $caseStudyFieldDefinitionOption2));

        $caseStudies = $queryBuilder->getQuery()->getResult();

        $this->assertEquals(1, count($caseStudies));




    }

}
