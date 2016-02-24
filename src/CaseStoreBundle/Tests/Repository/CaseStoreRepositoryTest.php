<?php

namespace CaseStoreBundle\Tests\Repository;



use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use CaseStoreBundle\Entity\CaseStudyFieldValueString;
use CaseStoreBundle\Entity\CaseStudyFieldValueText;
use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Entity\User;
use CaseStoreBundle\Tests\BaseTestWithDataBase;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class NodeRepositoryTest extends BaseTestWithDataBase {


    public function testCache() {


        // Build Data
        $user = new User();
        $user->setEmail("test@example.com");
        $user->setUsername("test");
        $user->setPassword("ouhosu");
        $this->em->persist($user);

        $project = new Project();
        $project->setTitle('Test');
        $project->setPublicId('test');
        $project->setOwner($user);
        $this->em->persist($project);

        $fieldDefTitle = new CaseStudyFieldDefinition();
        $fieldDefTitle->setProject($project);
        $fieldDefTitle->setAddedBy($user);
        $fieldDefTitle->setSort(0);
        $fieldDefTitle->setType('string');
        $fieldDefTitle->setTitle('Title');
        $fieldDefTitle->setPublicId('title');
        $this->em->persist($fieldDefTitle);

        $fieldDefDescription = new CaseStudyFieldDefinition();
        $fieldDefDescription->setProject($project);
        $fieldDefDescription->setAddedBy($user);
        $fieldDefDescription->setSort(1);
        $fieldDefDescription->setType('text');
        $fieldDefDescription->setTitle('Description');
        $fieldDefDescription->setPublicId('description');
        $this->em->persist($fieldDefDescription);

        $caseStudy = new CaseStudy();
        $caseStudy->setPublicId('test');
        $caseStudy->setProject($project);
        $this->em->persist($caseStudy);

        $fieldValueTitle = new CaseStudyFieldValueString();
        $fieldValueTitle->setFieldDefinition($fieldDefTitle);
        $fieldValueTitle->setCaseStudy($caseStudy);
        $fieldValueTitle->setAddedBy($user);
        $fieldValueTitle->setValue('Title Title');
        $this->em->persist($fieldValueTitle);

        $fieldValueDescription = new CaseStudyFieldValueText();
        $fieldValueDescription->setFieldDefinition($fieldDefDescription);
        $fieldValueDescription->setCaseStudy($caseStudy);
        $fieldValueDescription->setAddedBy($user);
        $fieldValueDescription->setValue("Description\nCats");
        $this->em->persist($fieldValueDescription);


        $this->em->flush();

        // Call Cache

        $repo = $this->em->getRepository('CaseStoreBundle:CaseStudy');

        $caseStudy = $repo->findOneBy(array('publicId'=>'test'));

        $repo->updateCaches($caseStudy);


        // Test results
        $caseStudy = $repo->findOneBy(array('publicId'=>'test'));

        $this->assertEquals('Title Title', $caseStudy->getTitle());
        $this->assertEquals("Description\nCats", $caseStudy->getDescription());

    }



}
