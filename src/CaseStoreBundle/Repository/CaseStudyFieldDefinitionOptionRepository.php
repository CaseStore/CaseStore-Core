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
        return $this->findBy(array('fieldDefinition'=>$caseStudyFieldDefinition), array('title'=>'ASC'));
    }


}

