<?php

namespace CaseStoreCaseStudyFieldTypeStringBundle\Tests;


use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Entity\User;
use CaseStoreBundle\Tests\BaseTestWithDataBase;
use CaseStoreCaseStudyFieldTypeStringBundle\CaseStudyQueryBuilderFieldTypeStringSearch;
use CaseStoreCaseStudyFieldTypeStringBundle\Entity\CaseStudyFieldValueString;
use CaseStoreCaseStudyFieldTypeStringBundle\Service\CaseStoreFieldTypeStringService;


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
        $caseStudyFieldDefinition->setType('string');
        $caseStudyFieldDefinition->setPublicId('title');
        $caseStudyFieldDefinition->setTitle('Title');
        $caseStudyFieldDefinition->setSort(10);
        $caseStudyFieldDefinition->setAddedBy($user);
        $this->em->persist($caseStudyFieldDefinition);

        $caseStudy = new CaseStudy();
        $caseStudy->setPublicId('test');
        $caseStudy->setProject($project);
        $this->em->persist($caseStudy);

        $value = new CaseStudyFieldValueString();
        $value->setFieldDefinition($caseStudyFieldDefinition);
        $value->setCaseStudy($caseStudy);
        $value->setAddedBy($user);
        $value->setValue('Cat');
        $value->setAddedAt($user);
        $this->em->persist($value);

        $this->em->flush();

        // Test: get a case study

        $repo = $this->em->getRepository('CaseStoreBundle:CaseStudy');

        $queryBuilder = $repo->getQueryBuilder($project);

        $caseStudies = $queryBuilder->getQuery()->getResult();

        $this->assertEquals(1, count($caseStudies));

        // Test: search for field, which should fail because cache not updated.

        $repo = $this->em->getRepository('CaseStoreBundle:CaseStudy');

        $queryBuilder = $repo->getQueryBuilder($project);
        $queryBuilder->addFieldSearch(new CaseStudyQueryBuilderFieldTypeStringSearch($caseStudyFieldDefinition, 'cat'));

        $caseStudies = $queryBuilder->getQuery()->getResult();

        $this->assertEquals(0, count($caseStudies));


        // Test: search for something else

        $repo = $this->em->getRepository('CaseStoreBundle:CaseStudy');

        $queryBuilder = $repo->getQueryBuilder($project);
        $queryBuilder->addFieldSearch(new CaseStudyQueryBuilderFieldTypeStringSearch($caseStudyFieldDefinition, 'dog'));

        $caseStudies = $queryBuilder->getQuery()->getResult();

        $this->assertEquals(0, count($caseStudies));


        // update cache

        $this->container->get('case_store_case_study_field_type_string.type')->updateCaches($caseStudyFieldDefinition, $caseStudy);


        // Test: search for field, which should now pass.

        $repo = $this->em->getRepository('CaseStoreBundle:CaseStudy');

        $queryBuilder = $repo->getQueryBuilder($project);
        $queryBuilder->addFieldSearch(new CaseStudyQueryBuilderFieldTypeStringSearch($caseStudyFieldDefinition, 'cat'));

        $caseStudies = $queryBuilder->getQuery()->getResult();

        $this->assertEquals(1, count($caseStudies));

        // Test: search for something else

        $repo = $this->em->getRepository('CaseStoreBundle:CaseStudy');

        $queryBuilder = $repo->getQueryBuilder($project);
        $queryBuilder->addFieldSearch(new CaseStudyQueryBuilderFieldTypeStringSearch($caseStudyFieldDefinition, 'dog'));

        $caseStudies = $queryBuilder->getQuery()->getResult();

        $this->assertEquals(0, count($caseStudies));




    }

}
