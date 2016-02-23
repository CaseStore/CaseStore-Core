<?php

namespace CaseStoreBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyFieldValueTextRepository extends EntityRepository
{

    public function getLatestValueFor(CaseStudyFieldDefinition $caseStudyFieldDefinition, CaseStudy $caseStudy) {

        return $this->getEntityManager()
            ->createQuery(
                ' SELECT fv FROM CaseStoreBundle:CaseStudyFieldValueText fv'.
                ' WHERE    fv.caseStudy = :caseStudy AND fv.fieldDefinition = :fieldDefinition '.
                ' ORDER BY fv.addedAt DESC '.
                ' '
            )
            ->setMaxResults(1)
            ->setParameter('caseStudy', $caseStudy)
            ->setParameter('fieldDefinition', $caseStudyFieldDefinition)
            ->getOneOrNullResult();

    }



}