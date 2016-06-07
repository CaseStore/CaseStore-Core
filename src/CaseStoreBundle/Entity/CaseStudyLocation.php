<?php

namespace CaseStoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="case_study_location", uniqueConstraints={@ORM\UniqueConstraint(name="public_id", columns={"case_study_id", "public_id"})})
 * @ORM\Entity(repositoryClass="CaseStoreBundle\Repository\CaseStudyLocationRepository" )
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyLocation
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="CaseStoreBundle\Entity\CaseStudy")
     * @ORM\JoinColumn(name="case_study_id", referencedColumnName="id", nullable=false)
     */
    private $caseStudy;


    /**
     * @ORM\Column(name="public_id", type="string", length=250, unique=false, nullable=false)
     */
    private $publicId;


    /**
     * @ORM\Column(name="lat", type="float", nullable=false)
     */
    private $lat;

    /**
     * @ORM\Column(name="lng", type="float", nullable=false)
     */
    private $lng;


    /**
     * @ORM\ManyToOne(targetEntity="CaseStoreBundle\Entity\User")
     * @ORM\JoinColumn(name="added_by_id", referencedColumnName="id", nullable=false)
     */
    private $addedBy;

    /**
     * @ORM\Column(name="added_at", type="datetime", nullable=false)
     */
    private $addedAt;

    /**
     * @ORM\ManyToOne(targetEntity="CaseStoreBundle\Entity\User")
     * @ORM\JoinColumn(name="removed_by_id", referencedColumnName="id", nullable=true)
     */
    private $removedBy;

    /**
     * @ORM\Column(name="removed_at", type="datetime", nullable=true)
     */
    private $removedAt;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCaseStudy()
    {
        return $this->caseStudy;
    }

    /**
     * @param mixed $caseStudy
     */
    public function setCaseStudy($caseStudy)
    {
        $this->caseStudy = $caseStudy;
    }

    /**
     * @return mixed
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param mixed $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * @return mixed
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @param mixed $lng
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    }

    /**
     * @return mixed
     */
    public function getAddedBy()
    {
        return $this->addedBy;
    }

    /**
     * @param mixed $addedBy
     */
    public function setAddedBy($addedBy)
    {
        $this->addedBy = $addedBy;
    }

    /**
     * @return mixed
     */
    public function getAddedAt()
    {
        return $this->addedAt;
    }

    /**
     * @param mixed $addedAt
     */
    public function setAddedAt($addedAt)
    {
        $this->addedAt = $addedAt;
    }

    /**
     * @return mixed
     */
    public function getRemovedBy()
    {
        return $this->removedBy;
    }

    /**
     * @param mixed $removedBy
     */
    public function setRemovedBy($removedBy)
    {
        $this->removedBy = $removedBy;
    }

    /**
     * @return mixed
     */
    public function getRemovedAt()
    {
        return $this->removedAt;
    }

    public function isRemoved() {
        return (boolean)$this->removedAt;
    }

    /**
     * @param mixed $removedAt
     */
    public function setRemovedAt($removedAt)
    {
        $this->removedAt = $removedAt;
    }

    /**
     * @return mixed
     */
    public function getPublicId()
    {
        return $this->publicId;
    }

    /**
     * @param mixed $publicId
     */
    public function setPublicId($publicId)
    {
        $this->publicId = $publicId;
    }


    /**
     * @ORM\PrePersist()
     */
    public function beforeFirstSave() {
        $this->addedAt = new \DateTime("", new \DateTimeZone("UTC"));
    }



}