<?php

namespace CaseStoreBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyFieldValueSelectRepository extends EntityRepository
{


    public function getLatestValuesFor(CaseStudyFieldDefinition $caseStudyFieldDefinition, CaseStudy $caseStudy) {

        return $this->getEntityManager()
            ->createQuery(
                ' SELECT fv FROM CaseStoreBundle:CaseStudyFieldValueSelect fv'.
                ' WHERE    fv.caseStudy = :caseStudy AND fv.fieldDefinition = :fieldDefinition AND fv.removedAt IS NULL  '.
                ' ORDER BY fv.addedAt DESC '.
                ' '
            )
            ->setParameter('caseStudy', $caseStudy)
            ->setParameter('fieldDefinition', $caseStudyFieldDefinition)
            ->getResult();

    }

    public function addOptionToCaseStudyField(CaseStudyFieldDefinitionOption $option, CaseStudy $caseStudy, User $addedBy) {

        $existingValues = $this->getEntityManager()
            ->createQuery(
                ' SELECT fv FROM CaseStoreBundle:CaseStudyFieldValueSelect fv'.
                ' WHERE    fv.caseStudy = :caseStudy AND fv.option = :option AND fv.removedAt IS NULL  '
            )
            ->setParameter('caseStudy', $caseStudy)
            ->setParameter('option', $option)
            ->getResult();

        if ($existingValues) {
            return;
        }

        $caseStudyFieldValueSelect = new CaseStudyFieldValueSelect();
        $caseStudyFieldValueSelect->setFieldDefinition($option->getFieldDefinition());
        $caseStudyFieldValueSelect->setCaseStudy($caseStudy);
        $caseStudyFieldValueSelect->setAddedBy($addedBy);
        $caseStudyFieldValueSelect->setOption($option);
        $this->getEntityManager()->persist($caseStudyFieldValueSelect);

    }

    public function removeOptionFromCaseStudyField(CaseStudyFieldDefinitionOption $option, CaseStudy $caseStudy, User $removedBy) {

        $existingValues = $this->getEntityManager()
            ->createQuery(
                ' SELECT fv FROM CaseStoreBundle:CaseStudyFieldValueSelect fv'.
                ' WHERE    fv.caseStudy = :caseStudy AND fv.option = :option AND fv.removedAt IS NULL  '
            )
            ->setParameter('caseStudy', $caseStudy)
            ->setParameter('option', $option)
            ->getResult();

        foreach($existingValues as $existingValue) {
            $existingValue->setRemovedAt(new \DateTime());
            $existingValue->setRemovedBy($removedBy);
            $this->getEntityManager()->persist($existingValue);
        }

    }


}