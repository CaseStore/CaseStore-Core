<?php

namespace CaseStoreBundle\Repository;

use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyHasOutput;
use CaseStoreBundle\Entity\Output;
use CaseStoreBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyHasOutputRepository extends EntityRepository
{

    public function isLinked(CaseStudy $caseStudy, Output $output) {
        $existings = $this->getEntityManager()
            ->createQuery(
                ' SELECT csho FROM CaseStoreBundle:CaseStudyHasOutput csho '.
                ' WHERE csho.caseStudy = :caseStudy AND csho.output = :output AND csho.removedAt IS NULL  '
            )
            ->setParameter('caseStudy', $caseStudy)
            ->setParameter('output', $output)
            ->getResult();
        return count($existings) > 0;
    }

    public function addLink(CaseStudy $caseStudy, Output $output, User $user = null)
    {

        if (!$this->isLinked($caseStudy, $output)) {
            $new = new CaseStudyHasOutput();
            $new->setAddedBy($user);
            $new->setCaseStudy($caseStudy);
            $new->setOutput($output);
            $this->getEntityManager()->persist($new);
            $this->getEntityManager()->flush($new);
        }
        
    }

}

