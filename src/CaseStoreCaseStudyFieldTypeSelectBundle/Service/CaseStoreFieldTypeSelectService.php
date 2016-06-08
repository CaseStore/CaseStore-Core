<?php

namespace CaseStoreCaseStudyFieldTypeSelectBundle\Service;

use CaseStoreBundle\CaseStudyFieldTypeServiceInterface;

class CaseStoreFieldTypeSelectService  implements CaseStudyFieldTypeServiceInterface {

    public function getId()
    {
        return 'select';
    }

    public function getTitle()
    {
        return 'Select';
    }
}
