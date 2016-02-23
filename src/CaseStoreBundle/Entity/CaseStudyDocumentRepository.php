<?php

namespace CaseStoreBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyDocumentRepository extends EntityRepository
{

    public function doesPublicIdExist($id, CaseStudy $caseStudy)
    {
        if ($caseStudy->getId()) {
            $s =  $this->getEntityManager()
                ->createQuery(
                    ' SELECT d FROM CaseStoreBundle:CaseStudyDocument d'.
                    ' WHERE d.caseStudy = :caseStudy AND d.publicId = :public_id'
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