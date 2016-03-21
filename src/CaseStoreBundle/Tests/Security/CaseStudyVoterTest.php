<?php

namespace CaseStoreBundle\Tests\Security;



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
class CaseStudyVoterTest extends BaseTestWithDataBase {


    public function testEditByAnon() {

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

        // Test!
        $projectVoter = new CaseStudyVoter($this->em);

        $token = new TokenInterfaceForTesting();
        $token->setUser(null);

        $this->assertEquals(VoterInterface::ACCESS_DENIED , $projectVoter->vote($token, $caseStudy, array(CaseStudyVoter::EDIT)));

    }


    public function testEditByProjectOwner() {

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

        // Test!
        $projectVoter = new CaseStudyVoter($this->em);

        $token = new TokenInterfaceForTesting();
        $token->setUser($user);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED , $projectVoter->vote($token, $caseStudy, array(CaseStudyVoter::EDIT)));

    }


    public function testEditByRandomUser() {

        // Build Data
        $user = new User();
        $user->setEmail("test@example.com");
        $user->setUsername("test");
        $user->setPassword("ouhosu");
        $this->em->persist($user);

        $userRandom = new User();
        $userRandom->setEmail("test1@example.com");
        $userRandom->setUsername("test1");
        $userRandom->setPassword("ouhosu");
        $this->em->persist($userRandom);

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

        // Test!
        $projectVoter = new CaseStudyVoter($this->em);

        $token = new TokenInterfaceForTesting();
        $token->setUser($userRandom);

        $this->assertEquals(VoterInterface::ACCESS_DENIED , $projectVoter->vote($token, $caseStudy, array(CaseStudyVoter::EDIT)));

    }


    public function testEditByStaffInvolved() {

        // Build Data
        $user = new User();
        $user->setEmail("test@example.com");
        $user->setUsername("test");
        $user->setPassword("ouhosu");
        $this->em->persist($user);

        $userStaffInvolved = new User();
        $userStaffInvolved->setEmail("test1@example.com");
        $userStaffInvolved->setUsername("test1");
        $userStaffInvolved->setPassword("ouhosu");
        $this->em->persist($userStaffInvolved);

        $project = new Project();
        $project->setTitle('Test');
        $project->setPublicId('test');
        $project->setOwner($user);
        $this->em->persist($project);

        $caseStudy = new CaseStudy();
        $caseStudy->setProject($project);
        $caseStudy->setPublicId('test');
        $this->em->persist($caseStudy);

        $caseStudyHasUser = new CaseStudyHasUser();
        $caseStudyHasUser->setCaseStudy($caseStudy);
        $caseStudyHasUser->setUser($userStaffInvolved);
        $caseStudyHasUser->setAddedBy($user);
        $this->em->persist($caseStudyHasUser);

        $this->em->flush();

        // Test!
        $projectVoter = new CaseStudyVoter($this->em);

        $token = new TokenInterfaceForTesting();
        $token->setUser($userStaffInvolved);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED , $projectVoter->vote($token, $caseStudy, array(CaseStudyVoter::EDIT)));

    }





}

