<?php

namespace CaseStoreCaseStudyFieldTypeIntegerBundle\Repository;


use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyFieldValueIntegerRepository extends EntityRepository
{

    public function getLatestValueFor(CaseStudyFieldDefinition $caseStudyFieldDefinition, CaseStudy $caseStudy) {

        return $this->getEntityManager()
            ->createQuery(
                ' SELECT fv FROM CaseStoreCaseStudyFieldTypeIntegerBundle:CaseStudyFieldValueInteger fv'.
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
