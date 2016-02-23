<?php

namespace CaseStoreBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyCommentRepository extends EntityRepository
{

    public function doesPublicIdExist($id, CaseStudy $caseStudy)
    {
        if ($caseStudy->getId()) {
            $s =  $this->getEntityManager()
                ->createQuery(
                    ' SELECT c FROM CaseStoreBundle:CaseStudyComment c '.
                    ' WHERE c.caseStudy = :caseStudy AND c.publicId = :public_id'
                )
                ->setParameter('caseStudy', $caseStudy)
                ->setParameter('public_id', $id)
                ->getResult();
            return (boolean)$s;
        } else {
            return false;
        }
    }

}