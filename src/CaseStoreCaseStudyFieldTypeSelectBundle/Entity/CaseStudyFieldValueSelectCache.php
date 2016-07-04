<?php

namespace CaseStoreCaseStudyFieldTypeSelectBundle\Entity;

use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use CaseStoreBundle\Entity\CaseStudyFieldDefinitionOption;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="case_study_field_value_select_cache")
 * @ORM\Entity(repositoryClass="CaseStoreCaseStudyFieldTypeSelectBundle\Repository\CaseStudyFieldValueSelectCacheRepository")
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyFieldValueSelectCache
{


    /**
     * @var CaseStudy
     * @ORM\ID
     * @ORM\ManyToOne(targetEntity="CaseStoreBundle\Entity\CaseStudy", inversedBy="fieldValueSelect")
     * @ORM\JoinColumn(name="case_study_id", referencedColumnName="id", nullable=false)
     */
    private $caseStudy;

    /**
     * @var CaseStudyFieldDefinition
     * @ORM\ID
     * @ORM\ManyToOne(targetEntity="CaseStoreBundle\Entity\CaseStudyFieldDefinition")
     * @ORM\JoinColumn(name="case_study_field_definition_id", referencedColumnName="id", nullable=false)
     */
    private $fieldDefinition;

    /**
     * @var CaseStudyFieldDefinitionOption
     * @ORM\ID
     * @ORM\ManyToOne(targetEntity="CaseStoreBundle\Entity\CaseStudyFieldDefinitionOption")
     * @ORM\JoinColumn(name="case_study_field_definition_option_id", referencedColumnName="id", nullable=true)
     */
    private $option;



    /**
     * @return CaseStudy
     */
    public function getCaseStudy()
    {
        return $this->caseStudy;
    }

    /**
     * @param CaseStudy $caseStudy
     */
    public function setCaseStudy($caseStudy)
    {
        $this->caseStudy = $caseStudy;
    }

    /**
     * @return CaseStudyFieldDefinition
     */
    public function getFieldDefinition()
    {
        return $this->fieldDefinition;
    }

    /**
     * @param CaseStudyFieldDefinition $fieldDefinition
     */
    public function setFieldDefinition($fieldDefinition)
    {
        $this->fieldDefinition = $fieldDefinition;
    }

    /**
     * @return CaseStudyFieldDefinitionOption
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * @param CaseStudyFieldDefinitionOption $option
     */
    public function setOption($option)
    {
        $this->option = $option;
    }


}

