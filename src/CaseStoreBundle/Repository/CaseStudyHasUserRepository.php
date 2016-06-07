<?php

namespace CaseStoreBundle\Repository;

use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyHasUserRepository extends EntityRepository
{

    public function isCaseStudyHaveUser(CaseStudy $caseStudy, User $user = null)
    {
        if ($user) {
            $s =  $this->getEntityManager()
                ->createQuery(
                    ' SELECT cshu FROM CaseStoreBundle:CaseStudyHasUser cshu'.
                    ' WHERE cshu.caseStudy = :caseStudy AND cshu.user = :user AND cshu.removedAt IS NULL  '
                )
                ->setParameter('caseStudy', $caseStudy)
                ->setParameter('user', $user)
                ->getResult();
            return (boolean)$s;
        } else {
            return false;
        }
    }

}

