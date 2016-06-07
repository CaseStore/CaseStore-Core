<?php

namespace CaseStoreBundle\Repository;


use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyFieldDefinitionOption;
use CaseStoreBundle\Entity\Output;
use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Entity\User;
use CaseStoreBundle\Repository\QueryBuilder\CaseStudyQueryBuilder;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyRepository extends EntityRepository
{



    public function doesPublicIdExist($id, Project $project)
    {
        if ($project->getId()) {
            $s =  $this->getEntityManager()
                ->createQuery(
                    ' SELECT cs FROM CaseStoreBundle:CaseStudy cs'.
                    ' WHERE cs.project = :project AND cs.publicId = :public_id'
                )
                ->setParameter('project', $project)
                ->setParameter('public_id', $id)
                ->getResult();
            return (boolean)$s;
        } else {
            return false;
        }
    }


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

    public function getQueryBuilder(Project $project)
    {
        return new CaseStudyQueryBuilder($this->getEntityManager(), $project);
    }

}

