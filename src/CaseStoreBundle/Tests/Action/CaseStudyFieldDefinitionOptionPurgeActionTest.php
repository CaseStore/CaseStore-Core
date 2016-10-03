<?php

namespace CaseStoreBundle\Tests\Action;



use CaseStoreBundle\Action\CaseStudyFieldDefinitionOptionPurgeAction;
use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use CaseStoreBundle\Entity\CaseStudyFieldDefinitionOption;
use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Entity\User;
use CaseStoreBundle\Tests\BaseTestWithDataBase;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyFieldDefinitionOptionPurgeActionTest extends BaseTestWithDataBase
{


    function testPurge1() {

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
        $caseStudyFieldDefinition->setTitle('cats');
        $caseStudyFieldDefinition->setPublicId('cats');
        $caseStudyFieldDefinition->setType('select');
        $caseStudyFieldDefinition->setSort(10);
        $caseStudyFieldDefinition->setAddedBy($user);
        $this->em->persist($caseStudyFieldDefinition);

        $caseStudyFieldDefinitionOption = new CaseStudyFieldDefinitionOption();
        $caseStudyFieldDefinitionOption->setTitle('One');
        $caseStudyFieldDefinitionOption->setPublicId('one');
        $caseStudyFieldDefinitionOption->setFieldDefinition($caseStudyFieldDefinition);
        $caseStudyFieldDefinitionOption->setSort(10);
        $caseStudyFieldDefinitionOption->setAddedBy($user);
        $this->em->persist($caseStudyFieldDefinitionOption);

        $this->em->flush();

        // TEST
        $caseStudyFieldDefinition = $this->em->getRepository('CaseStoreBundle:CaseStudyFieldDefinitionOption')->findOneBy(array('publicId'=>'one'));
        $this->assertNotNull($caseStudyFieldDefinition);

        // ACTION
        $purgeAction = new CaseStudyFieldDefinitionOptionPurgeAction($this->container);
        $purgeAction->purge($caseStudyFieldDefinition);

        // TEST
        $caseStudyFieldDefinition = $this->em->getRepository('CaseStoreBundle:CaseStudyFieldDefinitionOption')->findOneBy(array('publicId'=>'one'));
        $this->assertNull($caseStudyFieldDefinition);


    }

}
