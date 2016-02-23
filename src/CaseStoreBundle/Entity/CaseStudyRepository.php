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



        // TODO sort by field order
        $caseStudyFieldDefinitions = $this->getEntityManager()->getRepository('CaseStoreBundle:CaseStudyFieldDefinition')->findBy(array('project'=>$caseStudy->getProject()));

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

}