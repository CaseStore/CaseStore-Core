<?php


namespace CaseStoreBundle\Action;
use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyFieldDefinitionOption;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyFieldDefinitionOptionPurgeAction
{

    protected $app;

    /**
     * PurgeCaseStudyAction constructor.
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function purge(CaseStudyFieldDefinitionOption $caseStudyFieldDefinitionOption) {

        $doctrine = $this->app->get('doctrine');

        $doctrine->getEntityManager()->remove($caseStudyFieldDefinitionOption);

        foreach(array('CaseStoreCaseStudyFieldTypeSelectBundle:CaseStudyFieldValueSelect','CaseStoreCaseStudyFieldTypeSelectBundle:CaseStudyFieldValueSelectCache',
                    ) as $repoString) {
            foreach ($doctrine->getRepository($repoString)->findBy(array('option' => $caseStudyFieldDefinitionOption)) as $value) {
                $doctrine->getEntityManager()->remove($value);
            }
        }

        $doctrine->getEntityManager()->flush();

    }


}
