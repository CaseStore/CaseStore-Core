<?php

namespace CaseStoreCaseStudyFieldTypeTextBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="case_study_field_value_text_cache")
 * @ORM\Entity(repositoryClass="CaseStoreCaseStudyFieldTypeTextBundle\Repository\CaseStudyFieldValueTextCacheRepository")
 * @license 3-clause BSD
 * @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyFieldValueTextCache
{

    /**
     * @ORM\ID
     * @ORM\ManyToOne(targetEntity="CaseStoreBundle\Entity\CaseStudy")
     * @ORM\JoinColumn(name="case_study_id", referencedColumnName="id", nullable=false)
     */
    private $caseStudy;

    /**
     * @ORM\ID
     * @ORM\ManyToOne(targetEntity="CaseStoreBundle\Entity\CaseStudyFieldDefinition")
     * @ORM\JoinColumn(name="case_study_field_definition_id", referencedColumnName="id", nullable=false)
     */
    private $fieldDefinition;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    private $value;


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
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }


}
