<?php

namespace CaseStoreCaseStudyFieldTypeIntegerBundle\Service;


use CaseStoreBundle\CaseStudyFieldTypeServiceInterface;

class CaseStoreFieldTypeIntegerService  implements CaseStudyFieldTypeServiceInterface {

    public function getId()
    {
        return 'integer';
    }

    public function getTitle()
    {
        return 'Integer';
    }
}
