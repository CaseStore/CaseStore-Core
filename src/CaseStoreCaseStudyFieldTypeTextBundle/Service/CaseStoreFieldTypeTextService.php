<?php

namespace CaseStoreCaseStudyFieldTypeTextBundle\Service;

use CaseStoreBundle\CaseStudyFieldTypeServiceInterface;

class CaseStoreFieldTypeTextService implements CaseStudyFieldTypeServiceInterface {

    public function getId()
    {
        return 'text';
    }

    public function getTitle()
    {
        return 'Text';
    }
}
