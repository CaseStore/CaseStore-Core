<?php

namespace CaseStoreBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class OutputDocumentRepository extends EntityRepository
{

    public function doesPublicIdExist($id, Output $output)
    {
        if ($output->getId()) {
            $s =  $this->getEntityManager()
                ->createQuery(
                    ' SELECT d FROM CaseStoreBundle:OutputDocument d'.
                    ' WHERE d.output = :output AND d.publicId = :public_id'
                )
                ->setParameter('output', $output)
                ->setParameter('public_id', $id)
                ->getResult();
            return (boolean)$s;
        } else {
            return false;
        }
    }

}

