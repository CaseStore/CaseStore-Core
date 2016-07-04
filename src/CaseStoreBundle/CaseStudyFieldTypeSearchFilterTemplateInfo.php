<?php

namespace CaseStoreBundle;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */

class CaseStudyFieldTypeSearchFilterTemplateInfo implements CaseStudyFieldTypeSearchFilterTemplateInfoInterface {


    protected  $path;

    protected  $variables;

    /**
     * @param $path
     * @param $variables
     */
    public function __construct($path, $variables = array())
    {
        $this->path = $path;
        $this->variables = $variables;
    }


    public function getTemplatePath()
    {
        return $this->path;
    }

    public function getTemplateVariables()
    {
        return $this->variables;
    }
}
