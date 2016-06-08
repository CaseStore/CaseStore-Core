<?php

namespace CaseStoreOutputFieldTypeStringBundle\Repository;


use CaseStoreBundle\Entity\Output;
use CaseStoreBundle\Entity\OutputFieldDefinition;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class OutputFieldValueStringRepository extends EntityRepository
{

    public function getLatestValueFor(OutputFieldDefinition $outputFieldDefinition, Output $output) {

        return $this->getEntityManager()
            ->createQuery(
                ' SELECT fv FROM CaseStoreOutputFieldTypeStringBundle:OutputFieldValueString fv'.
                ' WHERE    fv.output = :output AND fv.fieldDefinition = :fieldDefinition '.
                ' ORDER BY fv.addedAt DESC '.
                ' '
            )
            ->setMaxResults(1)
            ->setParameter('output', $output)
            ->setParameter('fieldDefinition', $outputFieldDefinition)
            ->getOneOrNullResult();

    }

}

