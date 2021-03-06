<?php

namespace CaseStoreBundle\EventListener;



use CaseStoreBundle\CaseStoreBundle;
use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyComment;
use CaseStoreBundle\Entity\CaseStudyDocument;
use CaseStoreBundle\Entity\CaseStudyLocation;
use CaseStoreBundle\Entity\Output;
use CaseStoreBundle\Entity\OutputDocument;
use Doctrine\ORM\Event\LifecycleEventArgs;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class PrePersistEventListener  {


    const MIN_LENGTH = 5;
    const MAX_LENGTH = 250;
    const LENGTH_STEP = 1;

    function PrePersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();

        if ($entity instanceof CaseStudy) {
            if (!$entity->getPublicId()) {
                $manager = $args->getEntityManager()->getRepository('CaseStoreBundle\Entity\CaseStudy');
                $idLen = self::MIN_LENGTH;
                $id = CaseStoreBundle::createKey(1, $idLen);
                while ($manager->doesPublicIdExist($id, $entity->getProject())) {
                    if ($idLen < self::MAX_LENGTH) {
                        $idLen = $idLen + self::LENGTH_STEP;
                    }
                    $id = CaseStoreBundle::createKey(1, $idLen);
                }
                $entity->setPublicId($id);
            }
        } else if ($entity instanceof Output) {
            if (!$entity->getPublicId()) {
                $manager = $args->getEntityManager()->getRepository('CaseStoreBundle\Entity\Output');
                $idLen = self::MIN_LENGTH;
                $id = CaseStoreBundle::createKey(1, $idLen);
                while ($manager->doesPublicIdExist($id, $entity->getProject())) {
                    if ($idLen < self::MAX_LENGTH) {
                        $idLen = $idLen + self::LENGTH_STEP;
                    }
                    $id = CaseStoreBundle::createKey(1, $idLen);
                }
                $entity->setPublicId($id);
            }
        } else if ($entity instanceof CaseStudyDocument) {
            if (!$entity->getPublicId()) {
                $manager = $args->getEntityManager()->getRepository('CaseStoreBundle\Entity\CaseStudyDocument');
                $idLen = self::MIN_LENGTH;
                $id =  CaseStoreBundle::createKey(1,$idLen);
                while($manager->doesPublicIdExist($id, $entity->getCaseStudy())) {
                    if ($idLen < self::MAX_LENGTH) {
                        $idLen = $idLen + self::LENGTH_STEP;
                    }
                    $id =  CaseStoreBundle::createKey(1,$idLen);
                }
                $entity->setPublicId($id);
            }
        } else if ($entity instanceof CaseStudyComment) {
            if (!$entity->getPublicId()) {
                $manager = $args->getEntityManager()->getRepository('CaseStoreBundle\Entity\CaseStudyComment');
                $idLen = self::MIN_LENGTH;
                $id =  CaseStoreBundle::createKey(1,$idLen);
                while($manager->doesPublicIdExist($id, $entity->getCaseStudy())) {
                    if ($idLen < self::MAX_LENGTH) {
                        $idLen = $idLen + self::LENGTH_STEP;
                    }
                    $id =  CaseStoreBundle::createKey(1,$idLen);
                }
                $entity->setPublicId($id);
            }
        } else if ($entity instanceof CaseStudyLocation) {
            if (!$entity->getPublicId()) {
                $manager = $args->getEntityManager()->getRepository('CaseStoreBundle\Entity\CaseStudyLocation');
                $idLen = self::MIN_LENGTH;
                $id =  CaseStoreBundle::createKey(1,$idLen);
                while($manager->doesPublicIdExist($id, $entity->getCaseStudy())) {
                    if ($idLen < self::MAX_LENGTH) {
                        $idLen = $idLen + self::LENGTH_STEP;
                    }
                    $id =  CaseStoreBundle::createKey(1,$idLen);
                }
                $entity->setPublicId($id);
            }
        } else if ($entity instanceof OutputDocument) {
            if (!$entity->getPublicId()) {
                $manager = $args->getEntityManager()->getRepository('CaseStoreBundle\Entity\OutputDocument');
                $idLen = self::MIN_LENGTH;
                $id =  CaseStoreBundle::createKey(1,$idLen);
                while($manager->doesPublicIdExist($id, $entity->getOutput())) {
                    if ($idLen < self::MAX_LENGTH) {
                        $idLen = $idLen + self::LENGTH_STEP;
                    }
                    $id =  CaseStoreBundle::createKey(1,$idLen);
                }
                $entity->setPublicId($id);
            }
        }

    }

}
