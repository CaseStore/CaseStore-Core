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

    public function getForProject(Project $project, $includeCaseStudyUsersOnlyFields = true) {
        $where = array('project' => $project);
        if (!$includeCaseStudyUsersOnlyFields) {
            $where['isCaseStudyUsersOnly'] = false;
        }
        return $this->findBy( $where, array('sort'=>'ASC') );
    }


    public function getNextSortOrderForNewFieldOnProject(Project $project) {
        $s =  $this->getEntityManager()
            ->createQuery(
                ' SELECT MAX(csfd.sort) AS sort FROM CaseStoreBundle:CaseStudyFieldDefinition csfd'.
                ' WHERE csfd.project = :project '.
                ' GROUP BY csfd.project'
            )
            ->setParameter('project', $project)
            ->getResult();
        return $s ? $s[0]['sort'] + 10 : 10;
    }
}


