<?php

namespace CaseStoreCaseStudyFieldTypeSelectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="case_study_field_value_select")
 * @ORM\Entity(repositoryClass="CaseStoreCaseStudyFieldTypeSelectBundle\Repository\CaseStudyFieldValueSelectRepository")
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyFieldValueSelect
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
     * @ORM\ManyToOne(targetEntity="CaseStoreBundle\Entity\CaseStudy", inversedBy="fieldValueSelect")
     * @ORM\JoinColumn(name="case_study_id", referencedColumnName="id", nullable=false)
     */
    private $caseStudy;

    /**
     * @ORM\ManyToOne(targetEntity="CaseStoreBundle\Entity\CaseStudyFieldDefinition")
     * @ORM\JoinColumn(name="case_study_field_definition_id", referencedColumnName="id", nullable=false)
     */
    private $fieldDefinition;

    /**
     * @ORM\ManyToOne(targetEntity="CaseStoreBundle\Entity\CaseStudyFieldDefinitionOption")
     * @ORM\JoinColumn(name="case_study_field_definition_option_id", referencedColumnName="id", nullable=true)
     */
    private $option;

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
    public function getFieldDefinition()
    {
        return $this->fieldDefinition;
    }

    /**
     * @param mixed $fieldDefinition
     */
    public function setFieldDefinition($fieldDefinition)
    {
        $this->fieldDefinition = $fieldDefinition;
    }

    /**
     * @return mixed
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * @param mixed $option
     */
    public function setOption($option)
    {
        $this->option = $option;
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
     * @ORM\PrePersist()
     */
    public function beforeFirstSave() {
        $this->addedAt = new \DateTime("", new \DateTimeZone("UTC"));
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

    /**
     * @param mixed $removedAt
     */
    public function setRemovedAt($removedAt)
    {
        $this->removedAt = $removedAt;
    }



}