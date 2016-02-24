<?php

namespace CaseStoreBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyFieldDefinitionRepository extends EntityRepository
{

    public function getForProject(Project $project) {
        return $this->findBy(array('project'=>$project),array('sort'=>'ASC'));
    }

}


