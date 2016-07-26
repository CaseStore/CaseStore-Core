<?php

namespace CaseStoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="case_study_field_definition", uniqueConstraints={@ORM\UniqueConstraint(name="public_id", columns={"project_id", "public_id"})})
 * @ORM\Entity(repositoryClass="CaseStoreBundle\Repository\CaseStudyFieldDefinitionRepository" )
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyFieldDefinition
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
     * @ORM\ManyToOne(targetEntity="CaseStoreBundle\Entity\Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     */
    private $project;

    /**
     * @ORM\Column(name="public_id", type="string", length=250, unique=false, nullable=false)
     * @Assert\NotBlank()
     */
    private $publicId;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=250, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=250, nullable=false)
     */
    private $title;


    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer", nullable=false)
     */
    private $sort;


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
     * @var string
     *
     * @ORM\Column(name="default_value", type="text", nullable=true)
     */
    private $defaultValue;


    /**
     * @var string
     *
     * @ORM\Column(name="is_case_study_users_only", type="boolean", nullable=false, options={"default" = 0})
     */
    private $isCaseStudyUsersOnly = false;

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
     * @return string
     */
    public function getType()
    {
        return strtolower($this->type);
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }



    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param mixed $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
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
     * @return string
     */
    public function getIsCaseStudyUsersOnly()
    {
        return $this->isCaseStudyUsersOnly;
    }

    /**
     * @param string $isCaseStudyUsersOnly
     */
    public function setIsCaseStudyUsersOnly($isCaseStudyUsersOnly)
    {
        $this->isCaseStudyUsersOnly = $isCaseStudyUsersOnly;
    }

    /**
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param string $defaultValue
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }


    public function isTypeText() {
        return strtolower($this->type) == 'text';
    }

    public function isTypeString() {
        return strtolower($this->type) == 'string';
    }

    public function isTypeSelect() {
        return strtolower($this->type) == 'select';
    }

    public function isTypeInteger() {
        return strtolower($this->type) == 'integer';
    }



    /**
     * @ORM\PrePersist()
     */
    public function beforeFirstSave() {
        $this->addedAt = new \DateTime("", new \DateTimeZone("UTC"));
    }



}