<?php

namespace CaseStoreBundle\Tests\Repository\QueryBuilder;



use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use CaseStoreBundle\Entity\CaseStudyFieldValueString;
use CaseStoreBundle\Entity\CaseStudyFieldValueText;
use CaseStoreBundle\Entity\CaseStudyHasOutput;
use CaseStoreBundle\Entity\CaseStudyHasUser;
use CaseStoreBundle\Entity\Output;
use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Entity\User;
use CaseStoreBundle\Tests\BaseTestWithDataBase;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyQueryBuilderTest extends BaseTestWithDataBase {


    public function testNormal() {


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

        $caseStudy = new CaseStudy();
        $caseStudy->setPublicId('test');
        $caseStudy->setProject($project);
        $this->em->persist($caseStudy);

        $projectOther = new Project();
        $projectOther->setTitle('TestOther');
        $projectOther->setPublicId('testother');
        $projectOther->setOwner($user);
        $this->em->persist($projectOther);

        $caseStudyOther = new CaseStudy();
        $caseStudyOther->setPublicId('testother');
        $caseStudyOther->setProject($projectOther);
        $this->em->persist($caseStudyOther);

        $this->em->flush();

        // Test!

        $repo = $this->em->getRepository('CaseStoreBundle:CaseStudy');

        $caseStudies = $repo->getQueryBuilder($project)->getQuery()->getResult();

        $this->assertEquals(1, count($caseStudies));

    }


    public function testUserInProject() {


        // Build Data
        $user = new User();
        $user->setEmail("test@example.com");
        $user->setPassword("ouhosu");
        $this->em->persist($user);

        $userOther = new User();
        $userOther->setEmail("testOther@example.com");
        $userOther->setPassword("ouhosu");
        $this->em->persist($userOther);

        $project = new Project();
        $project->setTitle('Test');
        $project->setPublicId('test');
        $project->setOwner($user);
        $this->em->persist($project);

        $caseStudy = new CaseStudy();
        $caseStudy->setPublicId('test');
        $caseStudy->setProject($project);
        $this->em->persist($caseStudy);

        $caseStudyHasUser = new CaseStudyHasUser();
        $caseStudyHasUser->setUser($user);
        $caseStudyHasUser->setCaseStudy($caseStudy);
        $caseStudyHasUser->setAddedBy($user);
        $this->em->persist($caseStudyHasUser);

        $this->em->flush();

        // Test Find!

        $repo = $this->em->getRepository('CaseStoreBundle:CaseStudy');

        $caseStudiesQueryBuilder = $repo->getQueryBuilder($project);
        $caseStudiesQueryBuilder->setUserInProject($user);
        $caseStudies = $caseStudiesQueryBuilder->getQuery()->getResult();

        $this->assertEquals(1, count($caseStudies));

        // Test Not Found!

        $repo = $this->em->getRepository('CaseStoreBundle:CaseStudy');

        $caseStudiesQueryBuilder = $repo->getQueryBuilder($project);
        $caseStudiesQueryBuilder->setUserInProject($userOther);
        $caseStudies = $caseStudiesQueryBuilder->getQuery()->getResult();

        $this->assertEquals(0, count($caseStudies));

    }


    public function testOutput() {


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

        $caseStudy = new CaseStudy();
        $caseStudy->setPublicId('test');
        $caseStudy->setProject($project);
        $this->em->persist($caseStudy);

        $output = new Output();
        $output->setProject($project);
        $output->setPublicId('output');
        $this->em->persist($output);

        $caseStudyHasOutput = new CaseStudyHasOutput();
        $caseStudyHasOutput->setCaseStudy($caseStudy);
        $caseStudyHasOutput->setOutput($output);
        $caseStudyHasOutput->setAddedBy($user);
        $this->em->persist($caseStudyHasOutput);

        $outputOther = new Output();
        $outputOther->setProject($project);
        $outputOther->setPublicId('outputOther');
        $this->em->persist($outputOther);

        $this->em->flush();

        // Test Find!

        $repo = $this->em->getRepository('CaseStoreBundle:CaseStudy');

        $caseStudiesQueryBuilder = $repo->getQueryBuilder($project);
        $caseStudiesQueryBuilder->setOutput($output);
        $caseStudies = $caseStudiesQueryBuilder->getQuery()->getResult();

        $this->assertEquals(1, count($caseStudies));

        // Test Not Found!

        $repo = $this->em->getRepository('CaseStoreBundle:CaseStudy');

        $caseStudiesQueryBuilder = $repo->getQueryBuilder($project);
        $caseStudiesQueryBuilder->setOutput($outputOther);
        $caseStudies = $caseStudiesQueryBuilder->getQuery()->getResult();

        $this->assertEquals(0, count($caseStudies));

    }



}
