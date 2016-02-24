<?php

namespace CaseStoreBundle\Entity;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyRepository extends EntityRepository
{

    public function updateCaches (CaseStudy $caseStudy) {



        $caseStudyFieldDefinitions = $this->getEntityManager()->getRepository('CaseStoreBundle:CaseStudyFieldDefinition')->getForProject($caseStudy->getProject());

        $titleFound = false;
        $descriptionFound = false;

        foreach($caseStudyFieldDefinitions as $caseStudyFieldDefinition) {
            if (!$titleFound && $caseStudyFieldDefinition->isTypeString()) {

                $titleFound = true;

                $value = $this->getEntityManager()->
                    getRepository('CaseStoreBundle:CaseStudyFieldValueString')->
                    getLatestValueFor($caseStudyFieldDefinition, $caseStudy);
                $caseStudy->setTitle($value ? $value->getValue() : null);

            } else if (!$descriptionFound && $caseStudyFieldDefinition->isTypeText()) {

                $descriptionFound = true;

                $value = $this->getEntityManager()->
                    getRepository('CaseStoreBundle:CaseStudyFieldValueText')->
                    getLatestValueFor($caseStudyFieldDefinition, $caseStudy);
                $caseStudy->setDescription($value ? $value->getValue() : null);

            }
        }

        $this->getEntityManager()->persist($caseStudy);
        $this->getEntityManager()->flush($caseStudy);

    }

    public function getForUserInProject(User $user, Project $project) {

        return  $this->getEntityManager()
            ->createQuery(
                ' SELECT cs FROM CaseStoreBundle:CaseStudy cs '.
                ' JOIN cs.hasUsers cshu '.
                ' WHERE cs.project = :project AND cshu.user = :user AND cshu.removedAt IS NULL '
            )
            ->setParameter('project', $project)
            ->setParameter('user', $user)
            ->getResult();
    }

    public function findBySelectOption(CaseStudyFieldDefinitionOption $caseStudyFieldDefinitionOption) {

        return  $this->getEntityManager()
            ->createQuery(
                ' SELECT cs FROM CaseStoreBundle:CaseStudy cs '.
                ' JOIN  cs.fieldValueSelect fvs '.
                ' WHERE fvs.option = :option AND fvs.removedAt IS NULL '
            )
            ->setParameter('option', $caseStudyFieldDefinitionOption)
            ->getResult();

    }

}

