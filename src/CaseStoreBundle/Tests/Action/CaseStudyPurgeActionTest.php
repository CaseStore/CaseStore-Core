<?php

namespace CaseStoreBundle\Tests\Action;



use CaseStoreBundle\Action\CaseStudyPurgeAction;
use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyHasUser;
use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Entity\User;
use CaseStoreBundle\Security\CaseStudyVoter;
use CaseStoreBundle\Tests\BaseTestWithDataBase;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyPurgeActionTest extends BaseTestWithDataBase
{


    function testPurge1() {

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

        $caseStudy = new CaseStudy();
        $caseStudy->setProject($project);
        $caseStudy->setPublicId('test');
        $this->em->persist($caseStudy);

        $this->em->flush();


        // TEST
        $caseStudy = $this->em->getRepository('CaseStoreBundle:CaseStudy')->findOneBy(array('project'=>$project, 'publicId'=>'test'));
        $this->assertNotNull($caseStudy);

        // ACTION
        $purgeAction = new CaseStudyPurgeAction($this->container);
        $purgeAction->purge($caseStudy);



        // TEST
        $caseStudy = $this->em->getRepository('CaseStoreBundle:CaseStudy')->findOneBy(array('project'=>$project, 'publicId'=>'test'));
        $this->assertNull($caseStudy);



    }

}
