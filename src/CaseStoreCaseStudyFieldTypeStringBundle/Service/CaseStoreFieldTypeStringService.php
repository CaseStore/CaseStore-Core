<?php

namespace CaseStoreCaseStudyFieldTypeStringBundle\Service;

use CaseStoreBundle\CaseStudyFieldTypeServiceInterface;

class CaseStoreFieldTypeStringService  implements CaseStudyFieldTypeServiceInterface {

    public function getId()
    {
        return 'string';
    }

    public function getTitle()
    {
        return 'String';
    }
}
