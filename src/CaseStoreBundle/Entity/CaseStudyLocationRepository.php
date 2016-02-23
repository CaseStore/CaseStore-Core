<?php

namespace CaseStoreBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyLocationRepository extends EntityRepository
{

    public function doesPublicIdExist($id, CaseStudy $caseStudy)
    {
        if ($caseStudy->getId()) {
            $s =  $this->getEntityManager()
                ->createQuery(
                    ' SELECT l FROM CaseStoreBundle:CaseStudyLocation l '.
                    ' WHERE l.caseStudy = :caseStudy AND l.publicId = :public_id'
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