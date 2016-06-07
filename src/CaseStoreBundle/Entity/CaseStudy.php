<?php

namespace CaseStoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="case_study", uniqueConstraints={@ORM\UniqueConstraint(name="public_id", columns={"project_id", "public_id"})})
 * @ORM\Entity(repositoryClass="CaseStoreBundle\Repository\CaseStudyRepository")
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudy
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
     * @ORM\Column(name="public_id", type="string", length=250, unique=true, nullable=false)
     * @Assert\NotBlank()
     */
    private $publicId;

    /**
     * @ORM\ManyToOne(targetEntity="CaseStoreBundle\Entity\Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     */
    private $project;


    /**
     * @var string
     *
     * @ORM\Column(name="cached_title", type="string", length=250, nullable=true)
     */
    private $title;


    /**
     * @var string
     *
     * @ORM\Column(name="cached_description", type="string", length=250, nullable=true)
     */
    private $description;


    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="CaseStoreBundle\Entity\CaseStudyHasUser", mappedBy="caseStudy")
     */
    private $hasUsers;


    /**
     * @ORM\OneToMany(targetEntity="CaseStoreBundle\Entity\CaseStudyFieldValueSelect", mappedBy="caseStudy")
     */
    private $fieldValueSelect;

    /**
     * @ORM\OneToMany(targetEntity="CaseStoreBundle\Entity\CaseStudyHasOutput", mappedBy="caseStudy")
     */
    private $hasOutputs;


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
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getTitleOrPlaceHolder()
    {
        return $this->title ? $this->title : 'Case Study '.$this->publicId;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     * @return mixed
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param mixed $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getHasUsers()
    {
        return $this->hasUsers;
    }

    /**
     * @param mixed $hasUsers
     */
    public function setHasUsers($hasUsers)
    {
        $this->hasUsers = $hasUsers;
    }

    /**
     * @return mixed
     */
    public function getFieldValueSelect()
    {
        return $this->fieldValueSelect;
    }

    /**
     * @param mixed $fieldValueSelect
     */
    public function setFieldValueSelect($fieldValueSelect)
    {
        $this->fieldValueSelect = $fieldValueSelect;
    }



    /**
     * @ORM\PrePersist()
     */
    public function beforeFirstSave() {
        $this->createdAt = new \DateTime("", new \DateTimeZone("UTC"));
    }



}
