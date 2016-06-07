<?php

namespace CaseStoreBundle\Repository;

use CaseStoreBundle\Entity\Project;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class OutputFieldDefinitionRepository extends EntityRepository
{

    public function getForProject(Project $project) {
        return $this->findBy(array('project'=>$project),array('sort'=>'ASC'));
    }


    public function getNextSortOrderForNewFieldOnProject(Project $project) {
        $s =  $this->getEntityManager()
            ->createQuery(
                ' SELECT MAX(ofd.sort) AS sort FROM CaseStoreBundle:OutputFieldDefinition ofd '.
                ' WHERE ofd.project = :project '.
                ' GROUP BY ofd.project'
            )
            ->setParameter('project', $project)
            ->getResult();
        return $s ? $s[0]['sort'] + 10 : 10;
    }
}


