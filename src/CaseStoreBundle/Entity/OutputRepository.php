<?php

namespace CaseStoreBundle\Entity;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class OutputRepository extends EntityRepository
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

    public function findByCaseStudy(CaseStudy $caseStudy) {
        return $this->getEntityManager()
            ->createQuery(
                ' SELECT o FROM CaseStoreBundle:Output o'.
                '  JOIN  o.hasCaseStudies csho '.
                ' WHERE csho.caseStudy = :caseStudy AND csho.removedAt IS NULL'
            )
            ->setParameter('caseStudy', $caseStudy)
            ->getResult();
    }

}

