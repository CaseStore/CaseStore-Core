<?php

namespace CaseStoreBundle\Tests\Security;



use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Entity\User;
use CaseStoreBundle\Security\ProjectVoter;
use CaseStoreBundle\Tests\BaseTestWithDataBase;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class ProjectVoterTest extends BaseTestWithDataBase {


    public function testViewByOwner() {


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

        $this->em->flush();

        // Test!
        $projectVoter = new ProjectVoter();

        $token = new TokenInterfaceForTesting();
        $token->setUser($user);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED , $projectVoter->vote($token, $project, array(ProjectVoter::VIEW)));

    }


    public function testViewByAnonymous() {


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

        $this->em->flush();

        // Test!
        $projectVoter = new ProjectVoter();

        $token = new TokenInterfaceForTesting();
        $token->setUser(null);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED , $projectVoter->vote($token, $project, array(ProjectVoter::VIEW)));

    }

    public function testViewByOther() {


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


        $userOther = new User();
        $userOther->setEmail("testOther@example.com");
        $userOther->setPassword("ouhosu");
        $this->em->persist($userOther);

        $this->em->flush();

        // Test!
        $projectVoter = new ProjectVoter();

        $token = new TokenInterfaceForTesting();
        $token->setUser($userOther);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED , $projectVoter->vote($token, $project, array(ProjectVoter::VIEW)));

    }


    public function testAdminByOwner() {


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

        $this->em->flush();

        // Test!
        $projectVoter = new ProjectVoter();

        $token = new TokenInterfaceForTesting();
        $token->setUser($user);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED , $projectVoter->vote($token, $project, array(ProjectVoter::ADMIN)));

    }


    public function testAdminByAnonymous() {


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

        $this->em->flush();

        // Test!
        $projectVoter = new ProjectVoter();

        $token = new TokenInterfaceForTesting();
        $token->setUser(null);

        $this->assertEquals(VoterInterface::ACCESS_DENIED , $projectVoter->vote($token, $project, array(ProjectVoter::ADMIN)));

    }

    public function testAdminByOther() {


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


        $userOther = new User();
        $userOther->setEmail("testOther@example.com");
        $userOther->setPassword("ouhosu");
        $this->em->persist($userOther);

        $this->em->flush();

        // Test!
        $projectVoter = new ProjectVoter();

        $token = new TokenInterfaceForTesting();
        $token->setUser($userOther);

        $this->assertEquals(VoterInterface::ACCESS_DENIED , $projectVoter->vote($token, $project, array(ProjectVoter::ADMIN)));

    }



}

