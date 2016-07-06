<?php

namespace CaseStoreBundle\Repository;

use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use CaseStoreBundle\Entity\Project;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyFieldDefinitionOptionRepository extends EntityRepository
{

    public function getCurrentForFieldDefinition(CaseStudyFieldDefinition $caseStudyFieldDefinition) {
        return $this->findBy(array('fieldDefinition'=>$caseStudyFieldDefinition), array('sort'=>'ASC'));
    }



    public function getNextSortValue(CaseStudyFieldDefinition $caseStudyFieldDefinition) {
        $s =  $this->getEntityManager()
            ->createQuery(
                ' SELECT MAX(csfdo.sort) AS sort FROM CaseStoreBundle:CaseStudyFieldDefinitionOption csfdo '.
                ' WHERE csfdo.fieldDefinition = :fieldDefinition '.
                ' GROUP BY csfdo.fieldDefinition '
            )
            ->setParameter('fieldDefinition', $caseStudyFieldDefinition)
            ->getResult();
        return $s ? $s[0]['sort'] + 10 : 10;
    }

}

