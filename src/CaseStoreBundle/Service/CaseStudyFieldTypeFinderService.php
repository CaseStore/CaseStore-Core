<?php

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */


namespace CaseStoreBundle\Service;


use CaseStoreBundle\CaseStudyFieldTypeServiceInterface;

class CaseStudyFieldTypeFinderService {


    protected $container;

    /**
     * FeedbackFieldTypeURL constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    protected $fieldTypes = array();

    public function addFieldType(CaseStudyFieldTypeServiceInterface $caseStudyFieldTypeServiceInterface) {
        $this->fieldTypes[$caseStudyFieldTypeServiceInterface->getId()] = $caseStudyFieldTypeServiceInterface;
    }

    public function getFieldTypes() {
        return $this->fieldTypes;
    }

    public function getFieldTypeById($id) {
        return isset($this->fieldTypes[$id]) ? $this->fieldTypes[$id] : null;
    }

}
