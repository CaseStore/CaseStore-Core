<?php

namespace CaseStoreBundle\Repository;

use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyHasUser;
use CaseStoreBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class UserRepository extends EntityRepository {

    public function findByCaseStudy(CaseStudy $caseStudy)
    {
        return $this->getEntityManager()
            ->createQuery(
                ' SELECT u FROM CaseStoreBundle:User u'.
                ' JOIN u.inCaseStudies cshu '.
                ' WHERE    cshu.caseStudy = :caseStudy AND cshu.removedAt IS NULL '.
                ' ORDER BY u.username ASC '.
                '  '
            )
            ->setParameter('caseStudy', $caseStudy)
            ->getResult();

    }

    public function addUserToCaseStudy(User $user, CaseStudy $caseStudy, User $userDoingAction) {
        $x = $this->getEntityManager()->createQuery("SELECT cshu FROM CaseStoreBundle:CaseStudyHasUser cshu ".
            "WHERE cshu.removedAt IS NULL AND cshu.caseStudy = :caseStudy AND cshu.user = :user ")
            ->execute(array(
                'caseStudy'=>$caseStudy,
                'user'=>$user,
            ));


        if (!$x) {

            $caseStudyHasUser = new CaseStudyHasUser();
            $caseStudyHasUser->setAddedBy($userDoingAction);
            $caseStudyHasUser->setUser($user);
            $caseStudyHasUser->setCaseStudy($caseStudy);
            $this->getEntityManager()->persist($caseStudyHasUser);
            $this->getEntityManager()->flush($caseStudyHasUser);

        }


    }

    public function removeUserFromCaseStudy(User $user, CaseStudy $caseStudy, User $userDoingAction) {
        $this->getEntityManager()->createQuery("UPDATE CaseStoreBundle:CaseStudyHasUser cshu ".
            "SET cshu.removedBy = :user, cshu.removedAt = :now ".
            "WHERE cshu.removedAt IS NULL AND cshu.caseStudy = :caseStudy AND cshu.user = :user ")
            ->execute(array(
                'user'=>$userDoingAction,
                'now'=>new \DateTime(),
                'caseStudy'=>$caseStudy,
                'user'=>$user,
            ));
    }

}

